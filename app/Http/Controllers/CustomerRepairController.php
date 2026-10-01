<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Repair;
use App\Models\RepairImage;
use App\Models\RepairLog;
use App\Models\PorscheModel;

class CustomerRepairController extends Controller
{
    // แสดงรายการแจ้งซ่อมของลูกค้าที่ล็อกอิน
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Repair::where('customer_id', $user->id)
            ->with(['porscheModel', 'technician']);

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

        // สรุปยอด
        $counts = [
            'all' => Repair::where('customer_id', $user->id)->count(),
            'pending' => Repair::where('customer_id', $user->id)->where('status', 'pending')->count(),
            'active' => Repair::where('customer_id', $user->id)->whereIn('status', ['assigned', 'inspecting', 'estimated', 'approved', 'in_progress'])->count(),
            'completed' => Repair::where('customer_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('customer.repairs.index', compact('repairs', 'counts', 'status', 'search'));
    }

    // หน้าฟอร์มส่งคำขอแจ้งซ่อมรถใหม่
    public function create()
    {
        $models = PorscheModel::orderBy('series')->orderBy('model_name')->get();
        return view('customer.repairs.create', compact('models'));
    }

    // บันทึกคำขอแจ้งซ่อมใหม่
    public function store(Request $request)
    {
        $request->validate([
            'porsche_model_id' => 'required',
            'license_plate' => 'required|string|max:50',
            'province' => 'required|string|max:100',
            'vin_number' => 'nullable|string|max:20',
            'car_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'mileage' => 'required|numeric|min:0',
            'service_category' => 'required|string',
            'urgency' => 'required|in:normal,urgent,emergency',
            'symptom_title' => 'required|string|max:255',
            'symptom_detail' => 'nullable|string',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|string',
            'warranty_type' => 'required|in:porsche_approved,insurance,self_pay',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:8192',
        ], [
            'porsche_model_id.required' => 'กรุณาเลือกรุ่นรถ Porsche',
            'license_plate.required' => 'กรุณากรอกหมายเลขทะเบียนรถ',
            'province.required' => 'กรุณาระบุจังหวัดที่จดทะเบียน',
            'car_year.required' => 'กรุณาระบุปีของรถยนต์',
            'color.required' => 'กรุณาระบุสีรถภายนอก',
            'mileage.required' => 'กรุณาระบุเลขไมล์ปัจจุบัน',
            'service_category.required' => 'กรุณาเลือกหมวดหมู่งานบริการที่ต้องการแจ้งซ่อม',
            'urgency.required' => 'กรุณาเลือกระดับความเร่งด่วน',
            'symptom_title.required' => 'กรุณาระบุอาการเบื้องต้นที่พบ',
            'appointment_date.required' => 'กรุณาเลือกวันนัดหมายเข้าศูนย์',
            'appointment_date.after_or_equal' => 'วันนัดหมายต้องเป็นวันนี้หรือวันข้างหน้า',
            'appointment_time.required' => 'กรุณาเลือกเวลานัดหมาย',
        ]);

        // รหัสตั๋วแจ้งซ่อม อัตโนมัติ เช่น POR-2026-0007
        $year = date('Y');
        $lastRepair = Repair::whereYear('created_at', $year)->latest('id')->first();
        $nextNumber = $lastRepair ? ($lastRepair->id + 1) : 1;
        $ticketNo = sprintf('POR-%s-%04d', $year, $nextNumber);

        // กรณีเลือกรุ่นอื่นๆ
        $modelCustomName = null;
        $modelId = $request->porsche_model_id;
        if ($modelId === 'other') {
            $modelId = null;
            $modelCustomName = $request->input('model_custom_name', 'Porsche รุ่นพิเศษ/คลาสสิก');
        }

        $repair = Repair::create([
            'ticket_no' => $ticketNo,
            'customer_id' => Auth::id(),
            'technician_id' => null, // รอแอดมินมอบหมาย
            'porsche_model_id' => $modelId,
            'model_custom_name' => $modelCustomName,
            'license_plate' => $request->license_plate,
            'province' => $request->province,
            'vin_number' => $request->vin_number ? strtoupper(trim($request->vin_number)) : null,
            'car_year' => $request->car_year,
            'color' => $request->color,
            'mileage' => $request->mileage,
            'service_category' => $request->service_category,
            'urgency' => $request->urgency,
            'symptom_title' => $request->symptom_title,
            'symptom_detail' => $request->symptom_detail,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'warranty_type' => $request->warranty_type,
            'status' => 'pending',
        ]);

        // บันทึกรูปภาพแนบ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('repairs', $filename, 'public');

                RepairImage::create([
                    'repair_id' => $repair->id,
                    'uploaded_by' => Auth::id(),
                    'image_type' => 'symptom',
                    'image_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'caption' => 'ภาพอาการที่ลูกค้าส่งเข้ามา',
                ]);
            }
        }

        // บันทึก Timeline Log เริ่มต้น
        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'action_title' => 'ลูกค้าส่งคำขอแจ้งซ่อมสำเร็จ',
            'comment' => 'นัดหมายนำรถเข้าตรวจเช็ควันที่ ' . $repair->appointment_date->format('d/m/Y') . ' เวลา ' . $repair->appointment_time,
        ]);

        return redirect()->route('customer.repairs.show', $repair->id)
            ->with('success', 'ส่งข้อมูลแจ้งซ่อมเรียบร้อยแล้ว! หมายเลขใบแจ้งซ่อมของคุณคือ ' . $ticketNo);
    }

    // ดูรายละเอียดใบแจ้งซ่อม
    public function show($id)
    {
        $user = Auth::user();
        $repair = Repair::with(['porscheModel', 'technician', 'items', 'images', 'logs.user'])
            ->where('id', $id)
            ->where('customer_id', $user->id)
            ->firstOrFail();

        return view('customer.repairs.show', compact('repair'));
    }

    // ลูกค้ากดยืนยันอนุมัติการซ่อม (หลังจากช่างประเมินราคา)
    public function approve(Request $request, $id)
    {
        $repair = Repair::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        if ($repair->status !== 'estimated') {
            return back()->with('error', 'สถานะปัจจุบันไม่สามารถทำการอนุมัติได้');
        }

        $repair->update([
            'status' => 'approved',
            'customer_approval_status' => 'approved',
            'customer_approval_note' => $request->input('approval_note', 'ลูกค้ายืนยันอนุมัติราคาซ่อมเรียบร้อยแล้ว'),
            'customer_approved_at' => now(),
        ]);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => Auth::id(),
            'status' => 'approved',
            'action_title' => 'ลูกค้ากดยืนยันอนุมัติการซ่อมผ่านระบบ',
            'comment' => $request->input('approval_note', 'ลูกค้ายอมรับการประเมินราคาและยืนยันให้เริ่มดำเนินการซ่อมได้ทันที'),
        ]);

        return back()->with('success', 'คุณได้ยืนยันอนุมัติการซ่อมเรียบร้อยแล้ว ศูนย์บริการจะรีบดำเนินการซ่อมทันที');
    }

    // ลูกค้าขอระงับหรือไม่อนุมัติการซ่อม
    public function reject(Request $request, $id)
    {
        $repair = Repair::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'reject_reason' => 'required|string|min:5',
        ], [
            'reject_reason.required' => 'กรุณาระบุเหตุผลการระงับหรือปฏิเสธ',
        ]);

        $repair->update([
            'status' => 'rejected',
            'customer_approval_status' => 'rejected',
            'customer_approval_note' => $request->reject_reason,
        ]);

        RepairLog::create([
            'repair_id' => $repair->id,
            'user_id' => Auth::id(),
            'status' => 'rejected',
            'action_title' => 'ลูกค้าขอระงับการซ่อม / ปฏิเสธราคา',
            'comment' => 'เหตุผล: ' . $request->reject_reason,
        ]);

        return back()->with('warning', 'บันทึกการขอระงับการซ่อมเรียบร้อยแล้ว เจ้าหน้าที่จะติดต่อกลับเพื่อประสานงาน');
    }

    // ลูกค้าให้คะแนนและรีวิวงานซ่อมเมื่อเสร็จสิ้น
    public function feedback(Request $request, $id)
    {
        $repair = Repair::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        $repair->update([
            'customer_rating' => $request->rating,
            'customer_feedback' => $request->feedback,
        ]);

        return back()->with('success', 'ขอบพระคุณสำหรับคะแนนประเมินและความคิดเห็นที่มีคุณค่า');
    }
}
