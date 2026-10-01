<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ระบบแจ้งซ่อมและศูนย์บริการรถยนต์ปอร์เช่ | Porsche Service Care')</title>

    <!-- Google Fonts: Kanit & Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @stack('styles')
</head>
<body>

    <!-- Demo Role Switcher Bar (สำหรับอาจารย์/ผู้ตรวจงาน สลับบทบาทได้ทันทีใน 1 คลิก) -->
    <div class="demo-role-bar py-1 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fa-solid fa-graduation-cap text-warning me-1"></i>
                <span class="text-white-50">โครงงานนักศึกษา:</span>
                <span class="text-light fw-medium">Porsche Service Management System</span>
                <span class="badge bg-danger ms-2" style="font-size: 0.68rem;">Laravel 11 + MySQL</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white-50 small me-1"><i class="fa-solid fa-bolt text-warning me-1"></i>สลับผู้ใช้ทดสอบ:</span>
                <a href="{{ route('demo.login', 'admin') }}" class="badge bg-danger text-white text-decoration-none">
                    <i class="fa-solid fa-user-shield me-1"></i> แอดมิน: Raphiphat001
                </a>
                <a href="{{ route('demo.login', 'technician') }}" class="badge bg-warning text-dark text-decoration-none">
                    <i class="fa-solid fa-wrench me-1"></i> ช่าง: Thanasak 017
                </a>
                <a href="{{ route('demo.login', 'customer') }}" class="badge bg-primary text-white text-decoration-none">
                    <i class="fa-solid fa-user me-1"></i> ลูกค้า: Sompong
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-porsche sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="me-2 text-center" style="width: 38px; height: 38px; background: #d5001c; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-car-side text-white fs-5"></i>
                </div>
                <div>
                    <span class="brand-logo">PORSCHE</span>
                    <span class="brand-sub">SERVICE CARE & REPAIR SYSTEM</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-house me-1"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('track') ? 'active' : '' }}" href="{{ route('track') }}">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> เช็คสถานะซ่อม
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('customer.repairs.index') ? 'active' : '' }}" href="{{ route('customer.repairs.index') }}">
                                    <i class="fa-solid fa-clipboard-list me-1"></i> รายการแจ้งซ่อมของฉัน
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-bold {{ request()->routeIs('customer.repairs.create') ? 'active' : '' }}" href="{{ route('customer.repairs.create') }}">
                                    <i class="fa-solid fa-plus-circle me-1"></i> แจ้งซ่อมรถใหม่
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isTechnician())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('technician.repairs.*') ? 'active' : '' }}" href="{{ route('technician.repairs.index') }}">
                                    <i class="fa-solid fa-screwdriver-wrench me-1"></i> คิวงานซ่อมของช่าง
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-chart-line me-1"></i> แดชบอร์ด
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.repairs.*') ? 'active' : '' }}" href="{{ route('admin.repairs.index') }}">
                                    <i class="fa-solid fa-list-check me-1"></i> ใบแจ้งซ่อมทั้งหมด
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.catalogs.*') ? 'active' : '' }}" href="{{ route('admin.catalogs.index') }}">
                                    <i class="fa-solid fa-boxes-stacked me-1"></i> อะไหล่ & ค่าบริการ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="fa-solid fa-users me-1"></i> จัดการผู้ใช้
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- User Profile & Auth Buttons -->
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-dark border border-secondary dropdown-toggle d-flex align-items-center py-1 px-3" type="button" data-bs-toggle="dropdown">
                                <div class="text-start me-2">
                                    <div class="small fw-bold text-white">{{ auth()->user()->name }}</div>
                                    <div class="text-white-50" style="font-size: 0.72rem;">
                                        @if(auth()->user()->isAdmin())
                                            <span class="text-danger"><i class="fa-solid fa-shield"></i> ผู้ดูแลระบบ (Admin)</span>
                                        @elseif(auth()->user()->isTechnician())
                                            <span class="text-warning"><i class="fa-solid fa-wrench"></i> ช่างเทคนิค (Mechanic)</span>
                                        @else
                                            <span class="text-info"><i class="fa-solid fa-user"></i> ลูกค้า (Customer)</span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li class="dropdown-header text-muted small">สลับบทบาททดสอบด่วน:</li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('demo.login', 'admin') }}">
                                        <i class="fa-solid fa-user-shield text-danger me-2"></i> แอดมิน: Raphiphat001
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('demo.login', 'technician') }}">
                                        <i class="fa-solid fa-wrench text-warning me-2"></i> ช่าง: Thanasak 017
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('demo.login', 'customer') }}">
                                        <i class="fa-solid fa-user text-primary me-2"></i> ลูกค้า: Sompong
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> ออกจากระบบ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> เข้าสู่ระบบ
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-porsche btn-sm px-3">
                            <i class="fa-solid fa-user-plus me-1"></i> สมัครสมาชิก
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Alerts -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-exclamation fs-4 me-3 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-warning"></i>
                <div class="flex-grow-1">{{ session('warning') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
                <div class="flex-grow-1">{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-xmark me-2"></i>พบข้อผิดพลาด กรุณาตรวจสอบข้อมูล:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1 pb-5">
        @yield('content')
    </main>

    <!-- Student Project Footer -->
    <footer class="footer-porsche">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2 text-center" style="width: 32px; height: 32px; background: #d5001c; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-car-side text-white fs-6"></i>
                        </div>
                        <span class="brand-logo text-white fs-5">PORSCHE SERVICE CARE</span>
                    </div>
                    <p class="text-white-50 small mb-3">
                        ระบบสารสนเทศเพื่อการบริหารจัดการงานแจ้งซ่อมและประเมินราคาศูนย์บริการรถยนต์ปอร์เช่ รองรับการทำงานแบบครบวงจรตั้งแต่การแจ้งซ่อม ตรวจสภาพโดยช่าง เสนอราคาอะไหล่แท้ และส่งมอบรถ
                    </p>
                    <div class="student-credit-badge">
                        <div class="text-warning small fw-bold mb-1">
                            <i class="fa-solid fa-award me-1"></i> โครงงานพัฒนาซอฟต์แวร์ระดับปริญญาตรี (Senior Project)
                        </div>
                        <div class="text-light small">
                            <strong>ผู้พัฒนา:</strong> นายรภิภัทร [Raphiphat001] & นายธนศักดิ์ [Thanasak 017]
                        </div>
                        <div class="text-white-50" style="font-size: 0.76rem;">
                            สาขาวิชาวิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ มหาวิทยาลัยราชภัฏนครปฐม (NPRU)
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3"><i class="fa-solid fa-wrench text-danger me-2"></i>ศูนย์บริการที่รองรับ</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>ระบบเบรก PCCB คาร์บอนเซรามิก</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>แบตเตอรี่แรงดันสูง Taycan EV 800V</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>ระบบเกียร์คลัตช์คู่ PDK 7/8 จังหวะ</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>ตรวจเช็คสภาพ 111 จุดด้วย PIWIS III</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>ช่วงล่างถุงลม PASM & Active Ride</li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white fw-bold mb-3"><i class="fa-solid fa-laptop-code text-warning me-2"></i>ข้อมูลระบบและสิทธิ์การใช้งาน</h6>
                    <div class="bg-dark p-3 rounded border border-secondary mb-2 small text-white-50">
                        <div class="d-flex justify-content-between mb-1">
                            <span><strong class="text-white">Admin (แอดมิน):</strong></span>
                            <span class="text-danger fw-bold">Raphiphat001</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><strong class="text-white">Technician (ช่าง):</strong></span>
                            <span class="text-warning fw-bold">Thanasak 017</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong class="text-white">Customer (ลูกค้า):</strong></span>
                            <span class="text-info fw-bold">Sompong</span>
                        </div>
                    </div>
                    <div class="small text-white-50">
                        รหัสผ่านสำหรับทดสอบทุกบัญชี: <code class="text-warning">password123</code>
                    </div>
                </div>
            </div>

            <hr class="border-secondary opacity-25">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-white-50 small">
                <div>
                    &copy; 2026 Porsche Service Care System. พัฒนาด้วย Laravel 11 & MySQL Database
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge bg-secondary me-2">Database: porsche_repair_db</span>
                    <span class="badge bg-dark border border-secondary">Academic Project</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js (for Dashboard) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/custom.js') }}"></script>

    @stack('scripts')
</body>
</html>
