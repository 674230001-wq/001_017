@extends('layouts.app')

@section('title', 'ศูนย์บริการและซ่อมบำรุงรถยนต์ปอร์เช่ | Porsche Service Management')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="badge bg-danger text-uppercase px-3 py-2 mb-3 tracking-wider">
                    <i class="fa-solid fa-certificate me-1"></i> Porsche Specialist Workshop
                </div>
                <h1 class="display-4 fw-bold text-white mb-3">
                    ระบบแจ้งซ่อมและดูแลรักษารถยนต์ <span class="text-danger">PORSCHE</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    บริการตรวจเช็คและซ่อมบำรุงด้วยเครื่องมือวิเคราะห์ PIWIS-3 อะไหล่แท้ Porsche Genuine Parts ประเมินราคาโปร่งใส และติดตามสถานะงานซ่อมแบบเรียลไทม์
                </p>

                <!-- Quick Tracking Search Box -->
                <div class="card p-2 bg-dark border-secondary shadow-lg mb-4" style="max-width: 580px;">
                    <form action="{{ route('track') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-white-50">
                                <i class="fa-solid fa-magnifying-glass text-warning"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-transparent text-white border-0" placeholder="พิมพ์เลขที่ใบแจ้งซ่อม (เช่น POR-2026-0001) หรือเลขทะเบียน..." required>
                        </div>
                        <button type="submit" class="btn btn-porsche px-4">
                            <i class="fa-solid fa-location-arrow me-1"></i> ตรวจสถานะ
                        </button>
                    </form>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('customer.repairs.create') }}" class="btn btn-porsche py-2 px-4">
                        <i class="fa-solid fa-plus-circle me-2"></i>ส่งคำขอแจ้งซ่อมรถใหม่
                    </a>
                    <a href="{{ route('track') }}" class="btn btn-outline-light py-2 px-4">
                        <i class="fa-solid fa-search me-2"></i>เช็คสถานะงานซ่อม
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <!-- Live Stats Box -->
                <div class="hero-stats-box shadow">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-50">
                        <div class="fw-bold text-white fs-5">
                            <i class="fa-solid fa-gauge-high text-danger me-2"></i>สรุปสถานะศูนย์บริการ
                        </div>
                        <span class="badge bg-success"><i class="fa-solid fa-circle-dot fa-fade me-1"></i> Live System</span>
                    </div>

                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="p-3 rounded bg-black bg-opacity-40 border border-secondary border-opacity-25">
                                <div class="fs-3 fw-bold text-white">{{ $totalRepairs }}</div>
                                <div class="text-white-50 small">งานแจ้งทั้งหมด</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-black bg-opacity-40 border border-secondary border-opacity-25">
                                <div class="fs-3 fw-bold text-warning">{{ $inProgressRepairs }}</div>
                                <div class="text-white-50 small">กำลังดำเนินการ</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-black bg-opacity-40 border border-secondary border-opacity-25">
                                <div class="fs-3 fw-bold text-success">{{ $completedRepairs }}</div>
                                <div class="text-white-50 small">ซ่อมเสร็จส่งมอบ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Role Quick Switch Info -->
                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-50">
                        <div class="small text-white-50 mb-2"><i class="fa-solid fa-user-gear text-warning me-1"></i> บทบาทผู้ใช้งานที่กำหนดในโครงงาน:</div>
                        <div class="d-flex flex-column gap-2 small">
                            <div class="d-flex justify-content-between align-items-center bg-black bg-opacity-30 p-2 rounded">
                                <span><i class="fa-solid fa-user-shield text-danger me-2"></i><strong>Admin:</strong> Raphiphat001</span>
                                <a href="{{ route('demo.login', 'admin') }}" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.72rem;">ทดสอบ</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center bg-black bg-opacity-30 p-2 rounded">
                                <span><i class="fa-solid fa-wrench text-warning me-2"></i><strong>ช่าง:</strong> Thanasak 017</span>
                                <a href="{{ route('demo.login', 'technician') }}" class="btn btn-outline-warning btn-sm py-0 px-2" style="font-size: 0.72rem;">ทดสอบ</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center bg-black bg-opacity-30 p-2 rounded">
                                <span><i class="fa-solid fa-user text-primary me-2"></i><strong>ลูกค้า:</strong> คุณสมพงษ์</span>
                                <a href="{{ route('demo.login', 'customer') }}" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.72rem;">ทดสอบ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3 Role Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-light text-danger border px-3 py-2 fw-medium mb-2">Multi-Role Architecture</span>
            <h2 class="fw-bold">โครงสร้างระบบรองรับ 3 บทบาทการทำงาน</h2>
            <p class="text-muted">ออกแบบตามกระบวนการทำงานจริงของศูนย์บริการรถยนต์สมรรถนะสูง</p>
        </div>

        <div class="row g-4">
            <!-- Customer Role Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                            <i class="fa-solid fa-user-check fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-2">1. ส่วนของลูกค้า (Customer)</h4>
                        <p class="text-muted small mb-3">เจ้าของรถยนต์ปอร์เช่สามารถแจ้งซ่อม แนบรูปอาการเสีย และอนุมัติใบเสนอราคา</p>
                        <ul class="list-unstyled text-start small mb-4">
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>กรอกข้อมูลรถ เลือกรุ่น ระบุอาการเสีย</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>แนบรูปภาพอาการเสียหรือไฟเตือนหน้าปัด</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>ติดตามสเต็ปการซ่อม 6 ขั้นตอน</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>กดยืนยันอนุมัติซ่อมหรือขอระงับ</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>ให้คะแนนและรีวิวงานซ่อมเมื่อเสร็จ</li>
                        </ul>
                        <a href="{{ route('demo.login', 'customer') }}" class="btn btn-outline-primary btn-sm w-100">
                            เข้าใช้งานส่วนลูกค้า &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Technician Role Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm border-top border-4 border-warning">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex p-3 rounded-circle bg-warning bg-opacity-10 text-warning mb-3">
                            <i class="fa-solid fa-screwdriver-wrench fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-2">2. ส่วนของช่าง (Thanasak 017)</h4>
                        <p class="text-muted small mb-3">ห้องปฏิบัติการสำหรับช่างผู้รับผิดชอบงานซ่อม ตรวจสภาพและออกใบประเมินราคา</p>
                        <ul class="list-unstyled text-start small mb-4">
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-warning me-2"></i>ดูคิวงานซ่อมที่ได้รับมอบหมาย</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-warning me-2"></i>บันทึกผลตรวจสภาพ 111 จุด (Checklist)</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-warning me-2"></i>ประเมินราคาอะไหล่แท้และคำนวณค่าแรง</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-warning me-2"></i>อัปโหลดรูปภาพตรวจเช็คและภาพหลังซ่อม</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-warning me-2"></i>เปลี่ยนสถานะ: ตรวจสภาพ -> กำลังซ่อม -> เสร็จ</li>
                        </ul>
                        <a href="{{ route('demo.login', 'technician') }}" class="btn btn-warning text-dark btn-sm w-100 fw-bold">
                            เข้าใช้งานส่วนช่าง (Thanasak 017) &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admin Role Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm border-top border-4 border-danger">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-3">
                            <i class="fa-solid fa-user-shield fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-2">3. ส่วนแอดมิน (Raphiphat001)</h4>
                        <p class="text-muted small mb-3">ผู้ดูแลระบบ บริหารจัดการงานซ่อมทั้งหมด มอบหมายช่าง และดูรายงานภาพรวม</p>
                        <ul class="list-unstyled text-start small mb-4">
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-danger me-2"></i>แดชบอร์ดภาพรวมและกราฟสถิติรายได้</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-danger me-2"></i>มอบหมายช่างผู้รับผิดชอบแต่ละใบงาน</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-danger me-2"></i>พิมพ์ใบสั่งซ่อม / ใบเสนอราคาทางการ (A4 Print)</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-danger me-2"></i>จัดการแคตตาล็อกอะไหล่และค่าแรงมาตรฐาน</li>
                            <li class="mb-2"><i class="fa-solid fa-circle-check text-danger me-2"></i>จัดการผู้ใช้งานและเพิ่มรายชื่อช่าง</li>
                        </ul>
                        <a href="{{ route('demo.login', 'admin') }}" class="btn btn-porsche btn-sm w-100">
                            เข้าใช้งานส่วนแอดมิน (Raphiphat001) &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Supported Porsche Models Showcase -->
