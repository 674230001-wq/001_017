@extends('layouts.app')

@section('title', 'จัดการใบแจ้งซ่อมทั้งหมด | Raphiphat001')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger">Admin Management</span>
                <h3 class="fw-bold mb-0">รายการใบแจ้งซ่อมทั้งหมดในระบบ</h3>
            </div>
            <p class="text-muted small mb-0">ควบคุม มอบหมายช่าง และติดตามสถานะงานบริการศูนย์ปอร์เช่</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-chart-line me-1"></i> ดูแดชบอร์ดสรุปยอด
            </a>
        </div>
    </div>

    <!-- Filter & Search Box -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.repairs.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="ค้นหาเลขตั๋ว, ทะเบียน, ลูกค้า..." value="{{ $search }}">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- กรองสถานะทั้งหมด --</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>มอบหมายช่างแล้ว</option>
                        <option value="inspecting" {{ $status === 'inspecting' ? 'selected' : '' }}>กำลังตรวจเช็คสภาพ</option>
                        <option value="estimated" {{ $status === 'estimated' ? 'selected' : '' }}>ประเมินราคาแล้ว (รอลูกค้าอนุมัติ)</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>ลูกค้าอนุมัติการซ่อม</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการซ่อม</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>ซ่อมเสร็จสิ้น</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <select name="technician_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- ช่างทุกคน --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $technicianId == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6 text-end">
                    <a href="{{ route('admin.repairs.index') }}" class="btn btn-outline-secondary btn-sm w-100">ล้างตัวกรอง</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Repairs Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">เลขที่ใบแจ้งซ่อม</th>
                        <th>ลูกค้า / ผู้แจ้ง</th>
                        <th>รุ่นรถยนต์</th>
                        <th>ทะเบียน</th>
                        <th>ความเร่งด่วน</th>
                        <th>สถานะ</th>
                        <th>ช่างผู้รับผิดชอบ</th>
                        <th class="text-end">ยอดประเมิน</th>
                        <th class="text-end pe-3">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $r)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('technician.repairs.show', $r->id) }}" class="fw-bold text-danger text-decoration-none">
                                    {{ $r->ticket_no }}
                                </a>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $r->customer?->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->customer?->phone ?: '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $r->porscheModel?->model_name ?? $r->model_custom_name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->color }} ({{ $r->car_year }})</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $r->license_plate }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $r->urgency_badge }}">{{ $r->urgency_text }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $r->status_badge }}">{{ $r->status_text }}</span>
                            </td>
                            <td>
                                @if($r->technician)
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench me-1"></i>{{ $r->technician->name }}</span>
                                @else
                                    <button class="btn btn-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#assignModal{{ $r->id }}">
                                        + มอบหมายช่าง
                                    </button>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($r->net_total > 0)
                                    <strong class="text-dark">{{ number_format($r->net_total, 2) }} ฿</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('technician.repairs.show', $r->id) }}" class="btn btn-outline-dark" title="ดูรายละเอียดและปฏิบัติการ">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#assignModal{{ $r->id }}" title="เปลี่ยนช่างผู้รับผิดชอบ">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </button>
                                    <a href="{{ route('admin.repairs.print', $r->id) }}" target="_blank" class="btn btn-outline-secondary" title="พิมพ์ใบสั่งซ่อม / ใบเสนอราคา">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <form action="{{ route('admin.repairs.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบใบแจ้งซ่อมหมายเลข {{ $r->ticket_no }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="ลบใบแจ้งซ่อม">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal มอบหมายช่างสำหรับแต่ละใบงาน -->
                        <div class="modal fade" id="assignModal{{ $r->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.repairs.assign', $r->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-dark text-white">
                                            <h6 class="modal-title fw-bold">
                                                <i class="fa-solid fa-user-wrench text-warning me-2"></i>มอบหมายช่าง: {{ $r->ticket_no }}
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">เลือกช่างเทคนิคผู้รับผิดชอบ: <span class="text-danger">*</span></label>
                                                <select name="technician_id" class="form-select" required>
                                                    <option value="">-- เลือกช่างเทคนิค --</option>
                                                    @foreach($technicians as $tech)
                                                        <option value="{{ $tech->id }}" {{ $r->technician_id == $tech->id ? 'selected' : '' }}>
                                                            {{ $tech->name }} - {{ $tech->specialty }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">บันทึกคำสั่งการจากแอดมิน (Raphiphat001):</label>
                                                <textarea name="note" class="form-control" rows="2" placeholder="เช่น ให้ตรวจสอบระบบเบรกหน้าเป็นพิเศษ และรายงานผลก่อนเที่ยง"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn btn-porsche btn-sm">บันทึกการมอบหมาย</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                ไม่พบรายการใบแจ้งซ่อมที่ตรงกับเงื่อนไข
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
