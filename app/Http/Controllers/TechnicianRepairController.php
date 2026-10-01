<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Repair;
use App\Models\RepairItem;
use App\Models\RepairImage;
use App\Models\RepairLog;
use App\Models\ServiceCatalog;

class TechnicianRepairController extends Controller
{
    // หน้ารายการงานซ่อมของช่าง
    public function index(Request $request)
    {
        $tech = Auth::user();
        $tab = $request->query('tab', 'my_jobs'); // my_jobs หรือ available_jobs
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Repair::with(['customer', 'porscheModel', 'technician']);

        if ($tab === 'my_jobs') {
            $query->where('technician_id', $tech->id);
        } else {
            // งานที่ยังไม่มีช่าง หรือรอดำเนินการ
            $query->whereNull('technician_id')->orWhere('status', 'pending');
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_no', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhere('symptom_title', 'like', "%{$search}%");
            });
        }

        $repairs = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'assigned' => Repair::where('technician_id', $tech->id)->count(),
            'inspecting' => Repair::where('technician_id', $tech->id)->where('status', 'inspecting')->count(),
            'in_progress' => Repair::where('technician_id', $tech->id)->where('status', 'in_progress')->count(),
            'completed' => Repair::where('technician_id', $tech->id)->where('status', 'completed')->count(),
        ];

        return view('technician.repairs.index', compact('repairs', 'stats', 'tab', 'status', 'search'));
    }

    // หน้ารายละเอียดและพื้นที่ทำงานตรวจเช็ค/ซ่อม (Technician Workstation)
    public function show($id)
    {
        $repair = Repair::with(['customer', 'porscheModel', 'technician', 'items', 'images', 'logs.user'])
            ->findOrFail($id);

        $catalogs = ServiceCatalog::where('is_active', true)->orderBy('category')->orderBy('name')->get();

        return view('technician.repairs.show', compact('repair', 'catalogs'));
    }

    // ช่างกดรับงาน (กรณีเป็นงานว่าง)
    public function claimJob($id)
    {
        $repair = Repair::findOrFail($id);
        $tech = Auth::user();

        $repair->update([
            'technician_id' => $tech->id,
            'status' => 'assigned',
        ]);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => $tech->id,
            'status' => 'assigned',
            'action_title' => 'ช่าง ' . $tech->name . ' กดรับมอบหมายงานซ่อมนี้',
            'comment' => 'ช่างพร้อมเตรียมเครื่องมือและช่องซ่อม',
        ]);

        return back()->with('success', 'คุณได้รับงานซ่อมหมายเลข ' . $repair->ticket_no . ' เรียบร้อยแล้ว');
    }

    // ช่างอัปเดตสถานะงานซ่อม
    public function updateStatus(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $tech = Auth::user();

        $request->validate([
            'status' => 'required|in:inspecting,estimated,in_progress,completed',
            'status_note' => 'nullable|string',
        ]);

        $newStatus = $request->status;
        $note = $request->status_note;

        $actionTitle = match ($newStatus) {
            'inspecting' => 'ช่าง ' . $tech->name . ' เริ่มนำรถขึ้นลิฟต์ตรวจเช็คสภาพ',
            'estimated' => 'ช่าง ' . $tech->name . ' ส่งใบประเมินราคาให้ลูกค้าพิจารณา',
            'in_progress' => 'ช่าง ' . $tech->name . ' เริ่มดำเนินการซ่อมตามรายการอนุมัติ',
            'completed' => 'ช่าง ' . $tech->name . ' ซ่อมเสร็จสิ้นและทดสอบระบบเรียบร้อย (Ready for Delivery)',
            default => 'อัปเดตสถานะเป็น ' . $newStatus,
        };

        $data = ['status' => $newStatus];
        if ($newStatus === 'completed') {
            $data['repair_completed_at'] = now();
        }

        $repair->update($data);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => $tech->id,
            'status' => $newStatus,
            'action_title' => $actionTitle,
            'comment' => $note ?: 'บันทึกการทำงานโดย ' . $tech->name,
        ]);

        return back()->with('success', 'อัปเดตสถานะงานซ่อมเป็น "' . $repair->status_text . '" สำเร็จ');
    }

    // บันทึกรายการตรวจเช็คสภาพรถ 5 หมวด (Checklist) และบันทึกของช่าง
    public function saveInspection(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $checklist = [
            'brake_system' => $request->input('checklist.brake_system', 'pass'),
            'tires' => $request->input('checklist.tires', 'pass'),
            'battery' => $request->input('checklist.battery', 'pass'),
            'fluids' => $request->input('checklist.fluids', 'pass'),
            'suspension' => $request->input('checklist.suspension', 'pass'),
            'diagnostics_codes' => $request->input('checklist.diagnostics_codes', ''),
        ];

        $repair->update([
            'technician_notes' => $request->input('technician_notes'),
            'inspection_checklist' => $checklist,
        ]);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => Auth::id(),
            'status' => $repair->status,
            'action_title' => 'บันทึกผลการตรวจเช็คสภาพรถ 111 จุด',
            'comment' => 'ช่าง ' . Auth::user()->name . ' อัปเดตรายการตรวจเช็คและหมายเหตุทางเทคนิค',
        ]);

        return back()->with('success', 'บันทึกผลการตรวจสภาพเรียบร้อยแล้ว');
    }

    // เพิ่มรายการอะไหล่หรือค่าแรงในการประเมินราคา
    public function addItem(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|in:part,labor,fluid,other',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'part_code' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:255',
        ]);

        $subtotal = $request->quantity * $request->unit_price;

        RepairItem::create([
            'repair_id' => $repair->id,
            'service_catalog_id' => $request->service_catalog_id ?: null,
            'item_type' => $request->item_type,
            'part_code' => $request->part_code,
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'subtotal' => $subtotal,
            'note' => $request->note,
        ]);

        // คำนวณยอดรวมใหม่
        $repair->recalculateTotals();

        return back()->with('success', 'เพิ่มรายการประเมินราคา "' . $request->item_name . '" เรียบร้อยแล้ว');
    }

    // ลบรายการประเมินราคา
    public function deleteItem($id, $itemId)
    {
        $repair = Repair::findOrFail($id);
        $item = RepairItem::where('repair_id', $repair->id)->where('id', $itemId)->firstOrFail();
        
        $name = $item->item_name;
        $item->delete();

        $repair->recalculateTotals();

        return back()->with('info', 'ลบรายการ "' . $name . '" เรียบร้อยแล้ว');
    }

    // อัปเดตส่วนลดใบประเมินราคา
    public function updateDiscount(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $discount = max(0, floatval($request->input('discount', 0)));

        $repair->update(['discount' => $discount]);
        $repair->recalculateTotals();

        return back()->with('success', 'ปรับปรุงส่วนลดเรียบร้อย');
    }

    // ช่างอัปโหลดรูปภาพระหว่างการตรวจหรือการซ่อม
    public function uploadPhoto(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:8192',
            'image_type' => 'required|in:inspection,completed,symptom',
            'caption' => 'nullable|string|max:255',
        ], [
            'image.required' => 'กรุณาเลือกไฟล์ภาพ',
            'image.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('repairs', $filename, 'public');

        RepairImage::create([
            'repair_id' => $repair->id,
            'uploaded_by' => Auth::id(),
            'image_type' => $request->image_type,
            'image_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'caption' => $request->caption ?: 'ภาพถ่ายบันทึกการทำงานโดยช่าง ' . Auth::user()->name,
        ]);

        return back()->with('success', 'อัปโหลดภาพเรียบร้อยแล้ว');
    }
}
