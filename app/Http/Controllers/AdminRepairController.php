<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Repair;
use App\Models\User;
use App\Models\PorscheModel;
use App\Models\ServiceCatalog;
use App\Models\RepairLog;

class AdminRepairController extends Controller
{
    // Dashboard สรุปภาพรวมสำหรับผู้ดูแลระบบ (Raphiphat001)
    public function dashboard()
    {
        $totalRepairs = Repair::count();
        $pendingRepairs = Repair::where('status', 'pending')->count();
        $inProgressRepairs = Repair::whereIn('status', ['assigned', 'inspecting', 'estimated', 'approved', 'in_progress'])->count();
        $completedRepairs = Repair::where('status', 'completed')->count();
        $totalRevenue = Repair::where('status', 'completed')->sum('net_total');
        $totalEstimated = Repair::sum('net_total');

        // สรุปยอดตามซีรีส์รถยนต์ Porsche
        $seriesStats = [
            '911' => Repair::whereHas('porscheModel', fn($q) => $q->where('series', '911'))->count(),
            '718' => Repair::whereHas('porscheModel', fn($q) => $q->where('series', '718'))->count(),
            'Taycan' => Repair::whereHas('porscheModel', fn($q) => $q->where('series', 'Taycan'))->count(),
            'Cayenne' => Repair::whereHas('porscheModel', fn($q) => $q->where('series', 'Cayenne'))->count(),
            'Macan' => Repair::whereHas('porscheModel', fn($q) => $q->where('series', 'Macan'))->count(),
            'Panamera' => Repair::whereHas('porscheModel', fn($q) => $q->where('series', 'Panamera'))->count(),
        ];

        // สถิติภาระงานของช่างแต่ละคน
        $technicians = User::where('role', 'technician')->withCount([
            'technicianRepairs as total_jobs',
            'technicianRepairs as active_jobs' => function ($query) {
                $query->whereIn('status', ['assigned', 'inspecting', 'estimated', 'approved', 'in_progress']);
            },
            'technicianRepairs as done_jobs' => function ($query) {
                $query->where('status', 'completed');
            },
        ])->get();

        // รายการแจ้งซ่อมล่าสุด 6 รายการ
        $recentRepairs = Repair::with(['customer', 'technician', 'porscheModel'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalRepairs',
            'pendingRepairs',
            'inProgressRepairs',
            'completedRepairs',
            'totalRevenue',
            'totalEstimated',
            'seriesStats',
            'technicians',
            'recentRepairs'
        ));
    }

    // หน้ารายการจัดการใบแจ้งซ่อมทั้งหมด
    public function index(Request $request)
    {
        $status = $request->query('status');
        $technicianId = $request->query('technician_id');
        $urgency = $request->query('urgency');
        $search = $request->query('search');

        $query = Repair::with(['customer', 'technician', 'porscheModel']);

        if ($status) {
            $query->where('status', $status);
        }
        if ($technicianId) {
            $query->where('technician_id', $technicianId);
        }
        if ($urgency) {
            $query->where('urgency', $urgency);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_no', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhere('symptom_title', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $repairs = $query->latest()->paginate(15)->withQueryString();
        $technicians = User::where('role', 'technician')->get();

        return view('admin.repairs.index', compact('repairs', 'technicians', 'status', 'technicianId', 'urgency', 'search'));
    }

    // มอบหมายช่างให้ใบแจ้งซ่อม
    public function assignTechnician(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'note' => 'nullable|string',
        ]);

        $tech = User::findOrFail($request->technician_id);

        $oldStatus = $repair->status;
        $newStatus = ($oldStatus === 'pending') ? 'assigned' : $oldStatus;

        $repair->update([
            'technician_id' => $tech->id,
            'status' => $newStatus,
        ]);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => Auth::id(),
            'status' => $newStatus,
            'action_title' => 'แอดมิน (' . Auth::user()->name . ') มอบหมายงานให้ช่าง ' . $tech->name,
            'comment' => $request->note ?: 'มอบหมายงานซ่อมหมายเลข ' . $repair->ticket_no,
        ]);

        return back()->with('success', 'มอบหมายงานให้ช่าง "' . $tech->name . '" เรียบร้อยแล้ว');
    }

    // แอดมินปรับเปลี่ยนสถานะโดยตรง
    public function updateStatus(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'status' => 'required',
            'comment' => 'nullable|string',
        ]);

        $repair->update(['status' => $request->status]);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'action_title' => 'แอดมิน (' . Auth::user()->name . ') ปรับเปลี่ยนสถานะโดยตรง',
            'comment' => $request->comment ?: 'สถานะใหม่: ' . $repair->status_text,
        ]);

        return back()->with('success', 'ปรับปรุงสถานะสำเร็จ');
    }

    // ใบสั่งซ่อม / ใบประเมินราคาทางการ (Print-Friendly Official Sheet)
    public function printSheet($id)
    {
        $repair = Repair::with(['customer', 'technician', 'porscheModel', 'items', 'images', 'logs.user'])
            ->findOrFail($id);

        return view('admin.repairs.print', compact('repair'));
    }

    // จัดการผู้ใช้งานในระบบ (Admin, ช่าง, ลูกค้า)
    public function usersList()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    // เพิ่มผู้ใช้งานใหม่ (เช่น เพิ่มช่างคนใหม่)
    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,technician,customer',
            'phone' => 'nullable|string',
            'specialty' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'specialty' => $request->specialty,
        ]);

        return back()->with('success', 'เพิ่มผู้ใช้งานสำเร็จ');
    }

    // จัดการแคตตาล็อกอะไหล่และค่าแรง
    public function catalogList()
    {
        $catalogs = ServiceCatalog::latest()->paginate(15);
        return view('admin.catalogs.index', compact('catalogs'));
    }

    // เพิ่มอะไหล่ใหม่ในแคตตาล็อก
    public function catalogStore(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'part_code' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'labor_fee' => 'required|numeric|min:0',
            'unit' => 'required|string',
        ]);

        ServiceCatalog::create($request->all());

        return back()->with('success', 'เพิ่มรายการในแคตตาล็อกสำเร็จ');
    }

    // ลบรายการใบแจ้งซ่อม (สำหรับ Admin)
    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $ticket = $repair->ticket_no;
        $repair->delete();

        return redirect()->route('admin.repairs.index')->with('success', 'ลบใบแจ้งซ่อม ' . $ticket . ' เรียบร้อยแล้ว');
    }
}
