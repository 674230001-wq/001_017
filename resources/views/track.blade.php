@extends('layouts.app')

@section('title', 'ตรวจสอบสถานะงานแจ้งซ่อม | Porsche Service Care')

@section('content')
<div class="container py-4">

    <!-- Search Box Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 text-center bg-dark text-white rounded">
            <h3 class="fw-bold mb-2"><i class="fa-solid fa-magnifying-glass-location text-danger me-2"></i>ระบบติดตามสถานะงานแจ้งซ่อม</h3>
            <p class="text-white-50 small mb-3">กรอกหมายเลขใบแจ้งซ่อม (เช่น <span class="text-warning">POR-2026-0001</span>) หรือเลขทะเบียนรถ (เช่น <span class="text-warning">9กก 9911</span>)</p>

            <form action="{{ route('track') }}" method="GET" class="row justify-content-center g-2">
                <div class="col-md-6 col-sm-8">
                    <input type="text" name="search" class="form-control form-control-lg text-center" value="{{ $query }}" placeholder="เช่น POR-2026-0001 หรือ 9กก 9911" required>
                </div>
                <div class="col-md-auto col-sm-4">
                    <button type="submit" class="btn btn-porsche btn-lg px-4 w-100">
                        <i class="fa-solid fa-search me-1"></i> ค้นหา
                    </button>
                </div>
            </form>

            <!-- Quick sample search buttons -->
            <div class="mt-3 small text-white-50">
                ตัวอย่างคลิกเพื่อค้นหา: 
                <a href="{{ route('track', ['search' => 'POR-2026-0001']) }}" class="badge bg-secondary text-decoration-none text-white me-1">POR-2026-0001 (911 GT3 RS)</a>
                <a href="{{ route('track', ['search' => 'POR-2026-0002']) }}" class="badge bg-secondary text-decoration-none text-white me-1">POR-2026-0002 (Taycan Turbo S)</a>
                <a href="{{ route('track', ['search' => 'POR-2026-0003']) }}" class="badge bg-secondary text-decoration-none text-white">POR-2026-0003 (Cayenne เสร็จแล้ว)</a>
            </div>
        </div>
    </div>

    @if($query && !$repair)
        <div class="alert alert-warning text-center py-4 shadow-sm border-0">
            <i class="fa-solid fa-triangle-exclamation text-warning fs-1 mb-3 d-block"></i>
            <h4>ไม่พบข้อมูลการแจ้งซ่อมสำหรับคำค้นหา: "{{ $query }}"</h4>
            <p class="text-muted small mb-0">กรุณาตรวจสอบหมายเลขใบแจ้งซ่อม หรือเลขทะเบียนรถใหม่อีกครั้ง</p>
        </div>
    @elseif($repair)
        @php
            // กำหนดสถานะความคืบหน้า 6 สเต็ป
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

        <!-- Ticket Summary Header -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <span class="badge bg-dark me-2">เลขที่ใบแจ้งซ่อม:</span>
                    <span class="fs-4 fw-bold text-danger">{{ $repair->ticket_no }}</span>
                </div>
                <div class="d-flex gap-2 align-items-center mt-2 mt-md-0">
                    <span class="badge {{ $repair->urgency_badge }}">{{ $repair->urgency_text }}</span>
                    <span class="badge {{ $repair->status_badge }} fs-6">{{ $repair->status_text }}</span>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Visual Stepper Progress Bar -->
                <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-list-ol text-danger me-2"></i>ความคืบหน้าการดำเนินงาน (Repair Workflow)</h6>
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

                <!-- Info Grid -->
                <div class="row g-4 mt-2">
                    <!-- Vehicle Info -->
                    <div class="col-md-6">
                        <div class="card h-100 bg-light border-0 p-3">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-car text-danger me-2"></i>ข้อมูลรถยนต์ Porsche
                            </h6>
                            <div class="row g-2 small">
                                <div class="col-5 text-muted">รุ่นรถยนต์:</div>
                                <div class="col-7 fw-bold text-dark">{{ $repair->porscheModel?->model_name ?? $repair->model_custom_name }}</div>

                                <div class="col-5 text-muted">หมายเลขทะเบียน:</div>
                                <div class="col-7"><span class="badge bg-dark">{{ $repair->license_plate }} {{ $repair->province }}</span></div>

                                <div class="col-5 text-muted">เลขตัวถัง (VIN):</div>
                                <div class="col-7 text-monospace">{{ $repair->vin_number ?: '-' }}</div>

                                <div class="col-5 text-muted">ปี / สีตัวถัง:</div>
                                <div class="col-7">{{ $repair->car_year }} / {{ $repair->color }}</div>

                                <div class="col-5 text-muted">เลขไมล์ปัจจุบัน:</div>
                                <div class="col-7 fw-bold">{{ number_format($repair->mileage) }} กิโลเมตร</div>

                                <div class="col-5 text-muted">ประเภทบริการ:</div>
                                <div class="col-7 text-danger fw-medium">{{ $repair->service_category }}</div>

                                <div class="col-5 text-muted">การรับประกัน:</div>
                                <div class="col-7">{{ $repair->warranty_text }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Technician & Service Info -->
                    <div class="col-md-6">
                        <div class="card h-100 bg-light border-0 p-3">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-user-wrench text-warning me-2"></i>ช่างผู้รับผิดชอบ & นัดหมาย
                            </h6>
                            @if($repair->technician)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="p-2 bg-warning bg-opacity-25 rounded-circle me-3">
                                        <i class="fa-solid fa-wrench text-dark fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-6 text-dark">{{ $repair->technician->name }}</div>
                                        <div class="small text-muted">{{ $repair->technician->specialty }}</div>
                                        <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $repair->technician->phone ?: '089-777-0017' }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-secondary py-2 small mb-3">
                                    <i class="fa-solid fa-info-circle me-1"></i> อยู่ระหว่างรอแอดมิน (Raphiphat001) มอบหมายช่างเทคนิค
                                </div>
                            @endif

                            <div class="small text-muted mb-1">อาการที่ลูกค้าแจ้งไว้:</div>
                            <div class="bg-white p-2 rounded border small mb-2">
                                <strong>{{ $repair->symptom_title }}</strong>
                                <p class="mb-0 text-muted mt-1">{{ $repair->symptom_detail ?: 'ไม่มีรายละเอียดเพิ่มเติม' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technician Notes & Inspection Checklist -->
                @if($repair->technician_notes || $repair->inspection_checklist)
                    <div class="mt-4">
                        <div class="card border-0 bg-light p-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fa-solid fa-clipboard-check text-primary me-2"></i>บันทึกการตรวจเช็คสภาพโดยช่าง (Technician Inspection)
                            </h6>
                            @if($repair->technician_notes)
                                <p class="small text-dark mb-3 bg-white p-3 rounded border">
                                    {{ $repair->technician_notes }}
                                </p>
                            @endif

                            @if($repair->inspection_checklist)
                                <div class="row g-2 text-center small">
                                    <div class="col-md-2 col-4">
                                        <div class="p-2 bg-white rounded border">
                                            <div class="text-muted">ระบบเบรก</div>
                                            <span class="badge {{ ($repair->inspection_checklist['brake_system'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ($repair->inspection_checklist['brake_system'] ?? '') === 'pass' ? 'ผ่านเกณฑ์' : 'ต้องเปลี่ยน' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <div class="p-2 bg-white rounded border">
                                            <div class="text-muted">ยาง & ล้อ</div>
                                            <span class="badge {{ ($repair->inspection_checklist['tires'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ($repair->inspection_checklist['tires'] ?? '') === 'pass' ? 'ผ่านเกณฑ์' : 'ต้องเปลี่ยน' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <div class="p-2 bg-white rounded border">
                                            <div class="text-muted">แบตเตอรี่</div>
                                            <span class="badge {{ ($repair->inspection_checklist['battery'] ?? '') === 'pass' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ($repair->inspection_checklist['battery'] ?? '') === 'pass' ? 'ปกติ 12V' : 'ตรวจพบปัญหา' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <div class="p-2 bg-white rounded border">
                                            <div class="text-muted">ของเหลว</div>
                                            <span class="badge {{ ($repair->inspection_checklist['fluids'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ($repair->inspection_checklist['fluids'] ?? '') === 'pass' ? 'ปกติ' : 'ต้องเปลี่ยนถ่าย' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <div class="p-2 bg-white rounded border">
                                            <div class="text-muted">ช่วงล่าง PASM</div>
                                            <span class="badge {{ ($repair->inspection_checklist['suspension'] ?? '') === 'pass' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ($repair->inspection_checklist['suspension'] ?? '') === 'pass' ? 'ปกติ' : 'ต้องซ่อมแซม' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Cost Estimation / Quotation Sheet -->
                @if($repair->items->count() > 0)
                    <div class="mt-4">
                        <div class="card border border-warning shadow-sm">
                            <div class="card-header bg-warning bg-opacity-25 py-3 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fa-solid fa-file-invoice-dollar text-danger me-2"></i>ใบประเมินราคาอะไหล่และค่าแรง (Quotation Breakdown)
                                </h6>
                                <span class="badge bg-dark">Porsche Genuine Parts</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0 small">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 50px;">ลำดับ</th>
                                            <th>รายการอะไหล่ / ค่าบริการ</th>
                                            <th>รหัสอะไหล่แท้</th>
                                            <th class="text-center" style="width: 80px;">จำนวน</th>
                                            <th class="text-end" style="width: 140px;">ราคา/หน่วย (บาท)</th>
                                            <th class="text-end" style="width: 150px;">รวม (บาท)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($repair->items as $idx => $item)
                                            <tr>
                                                <td class="text-center">{{ $idx + 1 }}</td>
                                                <td>
                                                    <strong>{{ $item->item_name }}</strong>
                                                    @if($item->note)
                                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $item->note }}</div>
                                                    @endif
                                                </td>
                                                <td><code>{{ $item->part_code ?: '-' }}</code></td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-end fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end">รวมค่าอะไหล่และอุปกรณ์:</td>
                                            <td class="text-end">{{ number_format($repair->estimated_parts_total, 2) }} ฿</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end">รวมค่าแรงช่างเทคนิค:</td>
                                            <td class="text-end">{{ number_format($repair->estimated_labor_total, 2) }} ฿</td>
                                        </tr>
                                        @if($repair->discount > 0)
                                            <tr>
                                                <td colspan="5" class="text-end text-danger">ส่วนลดพิเศษ (Discount):</td>
                                                <td class="text-end text-danger">-{{ number_format($repair->discount, 2) }} ฿</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="5" class="text-end">ภาษีมูลค่าเพิ่ม (VAT 7%):</td>
                                            <td class="text-end">{{ number_format($repair->vat_amount, 2) }} ฿</td>
                                        </tr>
                                        <tr class="table-warning fs-6">
                                            <td colspan="5" class="text-end fw-bold">ยอดประเมินราคารวมทั้งสิ้น (Net Total):</td>
                                            <td class="text-end fw-bold text-danger">{{ number_format($repair->net_total, 2) }} ฿</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Attached Photos (ลูกค้า & ช่าง) -->
                @if($repair->images->count() > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-camera text-danger me-2"></i>รูปภาพประกอบการซ่อมและตรวจสภาพ</h6>
                        <div class="row g-3">
                            @foreach($repair->images as $img)
                                <div class="col-md-3 col-sm-6">
                                    <div class="image-preview-card shadow-sm h-100">
                                        <span class="image-preview-badge">{{ $img->type_name }}</span>
                                        <a href="{{ $img->url }}" target="_blank">
                                            <img src="{{ $img->url }}" alt="{{ $img->caption }}">
                                        </a>
                                        <div class="p-2 bg-white small">
                                            <div class="text-truncate fw-medium text-dark">{{ $img->caption ?: $img->file_name }}</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">{{ $img->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Real-time Timeline Logs -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-timeline text-secondary me-2"></i>ประวัติการดำเนินงาน (Activity Timeline)</h6>
                    <ul class="timeline-list">
                        @foreach($repair->logs as $log)
                            <li class="timeline-item">
                                <div class="timeline-icon"></div>
                                <div class="fw-bold text-dark">{{ $log->action_title }}</div>
                                <div class="small text-muted">{{ $log->created_at->format('d/m/Y H:i') }} น.</div>
                                @if($log->comment)
                                    <div class="small text-secondary mt-1 bg-light p-2 rounded">{{ $log->comment }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    @endif

</div>
@endsection
