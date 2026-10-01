@extends('layouts.app')

@section('title', 'รายละเอียดใบแจ้งซ่อม ' . $repair->ticket_no . ' | Porsche Service Care')

@section('content')
<div class="container py-4">

    @php
        $statusStep = match($repair->status) {
            'pending' => 1,
            'assigned' => 2,
            'inspecting' => 3,
            'estimated' => 4,
            'approved', 'in_progress' => 5,
            'completed', 'delivered' => 6,
            default => 1,
        };
        $progressPercent = ($statusStep - 1) * 20;
    @endphp

    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-dark">ใบแจ้งซ่อม</span>
                <h3 class="fw-bold text-danger mb-0">{{ $repair->ticket_no }}</h3>
                <span class="badge {{ $repair->status_badge }} fs-6">{{ $repair->status_text }}</span>
            </div>
            <div class="text-muted small">
                วันที่ส่งเรื่อง: {{ $repair->created_at->format('d/m/Y H:i') }} น. | 
                นัดหมายนำรถเข้าศูนย์: <strong>{{ $repair->appointment_date ? $repair->appointment_date->format('d/m/Y') : '-' }} เวลา {{ $repair->appointment_time }}</strong>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.repairs.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> กลับหน้ารายการ
            </a>
            <a href="{{ route('admin.repairs.print', $repair->id) }}" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-print me-1"></i> พิมพ์เอกสาร
            </a>
        </div>
    </div>

    <!-- Stepper Progress Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-bars-progress text-danger me-2"></i>ลำดับขั้นตอนการดำเนินงาน</h6>
            <div class="stepper-wrapper">
                <div class="stepper-progress-line" style="width: calc({{ $progressPercent }}% * 0.85);"></div>
                
                <div class="step-item {{ $statusStep >= 1 ? ($statusStep == 1 ? 'active' : 'completed') : '' }}">
                    <div class="step-counter"><i class="fa-solid {{ $statusStep > 1 ? 'fa-check' : 'fa-file-lines' }}"></i></div>
                    <div class="step-name">1. รับเรื่องแจ้งซ่อม</div>
                </div>
                <div class="step-item {{ $statusStep >= 2 ? ($statusStep == 2 ? 'active' : 'completed') : '' }}">
                    <div class="step-counter"><i class="fa-solid {{ $statusStep > 2 ? 'fa-check' : 'fa-user-gear' }}"></i></div>
                    <div class="step-name">2. มอบหมายช่าง</div>
                </div>
                <div class="step-item {{ $statusStep >= 3 ? ($statusStep == 3 ? 'active' : 'completed') : '' }}">
                    <div class="step-counter"><i class="fa-solid {{ $statusStep > 3 ? 'fa-check' : 'fa-magnifying-glass' }}"></i></div>
                    <div class="step-name">3. ตรวจเช็คสภาพ</div>
                </div>
                <div class="step-item {{ $statusStep >= 4 ? ($statusStep == 4 ? 'active' : 'completed') : '' }}">
                    <div class="step-counter"><i class="fa-solid {{ $statusStep > 4 ? 'fa-check' : 'fa-calculator' }}"></i></div>
                    <div class="step-name">4. เสนอราคา</div>
                </div>
                <div class="step-item {{ $statusStep >= 5 ? ($statusStep == 5 ? 'active' : 'completed') : '' }}">
                    <div class="step-counter"><i class="fa-solid {{ $statusStep > 5 ? 'fa-check' : 'fa-wrench' }}"></i></div>
                    <div class="step-name">5. กำลังซ่อม</div>
                </div>
                <div class="step-item {{ $statusStep >= 6 ? 'completed' : '' }}">
                    <div class="step-counter"><i class="fa-solid fa-car-side"></i></div>
                    <div class="step-name">6. ซ่อมเสร็จส่งมอบ</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quotation Approval Alert Box (เมื่อสถานะเป็น estimated) -->
    @if($repair->status === 'estimated')
        <div class="card border-warning border-2 shadow-sm mb-4" style="background-color: #fffdf5;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-warning text-dark me-2 fs-6"><i class="fa-solid fa-bell fa-shake me-1"></i> รอลูกค้าอนุมัติ</span>
                            <h5 class="fw-bold text-dark mb-0">ช่างเทคนิคได้จัดทำใบประเมินราคาและรายการอะไหล่เรียบร้อยแล้ว</h5>
                        </div>
                        <p class="text-muted small mb-0">
                            ยอดประเมินราคารวมทั้งสิ้น (รวมภาษีมูลค่าเพิ่ม): <strong class="fs-4 text-danger">{{ number_format($repair->net_total, 2) }} บาท</strong><br>
                            โปรดตรวจสอบตารางรายการอะไหล่และค่าแรงด้านล่าง จากนั้นกดปุ่ม <strong>"ยืนยันอนุมัติการซ่อม"</strong> เพื่อให้ช่างเริ่มลงมือปฏิบัติงานทันที
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <button type="button" class="btn btn-success btn-lg px-4 me-2 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fa-solid fa-circle-check me-1"></i> อนุมัติการซ่อม
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            ขอระงับ / สอบถาม
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($repair->status === 'approved')
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4">
            <i class="fa-solid fa-circle-check fs-3 me-3 text-success"></i>
            <div>
                <strong class="d-block">คุณได้ยืนยันอนุมัติการซ่อมเรียบร้อยแล้ว</strong>
                <span class="small">เมื่อวันที่ {{ $repair->customer_approved_at ? $repair->customer_approved_at->format('d/m/Y H:i') : '-' }} | ช่างกำลังจัดเตรียมอะไหล่และคิวช่องซ่อม</span>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Vehicle & Symptoms Info -->
        <div class="col-lg-6">
            <!-- Vehicle Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-car text-danger me-2"></i>ข้อมูลรถยนต์และอาการที่แจ้ง</h6>
                </div>
                <div class="card-body p-3">
                    <table class="table table-sm table-borderless mb-3 small">
                        <tr>
                            <td class="text-muted" style="width: 140px;">รุ่นรถยนต์:</td>
                            <td class="fw-bold text-dark">{{ $repair->porscheModel?->model_name ?? $repair->model_custom_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">หมายเลขทะเบียน:</td>
                            <td><span class="badge bg-dark">{{ $repair->license_plate }} {{ $repair->province }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">หมายเลขตัวถัง (VIN):</td>
                            <td class="text-monospace">{{ $repair->vin_number ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ปี / สีตัวถัง:</td>
                            <td>{{ $repair->car_year }} / {{ $repair->color }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">เลขไมล์:</td>
                            <td class="fw-bold">{{ number_format($repair->mileage) }} km</td>
                        </tr>
                        <tr>
                            <td class="text-muted">หมวดหมู่งาน:</td>
                            <td class="text-danger fw-medium">{{ $repair->service_category }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ระดับความเร่งด่วน:</td>
                            <td><span class="badge {{ $repair->urgency_badge }}">{{ $repair->urgency_text }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">การรับประกัน:</td>
                            <td>{{ $repair->warranty_text }}</td>
                        </tr>
                    </table>

                    <div class="p-3 bg-light rounded border">
                        <div class="fw-bold text-dark small mb-1">อาการที่พบ (ที่ลูกค้าแจ้งไว้):</div>
                        <div class="fw-medium text-danger mb-1">{{ $repair->symptom_title }}</div>
                        <p class="small text-muted mb-0">{{ $repair->symptom_detail ?: 'ไม่มีรายละเอียดเพิ่มเติม' }}</p>
                    </div>
                </div>
            </div>

            <!-- Technician & Inspection Results Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-user-wrench text-warning me-2"></i>ช่างผู้รับผิดชอบ & ผลการตรวจเช็ค</h6>
                </div>
                <div class="card-body p-3">
                    @if($repair->technician)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded border">
                            <div class="p-3 bg-warning bg-opacity-25 rounded-circle me-3">
                                <i class="fa-solid fa-wrench text-dark fs-3"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark fs-6">{{ $repair->technician->name }}</div>
                                <div class="small text-muted">{{ $repair->technician->specialty }}</div>
                                <div class="small text-muted"><i class="fa-solid fa-phone text-success me-1"></i>{{ $repair->technician->phone ?: '089-777-0017' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary small py-2">
                            <i class="fa-solid fa-clock me-1"></i> อยู่ระหว่างรอแอดมิน (Raphiphat001) มอบหมายช่างเทคนิค
                        </div>
                    @endif

                    @if($repair->technician_notes)
                        <div class="mb-3">
                            <div class="small text-muted fw-bold mb-1">บันทึกของช่าง:</div>
                            <div class="bg-light p-3 rounded border small text-dark">
                                {{ $repair->technician_notes }}
                            </div>
                        </div>
                    @endif

                    @if($repair->inspection_checklist)
                        <div class="small text-muted fw-bold mb-2">ผลตรวจสภาพ 5 หมวดสำคัญ (Inspection Checklist):</div>
                        <div class="row g-2 text-center small">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded border">
                                    <div class="text-muted" style="font-size: 0.72rem;">ระบบเบรก</div>
                                    <span class="badge {{ ($repair->inspection_checklist['brake_system'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ($repair->inspection_checklist['brake_system'] ?? '') === 'pass' ? 'ผ่านเกณฑ์' : 'ต้องเปลี่ยน' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded border">
                                    <div class="text-muted" style="font-size: 0.72rem;">ยาง & ล้อ</div>
                                    <span class="badge {{ ($repair->inspection_checklist['tires'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ($repair->inspection_checklist['tires'] ?? '') === 'pass' ? 'ผ่านเกณฑ์' : 'ต้องเปลี่ยน' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded border">
                                    <div class="text-muted" style="font-size: 0.72rem;">แบตเตอรี่</div>
                                    <span class="badge {{ ($repair->inspection_checklist['battery'] ?? '') === 'pass' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ($repair->inspection_checklist['battery'] ?? '') === 'pass' ? 'ปกติ 12V' : 'ตรวจพบปัญหา' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded border">
                                    <div class="text-muted" style="font-size: 0.72rem;">ของเหลวหล่อลื่น</div>
                                    <span class="badge {{ ($repair->inspection_checklist['fluids'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ($repair->inspection_checklist['fluids'] ?? '') === 'pass' ? 'ปกติ' : 'ต้องเปลี่ยนถ่าย' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded border">
                                    <div class="text-muted" style="font-size: 0.72rem;">ช่วงล่าง PASM</div>
                                    <span class="badge {{ ($repair->inspection_checklist['suspension'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ($repair->inspection_checklist['suspension'] ?? '') === 'pass' ? 'ปกติ' : 'ต้องซ่อมแซม' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Quotation Table & Attached Photos -->
        <div class="col-lg-6">
            <!-- Quotation Sheet -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-file-invoice-dollar text-danger me-2"></i>ใบประเมินราคาอะไหล่ & ค่าแรง
                    </h6>
                    <span class="badge bg-danger">Porsche Genuine</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 small">
                            <thead class="table-dark">
                                <tr>
                                    <th>รายการ</th>
                                    <th class="text-center">จำนวน</th>
                                    <th class="text-end">ราคา/หน่วย</th>
                                    <th class="text-end">รวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repair->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $item->item_name }}</div>
                                            @if($item->part_code)
                                                <code class="text-muted" style="font-size: 0.72rem;">{{ $item->part_code }}</code>
                                            @endif
                                            @if($item->note)
                                                <div class="text-muted" style="font-size: 0.72rem;">{{ $item->note }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-hourglass-half me-1"></i> อยู่ระหว่างช่างตรวจสภาพและจัดทำรายการอะไหล่
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($repair->items->count() > 0)
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end">รวมค่าอะไหล่:</td>
                                        <td class="text-end">{{ number_format($repair->estimated_parts_total, 2) }} ฿</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">รวมค่าแรงช่าง:</td>
                                        <td class="text-end">{{ number_format($repair->estimated_labor_total, 2) }} ฿</td>
                                    </tr>
                                    @if($repair->discount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end text-danger">ส่วนลด (Discount):</td>
                                            <td class="text-end text-danger">-{{ number_format($repair->discount, 2) }} ฿</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end">ภาษีมูลค่าเพิ่ม (VAT 7%):</td>
                                        <td class="text-end">{{ number_format($repair->vat_amount, 2) }} ฿</td>
                                    </tr>
                                    <tr class="table-warning fs-6">
                                        <td colspan="3" class="text-end fw-bold">ยอดสุทธิรวมทั้งสิ้น:</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($repair->net_total, 2) }} ฿</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Review / Feedback Box (เมื่อซ่อมเสร็จสิ้น) -->
            @if($repair->status === 'completed')
                <div class="card border-0 shadow-sm mb-4 border-start border-4 border-success">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-star text-warning me-2"></i>ประเมินความพึงพอใจการให้บริการ</h6>
                        @if($repair->customer_rating)
                            <div class="p-3 bg-light rounded text-center">
                                <div class="text-warning fs-4 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $repair->customer_rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <div class="fw-bold text-dark">{{ $repair->customer_rating }} / 5 ดาว</div>
                                @if($repair->customer_feedback)
                                    <p class="small text-muted mt-2 mb-0">"{{ $repair->customer_feedback }}"</p>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('customer.repairs.feedback', $repair->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-medium">ให้คะแนนความพึงพอใจ (1 - 5 ดาว):</label>
                                    <select name="rating" class="form-select form-select-sm" required>
                                        <option value="5">⭐⭐⭐⭐⭐ 5 ดาว (ยอดเยี่ยมมาก)</option>
                                        <option value="4">⭐⭐⭐⭐ 4 ดาว (ดีมาก)</option>
                                        <option value="3">⭐⭐⭐ 3 ดาว (ปานกลาง)</option>
                                        <option value="2">⭐⭐ 2 ดาว (พอใช้)</option>
                                        <option value="1">⭐ 1 ดาว (ต้องปรับปรุง)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-medium">ข้อเสนอแนะเพิ่มเติม:</label>
                                    <textarea name="feedback" class="form-control form-control-sm" rows="2" placeholder="พิมพ์ความคิดเห็นของคุณเพื่อการพัฒนาบริการ..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning text-dark btn-sm w-100 fw-bold">
                                    <i class="fa-solid fa-paper-plane me-1"></i> ส่งคะแนนประเมิน
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Photos Gallery -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-camera text-danger me-2"></i>รูปภาพแนบทั้งหมด ({{ $repair->images->count() }} รูป)</h6>
                </div>
                <div class="card-body p-3">
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
                            <div class="col-12 text-center text-muted small py-3">
                                ยังไม่มีรูปภาพแนบในระบบ
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Timeline Activity Log -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-timeline text-secondary me-2"></i>ประวัติการดำเนินงาน</h6>
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

<!-- Modal อนุมัติการซ่อม -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.repairs.approve', $repair->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-circle-check me-2"></i>ยืนยันอนุมัติการซ่อม</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>คุณกำลังจะยืนยันอนุมัติราคาซ่อมสำหรับรถยนต์ <strong>{{ $repair->porscheModel?->model_name ?? $repair->model_custom_name }}</strong> ทะเบียน <strong>{{ $repair->license_plate }}</strong></p>
                    <div class="alert alert-warning py-2 small">
                        ยอดเงินสุทธิที่ต้องชำระ: <strong class="fs-5 text-danger">{{ number_format($repair->net_total, 2) }} ฿</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">หมายเหตุเพิ่มเติมถึงช่างเทคนิค (ถ้ามี):</label>
                        <textarea name="approval_note" class="form-control" rows="2" placeholder="เช่น อนุมัติให้เริ่มดำเนินการได้เลยครับ ขอให้เสร็จตามกำหนด"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check me-1"></i> ยืนยันการอนุมัติซ่อม
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal ขอระงับ / ปฏิเสธ -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.repairs.reject', $repair->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-circle-xmark me-2"></i>ขอระงับการซ่อม / สอบถามเพิ่มเติม</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">โปรดระบุเหตุผลหรือข้อสงสัยที่ต้องการสอบถาม <span class="text-danger">*</span></label>
                        <textarea name="reject_reason" class="form-control" rows="3" placeholder="เช่น ต้องการสอบถามรายละเอียดอะไหล่เพิ่มเติม หรือขอชะลอการเปลี่ยนผ้าเบรกไว้ก่อน" required></textarea>
                    </div>
                    <p class="small text-muted mb-0">เจ้าหน้าที่ศูนย์บริการจะติดต่อกลับเพื่อประสานงานและชี้แจงรายละเอียดเพิ่มเติม</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-paper-plane me-1"></i> ส่งคำขอระงับ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
