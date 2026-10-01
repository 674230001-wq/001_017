@extends('layouts.app')

@section('title', 'รายการแจ้งซ่อมของฉัน | ศูนย์บริการรถยนต์ปอร์เช่')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-clipboard-list text-danger me-2"></i>รายการแจ้งซ่อมของฉัน</h3>
            <p class="text-muted small mb-0">ยินดีต้อนรับคุณ {{ auth()->user()->name }} | ตรวจสอบสถานะและอนุมัติใบเสนอราคา</p>
        </div>
        <div>
            <a href="{{ route('customer.repairs.create') }}" class="btn btn-porsche py-2 px-3 shadow-sm">
                <i class="fa-solid fa-plus-circle me-1"></i> แจ้งซ่อมรถใหม่
            </a>
        </div>
    </div>

    <!-- Summary Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">งานแจ้งซ่อมทั้งหมด</div>
                        <div class="fs-3 fw-bold text-dark">{{ $counts['all'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-primary">
                        <i class="fa-solid fa-list-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">รอดำเนินการ</div>
                        <div class="fs-3 fw-bold text-secondary">{{ $counts['pending'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-secondary">
                        <i class="fa-solid fa-clock fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">อยู่ระหว่างดำเนินการ</div>
                        <div class="fs-3 fw-bold text-warning">{{ $counts['active'] }}</div>
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
                        <div class="fs-3 fw-bold text-success">{{ $counts['completed'] }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-success">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('customer.repairs.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="ค้นหาเลขที่ตั๋ว, ทะเบียน, หรืออาการเสีย..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- กรองตามสถานะทั้งหมด --</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>ช่างรับเรื่องแล้ว</option>
                        <option value="inspecting" {{ $status === 'inspecting' ? 'selected' : '' }}>กำลังตรวจเช็คสภาพ</option>
                        <option value="estimated" {{ $status === 'estimated' ? 'selected' : '' }}>ประเมินราคาแล้ว (รออนุมัติ)</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>อนุมัติซ่อมแล้ว</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>กำลังซ่อม</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>ซ่อมเสร็จสิ้น</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="{{ route('customer.repairs.index') }}" class="btn btn-outline-secondary btn-sm">ล้างการค้นหา</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Repairs Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">เลขที่ใบแจ้งซ่อม</th>
                        <th>รุ่นรถยนต์</th>
                        <th>ทะเบียน</th>
                        <th>หมวดงานบริการ</th>
                        <th>ความเร่งด่วน</th>
                        <th>สถานะ</th>
                        <th>ยอดประเมินรวม</th>
                        <th class="text-end pe-3">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $r)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('customer.repairs.show', $r->id) }}" class="fw-bold text-danger text-decoration-none">
                                    {{ $r->ticket_no }}
                                </a>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $r->porscheModel?->model_name ?? $r->model_custom_name }}</div>
                                <div class="small text-muted">{{ $r->color }} ({{ $r->car_year }})</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $r->license_plate }}</span>
                            </td>
                            <td>
                                <span class="small">{{ $r->service_category }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $r->urgency_badge }}">{{ $r->urgency_text }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $r->status_badge }}">{{ $r->status_text }}</span>
                                @if($r->status === 'estimated')
                                    <div class="text-danger small fw-bold mt-1"><i class="fa-solid fa-bell fa-shake me-1"></i>รอคุณกดอนุมัติ</div>
                                @endif
                            </td>
                            <td>
                                @if($r->net_total > 0)
                                    <strong class="text-dark">{{ number_format($r->net_total, 2) }} ฿</strong>
                                @else
                                    <span class="text-muted small">รอช่างประเมิน</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('customer.repairs.show', $r->id) }}" class="btn btn-sm {{ $r->status === 'estimated' ? 'btn-danger' : 'btn-outline-dark' }}">
                                    @if($r->status === 'estimated')
                                        <i class="fa-solid fa-check-to-slot me-1"></i> อนุมัติราคา
                                    @else
                                        <i class="fa-solid fa-eye me-1"></i> ดูรายละเอียด
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-car-side fs-1 d-block mb-3 text-secondary"></i>
                                <h5>ยังไม่มีประวัติการแจ้งซ่อม</h5>
                                <p class="small">หากรถยนต์ Porsche ของคุณมีปัญหา สามารถกดปุ่มแจ้งซ่อมได้ทันที</p>
                                <a href="{{ route('customer.repairs.create') }}" class="btn btn-porsche btn-sm">
                                    <i class="fa-solid fa-plus-circle me-1"></i> แจ้งซ่อมรถใหม่
                                </a>
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
