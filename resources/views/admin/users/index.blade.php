@extends('layouts.app')

@section('title', 'จัดการผู้ใช้งานในระบบ | Raphiphat001')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger">User Management</span>
                <h3 class="fw-bold mb-0">จัดการผู้ใช้งานในระบบ (Users & Staff)</h3>
            </div>
            <p class="text-muted small mb-0">แอดมิน, ช่างเทคนิค (เช่น Thanasak 017), และบัญชีลูกค้า</p>
        </div>
        <button type="button" class="btn btn-porsche btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-user-plus me-1"></i> เพิ่มผู้ใช้งาน / ช่างใหม่
        </button>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">ลำดับ</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>อีเมล</th>
                        <th>บทบาท (Role)</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>ความเชี่ยวชาญ (สำหรับช่าง)</th>
                        <th>วันที่ลงทะเบียน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $idx => $u)
                        <tr>
                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <strong class="text-dark">{{ $u->name }}</strong>
                                @if($u->name === 'Raphiphat001')
                                    <span class="badge bg-danger ms-1">Project Admin</span>
                                @elseif($u->name === 'Thanasak 017')
                                    <span class="badge bg-warning text-dark ms-1">Lead Tech</span>
                                @endif
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->isAdmin())
                                    <span class="badge bg-danger"><i class="fa-solid fa-user-shield me-1"></i> ผู้ดูแลระบบ (Admin)</span>
                                @elseif($u->isTechnician())
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench me-1"></i> ช่างเทคนิค (Technician)</span>
                                @else
                                    <span class="badge bg-primary"><i class="fa-solid fa-user me-1"></i> ลูกค้า (Customer)</span>
                                @endif
                            </td>
                            <td>{{ $u->phone ?: '-' }}</td>
                            <td>{{ $u->specialty ?: '-' }}</td>
                            <td class="text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal เพิ่มผู้ใช้งานใหม่ -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-user-plus text-danger me-2"></i>เพิ่มบัญชีผู้ใช้ใหม่</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="เช่น นายธีรภัทร 025" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">อีเมล <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="user@porsche-service.th" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">รหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="อย่างน้อย 6 ตัวอักษร" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">บทบาทในระบบ (Role) <span class="text-danger">*</span></label>
                        <select name="role" class="form-select form-select-sm" required>
                            <option value="technician" selected>ช่างเทคนิค (Technician)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                            <option value="customer">ลูกค้า (Customer)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="08x-xxx-xxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ความเชี่ยวชาญเฉพาะทาง (สำหรับช่าง)</label>
                        <input type="text" name="specialty" class="form-control form-control-sm" placeholder="เช่น ผู้เชี่ยวชาญระบบเบรกคาร์บอน PCCB และเกียร์ PDK">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-porsche btn-sm">บันทึกข้อมูลผู้ใช้</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
