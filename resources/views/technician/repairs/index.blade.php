@extends('layouts.app')

@section('title', 'คิวงานซ่อมของช่าง | ศูนย์บริการรถยนต์ปอร์เช่')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench me-1"></i> Technician Portal</span>
                <h3 class="fw-bold mb-0">คิวงานซ่อมของช่างเทคนิค</h3>
            </div>
            <p class="text-muted small mb-0">
                ผู้ปฏิบัติงาน: <strong>นายธนศักดิ์ (Thanasak 017)</strong> | Porsche Certified Master Technician
            </p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">งานในความรับผิดชอบ</div>
                        <div class="fs-3 fw-bold text-dark">{{ $stats['assigned'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-primary">
                        <i class="fa-solid fa-clipboard-list fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">กำลังตรวจเช็คสภาพ</div>
                        <div class="fs-3 fw-bold text-info">{{ $stats['inspecting'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-info">
                        <i class="fa-solid fa-magnifying-glass fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">กำลังลงมือซ่อม</div>
                        <div class="fs-3 fw-bold text-warning">{{ $stats['in_progress'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-warning">
                        <i class="fa-solid fa-screwdriver-wrench fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">ซ่อมเสร็จสิ้นแล้ว</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['completed'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-success">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-3 gap-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'my_jobs' ? 'active bg-warning text-dark fw-bold' : 'bg-white border text-dark' }}" href="{{ route('technician.repairs.index', ['tab' => 'my_jobs']) }}">
                <i class="fa-solid fa-briefcase me-1"></i> งานที่ได้รับมอบหมายของฉัน ({{ $stats['assigned'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'available_jobs' ? 'active bg-warning text-dark fw-bold' : 'bg-white border text-dark' }}" href="{{ route('technician.repairs.index', ['tab' => 'available_jobs']) }}">
                <i class="fa-solid fa-inbox me-1"></i> งานใหม่ที่รอมอบหมาย / ช่างว่าง
            </a>
        </li>
    </ul>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('technician.repairs.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="ค้นหาเลขที่ตั๋ว, ทะเบียน, หรืออาการ..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- สถานะทั้งหมด --</option>
                        <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>ช่างรับเรื่องแล้ว</option>
                        <option value="inspecting" {{ $status === 'inspecting' ? 'selected' : '' }}>กำลังตรวจเช็คสภาพ</option>
                        <option value="estimated" {{ $status === 'estimated' ? 'selected' : '' }}>ประเมินราคาแล้ว (รอลูกค้าอนุมัติ)</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>ลูกค้าอนุมัติแล้ว (พร้อมซ่อม)</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการซ่อม</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>ซ่อมเสร็จสิ้น</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="{{ route('technician.repairs.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary btn-sm">ล้างการค้นหา</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">เลขที่ใบแจ้งซ่อม</th>
                        <th>รุ่นรถยนต์</th>
                        <th>ทะเบียน</th>
                        <th>อาการที่แจ้ง</th>
                        <th>ความเร่งด่วน</th>
                        <th>สถานะ</th>
                        <th>ประเมินราคา</th>
                        <th class="text-end pe-3">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $r)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('technician.repairs.show', $r->id) }}" class="fw-bold text-danger text-decoration-none">
                                    {{ $r->ticket_no }}
                                </a>
                                <div class="text-muted" style="font-size: 0.72rem;">นัด: {{ $r->appointment_date ? $r->appointment_date->format('d/m/Y') : '-' }} {{ $r->appointment_time }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $r->porscheModel?->model_name ?? $r->model_custom_name }}</div>
                                <div class="small text-muted">{{ $r->color }} (ไมล์ {{ number_format($r->mileage) }} km)</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $r->license_plate }}</span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 230px;">{{ $r->symptom_title }}</div>
                                <div class="small text-muted">{{ $r->service_category }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $r->urgency_badge }}">{{ $r->urgency_text }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $r->status_badge }}">{{ $r->status_text }}</span>
                                @if($r->status === 'approved')
                                    <div class="badge bg-success mt-1 d-block"><i class="fa-solid fa-check me-1"></i>ลูกค้าอนุมัติแล้ว</div>
                                @endif
                            </td>
                            <td>
                                @if($r->net_total > 0)
                                    <strong class="text-dark">{{ number_format($r->net_total, 2) }} ฿</strong>
                                    <div class="small text-muted">อะไหล่ {{ $r->items->count() }} รายการ</div>
                                @else
                                    <span class="text-muted small">ยังไม่ได้ประเมิน</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                @if(!$r->technician_id)
                                    <form action="{{ route('technician.repairs.claim', $r->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm fw-bold">
                                            <i class="fa-solid fa-hand me-1"></i> รับงานนี้
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('technician.repairs.show', $r->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fa-solid fa-wrench me-1"></i> ห้องปฏิบัติการซ่อม &rarr;
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-wrench fs-1 d-block mb-2 text-secondary"></i>
                                <h5>ไม่พบรายการงานซ่อมในหมวดนี้</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($repairs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $repairs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
