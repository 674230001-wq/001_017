@extends('layouts.app')

@section('title', 'ห้องปฏิบัติการงานซ่อม ' . $repair->ticket_no . ' | Thanasak 017')

@section('content')
<div class="container py-4">

    <!-- Top Breadcrumb & Ticket Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench me-1"></i> ห้องปฏิบัติการช่าง</span>
                <h3 class="fw-bold mb-0 text-danger">{{ $repair->ticket_no }}</h3>
                <span class="badge {{ $repair->status_badge }} fs-6">{{ $repair->status_text }}</span>
            </div>
            <div class="text-muted small">
                ช่างผู้รับผิดชอบ: <strong>{{ $repair->technician?->name ?? 'ยังไม่กำหนด' }}</strong> | 
                ลูกค้า: <strong>{{ $repair->customer?->name }}</strong> (โทร: {{ $repair->customer?->phone ?: '-' }})
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('technician.repairs.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> กลับคิวงาน
            </a>
            <a href="{{ route('admin.repairs.print', $repair->id) }}" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-print me-1"></i> พิมพ์ใบสั่งซ่อม
            </a>
        </div>
    </div>

    <!-- Quick Status Transition Toolbar (กล่องควบคุมสถานะงานซ่อมของช่าง) -->
    <div class="card border-0 shadow-sm mb-4 bg-dark text-white">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="text-white-50 small d-block mb-1"><i class="fa-solid fa-arrows-spin me-1 text-warning"></i> อัปเดตสถานะงานซ่อมปัจจุบัน:</span>
                    <strong class="fs-5 text-warning">{{ $repair->status_text }}</strong>
                </div>

                <!-- Status Action Buttons -->
                <div class="d-flex flex-wrap gap-2">
                    <!-- 1. Start Inspection -->
                    <form action="{{ route('technician.repairs.status', $repair->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="inspecting">
                        <button type="submit" class="btn btn-sm {{ $repair->status === 'inspecting' ? 'btn-primary' : 'btn-outline-primary text-white' }}">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> 1. นำรถขึ้นตรวจสภาพ
                        </button>
                    </form>

                    <!-- 2. Send Quotation to Customer -->
                    <form action="{{ route('technician.repairs.status', $repair->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="estimated">
                        <button type="submit" class="btn btn-sm {{ $repair->status === 'estimated' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning text-white' }}" {{ $repair->items->count() == 0 ? 'disabled title="กรุณาเพิ่มรายการอะไหล่ก่อนส่งประเมินราคา"' : '' }}>
                            <i class="fa-solid fa-calculator me-1"></i> 2. ส่งใบประเมินราคาให้ลูกค้า
                        </button>
                    </form>

                    <!-- 3. Start Repair Work -->
                    <form action="{{ route('technician.repairs.status', $repair->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn btn-sm {{ $repair->status === 'in_progress' ? 'btn-info text-dark fw-bold' : 'btn-outline-info text-white' }}">
                            <i class="fa-solid fa-wrench me-1"></i> 3. เริ่มลงมือซ่อม
                        </button>
                    </form>

                    <!-- 4. Complete & Test Pass -->
                    <form action="{{ route('technician.repairs.status', $repair->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-sm {{ $repair->status === 'completed' ? 'btn-success fw-bold' : 'btn-outline-success text-white' }}" onclick="return confirm('ยืนยันว่าการซ่อมเสร็จสิ้น 100% และผ่านการทดสอบระบบเรียบร้อย?')">
                            <i class="fa-solid fa-circle-check me-1"></i> 4. ซ่อมเสร็จสิ้น & ส่งมอบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div class="row g-4">
        <!-- Left: Car Details & 111-Point Inspection Checklist -->
        <div class="col-lg-6">
            <!-- Vehicle Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-car text-danger me-2"></i>ข้อมูลยานพาหนะ</h6>
                    <span class="badge bg-danger">{{ $repair->porscheModel?->series ?? 'Porsche' }}</span>
                </div>
                <div class="card-body p-3">
                    <table class="table table-sm table-borderless small mb-2">
                        <tr>
                            <td class="text-muted" style="width: 140px;">รุ่นรถยนต์:</td>
                            <td class="fw-bold text-dark">{{ $repair->porscheModel?->model_name ?? $repair->model_custom_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">หมายเลขทะเบียน:</td>
                            <td><span class="badge bg-dark">{{ $repair->license_plate }} {{ $repair->province }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">เลขตัวถัง (VIN):</td>
                            <td class="text-monospace fw-bold">{{ $repair->vin_number ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ปี / สีตัวถัง:</td>
                            <td>{{ $repair->car_year }} / {{ $repair->color }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">เลขไมล์:</td>
                            <td class="fw-bold text-danger">{{ number_format($repair->mileage) }} กิโลเมตร</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ความเร่งด่วน:</td>
                            <td><span class="badge {{ $repair->urgency_badge }}">{{ $repair->urgency_text }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">การรับประกัน:</td>
                            <td>{{ $repair->warranty_text }}</td>
                        </tr>
                    </table>

                    <div class="p-3 bg-light rounded border">
                        <div class="small fw-bold text-danger mb-1">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> อาการที่ลูกค้าแจ้ง: {{ $repair->symptom_title }}
                        </div>
                        <p class="small text-muted mb-0">{{ $repair->symptom_detail ?: 'ไม่มีรายละเอียดเพิ่มเติม' }}</p>
                    </div>
                </div>
            </div>

            <!-- Inspection Checklist Form (บันทึกผลตรวจสภาพ 5 หมวด + PIWIS) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-clipboard-check text-primary me-2"></i>บันทึกการตรวจเช็คสภาพรถ 111 จุด (Inspection Sheet)
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('technician.repairs.inspection', $repair->id) }}" method="POST">
                        @csrf

                        @php
                            $checklist = $repair->inspection_checklist ?? [];
                        @endphp

                        <div class="mb-3">
                            <label class="form-label small fw-bold">1. ระบบเบรกคาร์บอนเซรามิก PCCB / ผ้าเบรก</label>
                            <select name="checklist[brake_system]" class="form-select form-select-sm">
                                <option value="pass" {{ ($checklist['brake_system'] ?? '') === 'pass' ? 'selected' : '' }}>✅ ผ่านเกณฑ์ (ความหนาผ้าเบรกปกติ)</option>
                                <option value="pass_with_repair" {{ ($checklist['brake_system'] ?? '') === 'pass_with_repair' ? 'selected' : '' }}>⚠️ ต่ำกว่าเกณฑ์มาตรฐาน (แนะนำเปลี่ยนผ้าเบรก/จาน)</option>
                                <option value="fail" {{ ($checklist['brake_system'] ?? '') === 'fail' ? 'selected' : '' }}>❌ ไม่ผ่านเกณฑ์ (จานเบรกแตกบิ่น หรือมีรอยร้าว)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">2. ยางรถยนต์และแรงดันลม N-Spec</label>
                            <select name="checklist[tires]" class="form-select form-select-sm">
                                <option value="pass" {{ ($checklist['tires'] ?? '') === 'pass' ? 'selected' : '' }}>✅ ผ่านเกณฑ์ (ดอกยางเหลือมากกว่า 3.0 มม.)</option>
                                <option value="pass_with_repair" {{ ($checklist['tires'] ?? '') === 'pass_with_repair' ? 'selected' : '' }}>⚠️ ดอกยางใกล้หมดเกณฑ์ (เหลือน้อยกว่า 2.5 มม.)</option>
                                <option value="fail" {{ ($checklist['tires'] ?? '') === 'fail' ? 'selected' : '' }}>❌ ยางมีบาดแผล หรือบวม</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">3. แบตเตอรี่ 12V และ High-Voltage EV/Hybrid</label>
                            <select name="checklist[battery]" class="form-select form-select-sm">
                                <option value="pass" {{ ($checklist['battery'] ?? '') === 'pass' ? 'selected' : '' }}>✅ สุขภาพแบตเตอรี่สมบูรณ์ (State of Health > 90%)</option>
                                <option value="inspecting" {{ ($checklist['battery'] ?? '') === 'inspecting' ? 'selected' : '' }}>⚠️ อยู่ระหว่างวิเคราะห์เซลล์แรงดันสูง</option>
                                <option value="fail" {{ ($checklist['battery'] ?? '') === 'fail' ? 'selected' : '' }}>❌ ตรวจพบเซลล์ผิดปกติ / ความต้านทานสูง</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">4. ของเหลวหล่อลื่น (น้ำมันเครื่อง C40, เกียร์ PDK, น้ำยาหล่อเย็น)</label>
                            <select name="checklist[fluids]" class="form-select form-select-sm">
                                <option value="pass" {{ ($checklist['fluids'] ?? '') === 'pass' ? 'selected' : '' }}>✅ ระดับและสภาพของเหลวอยู่ในเกณฑ์ดี</option>
                                <option value="pass_with_repair" {{ ($checklist['fluids'] ?? '') === 'pass_with_repair' ? 'selected' : '' }}>⚠️ ถึงรอบเปลี่ยนถ่ายตามระยะทาง</option>
                                <option value="fail" {{ ($checklist['fluids'] ?? '') === 'fail' ? 'selected' : '' }}>❌ พบการรั่วซึม หรือมีคราบน้ำมันผิดปกติ</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">5. ช่วงล่างถุงลม PASM และบูชยาง</label>
                            <select name="checklist[suspension]" class="form-select form-select-sm">
                                <option value="pass" {{ ($checklist['suspension'] ?? '') === 'pass' ? 'selected' : '' }}>✅ ทำงานปกติ ไม่มีเสียงดัง ถุงลมไม่รั่ว</option>
                                <option value="pass_with_repair" {{ ($checklist['suspension'] ?? '') === 'pass_with_repair' ? 'selected' : '' }}>⚠️ โช้คเริ่มมีความชื้น หรือบูชยางสึกหรอ</option>
                                <option value="fail" {{ ($checklist['suspension'] ?? '') === 'fail' ? 'selected' : '' }}>❌ ถุงลมรั่ว ยุบตัว หรือวาล์วไฟฟ้าขัดข้อง</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">รหัสข้อผิดพลาดจากเครื่องวิเคราะห์ PIWIS-3 (DTC Fault Codes):</label>
                            <input type="text" name="checklist[diagnostics_codes]" class="form-control form-control-sm text-monospace" value="{{ $checklist['diagnostics_codes'] ?? '' }}" placeholder="เช่น Fault Code: P1489, P0B3C00 หรือ No Fault Codes">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">บันทึกความคิดเห็นเชิงเทคนิคของช่าง (Technician Notes):</label>
                            <textarea name="technician_notes" class="form-control form-control-sm" rows="3" placeholder="ระบุผลการตรวจสอบเชิงลึก เช่น ความหนาผ้าเบรกที่วัดได้, ผลการทดสอบแรงดัน หรือชิ้นส่วนที่ต้องสั่งเปลี่ยน">{{ $repair->technician_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 py-2">
                            <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกผลการตรวจเช็คสภาพรถ
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Quotation Builder & Parts Breakdown -->
        <div class="col-lg-6">
            <!-- Quotation Builder -->
            <div class="card border-0 shadow-sm mb-4 border-top border-4 border-warning">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-calculator text-warning me-2"></i>จัดการรายการประเมินราคาอะไหล่ & ค่าแรง
                    </h6>
                    <span class="badge bg-warning text-dark">Quotation Manager</span>
                </div>
                <div class="card-body p-3">

                    <!-- Form to add item -->
                    <div class="bg-light p-3 rounded border mb-3">
                        <div class="fw-bold small text-dark mb-2">
                            <i class="fa-solid fa-plus-circle text-success me-1"></i> เพิ่มรายการอะไหล่ / ค่าแรงในใบเสนอราคา
                        </div>

                        <form action="{{ route('technician.repairs.items.add', $repair->id) }}" method="POST">
                            @csrf

                            <!-- Quick Select from Porsche Catalog -->
                            <div class="mb-2">
                                <label class="form-label small mb-1">เลือกจากแคตตาล็อกอะไหล่มาตรฐาน (Porsche Catalog):</label>
                                <select id="catalogSelectPicker" class="form-select form-select-sm">
                                    <option value="">-- พิมพ์เอง หรือเลือกจากแคตตาล็อกเพื่อกรอกราคาอัตโนมัติ --</option>
                                    @foreach($catalogs as $cat)
                                        <option value="{{ $cat->id }}" 
                                                data-name="{{ $cat->name }}" 
                                                data-code="{{ $cat->part_code }}" 
                                                data-price="{{ $cat->unit_price }}" 
                                                data-type="{{ $cat->category === 'เช็คระยะ' ? 'part' : ($cat->category === 'ระบบไฟฟ้า' ? 'labor' : 'part') }}">
                                            [{{ $cat->category }}] {{ $cat->name }} ({{ number_format($cat->unit_price, 2) }} ฿)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-7">
                                    <label class="form-label small mb-1">ชื่อรายการอะไหล่/งานบริการ <span class="text-danger">*</span></label>
                                    <input type="text" name="item_name" id="item_name_input" class="form-control form-control-sm" placeholder="เช่น ชุดผ้าเบรก PCCB หน้า" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small mb-1">รหัสอะไหล่แท้ (Part Number)</label>
                                    <input type="text" name="part_code" id="part_code_input" class="form-control form-control-sm text-monospace" placeholder="P-992-BRK-01">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small mb-1">ประเภทรายการ</label>
                                    <select name="item_type" id="item_type_input" class="form-select form-select-sm">
                                        <option value="part">อะไหล่ (Part)</option>
                                        <option value="labor">ค่าแรงช่าง (Labor)</option>
                                        <option value="fluid">ของเหลว (Fluid)</option>
                                        <option value="other">อื่นๆ (Extra)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">จำนวน</label>
                                    <input type="number" name="quantity" class="form-control form-control-sm" value="1" min="1" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small mb-1">ราคาต่อหน่วย (บาท) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="unit_price" id="unit_price_input" class="form-control form-control-sm" placeholder="0.00" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">หมายเหตุเพิ่มเติม</label>
                                    <input type="text" name="note" class="form-control form-control-sm" placeholder="เช่น อะไหล่เบิกศูนย์เยอรมนี พร้อมรับประกัน 2 ปี">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning text-dark btn-sm w-100 fw-bold">
                                <i class="fa-solid fa-plus me-1"></i> เพิ่มรายการลงในใบเสนอราคา
                            </button>
                        </form>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0 small">
                            <thead class="table-dark">
                                <tr>
                                    <th>รายการ</th>
                                    <th class="text-center" style="width: 50px;">จน.</th>
                                    <th class="text-end" style="width: 100px;">ราคา/หน่วย</th>
                                    <th class="text-end" style="width: 110px;">รวม (บาท)</th>
                                    <th class="text-center" style="width: 45px;"><i class="fa-solid fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repair->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $item->item_name }}</div>
                                            @if($item->part_code)
                                                <code style="font-size: 0.72rem;">{{ $item->part_code }}</code>
                                            @endif
                                            @if($item->note)
                                                <div class="text-muted" style="font-size: 0.7rem;">{{ $item->note }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('technician.repairs.items.delete', [$repair->id, $item->id]) }}" method="POST" onsubmit="return confirm('ต้องการลบรายการนี้ใช่หรือไม่?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            ยังไม่มีรายการอะไหล่หรือค่าแรงในใบเสนอราคา
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($repair->items->count() > 0)
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end">รวมค่าอะไหล่:</td>
                                        <td class="text-end">{{ number_format($repair->estimated_parts_total, 2) }} ฿</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">รวมค่าแรง:</td>
                                        <td class="text-end">{{ number_format($repair->estimated_labor_total, 2) }} ฿</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-danger">ส่วนลด (Discount):</td>
                                        <td class="text-end text-danger">
                                            -{{ number_format($repair->discount, 2) }} ฿
                                        </td>
                                        <td>
                                            <button class="btn btn-link btn-sm p-0 text-muted" data-bs-toggle="modal" data-bs-target="#discountModal">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">ภาษีมูลค่าเพิ่ม (VAT 7%):</td>
                                        <td class="text-end">{{ number_format($repair->vat_amount, 2) }} ฿</td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-warning fs-6">
                                        <td colspan="3" class="text-end fw-bold">ยอดประเมินราคารวมทั้งสิ้น:</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($repair->net_total, 2) }} ฿</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Technician Photo Upload Area -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-camera text-danger me-2"></i>อัปโหลดรูปภาพตรวจเช็ค / ผลงานซ่อม</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('technician.repairs.photos.upload', $repair->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label small mb-1">เลือกประเภทภาพ</label>
                                <select name="image_type" class="form-select form-select-sm" required>
                                    <option value="inspection">ภาพตรวจสภาพ (Inspection Photo)</option>
                                    <option value="completed">ภาพงานซ่อมเสร็จสิ้น (Completed Photo)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1">เลือกไฟล์รูปภาพ <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small mb-1">คำอธิบายภาพ</label>
                                <input type="text" name="caption" class="form-control form-control-sm" placeholder="เช่น ตรวจสอบความหนาผ้าเบรก 2.1 มม. ด้วยไมโครมิเตอร์">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-dark btn-sm w-100">
                            <i class="fa-solid fa-upload me-1"></i> อัปโหลดรูปภาพเข้าสู่ระบบ
                        </button>
                    </form>

                    <!-- Gallery of uploaded images -->
                    <div class="row g-2">
                        @forelse($repair->images as $img)
                            <div class="col-6">
                                <div class="image-preview-card shadow-sm h-100">
                                    <span class="image-preview-badge">{{ $img->type_name }}</span>
                                    <a href="{{ $img->url }}" target="_blank">
                                        <img src="{{ $img->url }}" alt="{{ $img->caption }}">
                                    </a>
                                    <div class="p-2 bg-white small">
                                        <div class="text-truncate fw-medium text-dark">{{ $img->caption ?: $img->file_name }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted small py-2">
                                ยังไม่มีรูปภาพในระบบ
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Timeline Activity Log -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-timeline text-secondary me-2"></i>ประวัติการบันทึกงานซ่อม</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="timeline-list mb-0">
                        @foreach($repair->logs as $log)
                            <li class="timeline-item">
                                <div class="timeline-icon"></div>
                                <div class="fw-bold text-dark small">{{ $log->action_title }}</div>
                                <div class="small text-muted" style="font-size: 0.72rem;">{{ $log->created_at->format('d/m/Y H:i') }} น.</div>
                                @if($log->comment)
                                    <div class="small text-secondary mt-1 bg-light p-2 rounded" style="font-size: 0.78rem;">{{ $log->comment }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Modal ปรับส่วนลด -->
<div class="modal fade" id="discountModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{ route('technician.repairs.discount', $repair->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">กำหนดส่วนลดพิเศษ</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <label class="form-label small">ระบุจำนวนเงินส่วนลด (บาท):</label>
                    <input type="number" step="0.01" name="discount" class="form-control form-control-sm" value="{{ $repair->discount }}" min="0">
                </div>
                <div class="modal-footer p-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">บันทึกส่วนลด</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
