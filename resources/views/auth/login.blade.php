@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ | ศูนย์บริการรถยนต์ปอร์เช่')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            
            <!-- Quick Demo Login Card (แนะนำสำหรับอาจารย์/ผู้ตรวจงาน) -->
            <div class="card mb-4 border-warning shadow-sm" style="background: #fffcf0;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2 text-dark">
                        <i class="fa-solid fa-bolt-lightning text-warning fs-5 me-2"></i>
                        <span class="fw-bold">ทางลัดสำหรับทดสอบระบบ (1-Click Demo Login)</span>
                    </div>
                    <p class="small text-muted mb-2">คลิกเพื่อเข้าสู่ระบบตามบทบาทต่างๆ ได้ทันทีโดยไม่ต้องพิมพ์รหัสผ่าน:</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('demo.login', 'admin') }}" class="btn btn-outline-danger btn-sm text-start py-2 d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-user-shield me-2"></i><strong>Admin (ผู้ดูแลระบบ):</strong> Raphiphat001</span>
                            <span class="badge bg-danger">คลิกเข้าทันที &rarr;</span>
                        </a>
                        <a href="{{ route('demo.login', 'technician') }}" class="btn btn-outline-warning btn-sm text-start py-2 text-dark d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-wrench me-2"></i><strong>Technician (ช่างซ่อม):</strong> Thanasak 017</span>
                            <span class="badge bg-warning text-dark">คลิกเข้าทันที &rarr;</span>
                        </a>
                        <a href="{{ route('demo.login', 'customer') }}" class="btn btn-outline-primary btn-sm text-start py-2 d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-user me-2"></i><strong>Customer (ลูกค้า):</strong> คุณสมพงษ์</span>
                            <span class="badge bg-primary">คลิกเข้าทันที &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Standard Login Form -->
            <div class="card shadow-sm border-0">
                <div class="card-header card-header-porsche py-3 text-center">
                    <div class="fs-4 fw-bold"><i class="fa-solid fa-lock me-2"></i>เข้าสู่ระบบ</div>
                    <div class="small text-white-50">Porsche Service Care Authentication</div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-medium"><i class="fa-solid fa-envelope me-1 text-muted"></i> อีเมล (Email)</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@porsche-service.th" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium"><i class="fa-solid fa-key me-1 text-muted"></i> รหัสผ่าน (Password)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                            <label class="form-check-label small text-muted" for="rememberMe">จดจำการเข้าสู่ระบบ</label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-porsche py-2">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>เข้าสู่ระบบ
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">

                    <div class="text-center small text-muted">
                        ยังไม่มีบัญชีสำหรับส่งรถเข้าซ่อม? 
                        <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">สมัครสมาชิกลูกค้าที่นี่</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
