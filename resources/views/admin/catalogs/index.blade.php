@extends('layouts.app')

@section('title', 'แคตตาล็อกอะไหล่และค่าแรง | Raphiphat001')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger">Parts & Labor</span>
                <h3 class="fw-bold mb-0">แคตตาล็อกอะไหล่แท้และค่าบริการมาตรฐาน Porsche</h3>
            </div>
            <p class="text-muted small mb-0">ใช้สำหรับให้ช่างเลือกในแบบฟอร์มประเมินราคาอะไหล่แท้ศูนย์บริการ</p>
        </div>
        <button type="button" class="btn btn-porsche btn-sm" data-bs-toggle="modal" data-bs-target="#addCatalogModal">
            <i class="fa-solid fa-plus me-1"></i> เพิ่มรายการอะไหล่/บริการใหม่
        </button>
    </div>

    <!-- Catalog Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">รหัสอะไหล่</th>
                        <th>หมวดหมู่</th>
                        <th>ชื่อรายการอะไหล่ / ค่าบริการ</th>
                        <th>ราคาอะไหล่/หน่วย</th>
                        <th>ค่าแรงบริการ</th>
                        <th>หน่วยนับ</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($catalogs as $c)
                        <tr>
                            <td class="ps-3 text-monospace fw-bold text-danger">{{ $c->part_code ?: '-' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $c->category }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $c->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $c->description }}</div>
                            </td>
                            <td>{{ number_format($c->unit_price, 2) }} ฿</td>
                            <td>{{ number_format($c->labor_fee, 2) }} ฿</td>
                            <td>{{ $c->unit }}</td>
                            <td>
                                <span class="badge bg-success">ใช้งานอยู่</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($catalogs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $catalogs->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal เพิ่มรายการแคตตาล็อกใหม่ -->
<div class="modal fade" id="addCatalogModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.catalogs.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h6 class="modal-title fw-bold"><i class="fa-solid fa-boxes-stacked text-warning me-2"></i>เพิ่มรายการอะไหล่/บริการใหม่</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">หมวดหมู่งานบริการ <span class="text-danger">*</span></label>
                        <select name="category" class="form-select form-select-sm" required>
                            <option value="ระบบเบรก">ระบบเบรก PCCB / เบรกมาตรฐาน</option>
                            <option value="เช็คระยะ">ตรวจเช็คระยะและเปลี่ยนถ่ายของเหลว</option>
                            <option value="ระบบเครื่องยนต์">ระบบเครื่องยนต์ Boxer / เทอร์โบ</option>
                            <option value="ระบบเกียร์ PDK">ระบบเกียร์คลัตช์คู่ PDK</option>
                            <option value="ช่วงล่าง PASM">ช่วงล่างถุงลม PASM / แร็คพวงมาลัย</option>
                            <option value="แบตเตอรี่ EV/Hybrid">ระบบไฟฟ้าและแบตเตอรี่แรงดันสูง EV</option>
                            <option value="ระบบระบายความร้อน">ระบบระบายความร้อน / หม้อน้ำ</option>
                            <option value="ตัวถังและสี">ตัวถังและสี / ชุดแต่ง Tequipment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">รหัสอะไหล่แท้ (Part Number)</label>
                        <input type="text" name="part_code" class="form-control form-control-sm text-monospace" placeholder="เช่น P-992-ENG-05">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ชื่อรายการอะไหล่หรือบริการ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="เช่น กรองอากาศคาร์บอนแท้ Porsche 911 GT3" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">ราคาอะไหล่/หน่วย (บาท) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="unit_price" class="form-control form-control-sm" placeholder="0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">ค่าแรงติดตั้ง/บริการ (บาท) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="labor_fee" class="form-control form-control-sm" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">หน่วยนับ <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control form-control-sm" value="ชิ้น" placeholder="เช่น ชิ้น, ชุด, แกลลอน, ครั้ง" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">คำอธิบายรายละเอียด</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="ระบุรายละเอียดอะไหล่ การรับประกัน หรือรุ่นรถที่รองรับ"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-porsche btn-sm">บันทึกรายการ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