<section class="py-5" style="background-color: #eef2f6;">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="badge bg-dark text-warning mb-2 px-3 py-1">Supported Models</span>
                <h3 class="fw-bold mb-1">รุ่นรถยนต์ Porsche ที่รองรับในระบบ</h3>
                <p class="text-muted mb-0">ศูนย์บริการพร้อมด้วยซอฟต์แวร์วิเคราะห์เฉพาะทางสำหรับรถยนต์ปอร์เช่ทุกซีรีส์</p>
            </div>
            <a href="{{ route('customer.repairs.create') }}" class="btn btn-outline-dark btn-sm mt-3 mt-md-0">
                <i class="fa-solid fa-calendar-check me-1"></i> จองคิวเข้าตรวจเช็ค
            </a>
        </div>

        <div class="row g-3">
            @foreach($models as $m)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-danger">{{ $m->series }} Series</span>
                            <small class="text-muted">{{ $m->year_range }}</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $m->model_name }}</h5>
                        <div class="small text-danger mb-2"><i class="fa-solid fa-gas-pump me-1"></i>{{ $m->engine_type }}</div>
                        <p class="small text-muted mb-0">{{ $m->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Recent Repairs Live Table -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="fa-solid fa-clock-rotate-left text-danger me-2"></i>รายการที่มีการแจ้งซ่อมเข้ามาล่าสุด</h3>
                <p class="text-muted small mb-0">ตัวอย่างข้อมูลรายการแจ้งซ่อมที่บันทึกอยู่ในฐานข้อมูลจริง</p>
            </div>
            <a href="{{ route('track') }}" class="btn btn-outline-danger btn-sm">
                ดูทั้งหมด / ตรวจสอบสถานะ &rarr;
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">เลขที่ใบแจ้งซ่อม</th>
                            <th>รุ่นรถยนต์</th>
                            <th>ทะเบียน</th>
                            <th>อาการที่แจ้ง</th>
                            <th>ช่างผู้รับผิดชอบ</th>
                            <th>สถานะ</th>
                            <th class="text-end pe-3">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTickets as $ticket)
                            <tr>
                                <td class="ps-3">
                                    <strong class="text-danger">{{ $ticket->ticket_no }}</strong>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $ticket->porscheModel?->model_name ?? $ticket->model_custom_name }}</span>
                                    <div class="small text-muted">{{ $ticket->color }} ({{ $ticket->car_year }})</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $ticket->license_plate }}</span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 250px;">{{ $ticket->symptom_title }}</div>
                                    <div class="small text-muted">{{ $ticket->service_category }}</div>
                                </td>
                                <td>
                                    @if($ticket->technician)
                                        <span class="text-dark small"><i class="fa-solid fa-wrench text-warning me-1"></i>{{ $ticket->technician->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">รอแอดมินมอบหมาย</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $ticket->status_badge }}">{{ $ticket->status_text }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('track', ['search' => $ticket->ticket_no]) }}" class="btn btn-outline-dark btn-sm py-1 px-2">
                                        <i class="fa-solid fa-eye me-1"></i> เช็คสถานะ
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection
