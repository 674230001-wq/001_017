@extends('layouts.app')

@section('title', 'สมัครสมาชิกลูกค้า | ศูนย์บริการรถยนต์ปอร์เช่')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header card-header-porsche py-3 text-center">
                    <div class="fs-4 fw-bold"><i class="fa-solid fa-user-plus me-2"></i>ลงทะเบียนลูกค้ารายใหม่</div>
                    <div class="small text-white-50">สร้างบัญชีเพื่อแจ้งซ่อมและติดตามสถานะงานบริการ Porsche ของคุณ</div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-medium"><i class="fa-solid fa-user me-1 text-muted"></i> ชื่อ-นามสกุล (Full Name) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="เช่น คุณสมศักดิ์ ขับปอร์เช่" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium"><i class="fa-solid fa-envelope me-1 text-muted"></i> อีเมล (Email) <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="your-email@gmail.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium"><i class="fa-solid fa-phone me-1 text-muted"></i> เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08x-xxx-xxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium"><i class="fa-solid fa-key me-1 text-muted"></i> รหัสผ่าน (Password) <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="อย่างน้อย 6 ตัวอักษร" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium"><i class="fa-solid fa-lock me-1 text-muted"></i> ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="พิมพ์รหัสผ่านซ้ำอีกครั้ง" required>
                            </div>
                        </div>

                        <div class="d-grid mt-2 mb-3">
                            <button type="submit" class="btn btn-porsche py-2">
                                <i class="fa-solid fa-user-check me-2"></i>ยืนยันการลงทะเบียน
                            </button>
                        </div>
                    </form>

                    <div class="text-center small text-muted">
                        มีบัญชีผู้ใช้อยู่แล้ว? 
                        <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">เข้าสู่ระบบที่นี่</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
