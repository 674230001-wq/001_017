@extends('layouts.app')

@section('title', 'ส่งคำขอแจ้งซ่อมรถใหม่ | ศูนย์บริการรถยนต์ปอร์เช่')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header card-header-porsche py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-4 fw-bold"><i class="fa-solid fa-file-circle-plus me-2"></i>แบบฟอร์มแจ้งซ่อมรถยนต์ Porsche</div>
                        <div class="small text-white-50">กรอกข้อมูลรถยนต์ อาการเสีย และนัดหมายเข้าศูนย์บริการ</div>
                    </div>
                    <a href="{{ route('customer.repairs.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> ย้อนกลับ
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('customer.repairs.store') }}" method="POST" enctype="multipart/form-data" id="repairForm">
                        @csrf

                        <!-- Section 1: ข้อมูลรถยนต์ -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <span class="badge bg-danger me-2">1</span> ข้อมูลรถยนต์ Porsche ของคุณ
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">เลือกรุ่นรถยนต์ Porsche <span class="text-danger">*</span></label>
                                    <select name="porsche_model_id" class="form-select @error('porsche_model_id') is-invalid @enderror" id="modelSelect" required>
                                        <option value="">-- กรุณาเลือกรุ่นรถยนต์ --</option>
                                        @foreach($models as $m)
                                            <option value="{{ $m->id }}" {{ old('porsche_model_id') == $m->id ? 'selected' : '' }}>
                                                [{{ $m->series }}] {{ $m->model_name }}
                                            </option>
                                        @endforeach
                                        <option value="other" {{ old('porsche_model_id') == 'other' ? 'selected' : '' }}>-- รุ่นอื่นๆ / รุ่นคลาสสิก --</option>
                                    </select>
                                    @error('porsche_model_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6" id="customModelGroup" style="display: {{ old('porsche_model_id') == 'other' ? 'block' : 'none' }};">
                                    <label class="form-label fw-medium">ระบุชื่อรุ่นเพิ่มเติม</label>
                                    <input type="text" name="model_custom_name" class="form-control" value="{{ old('model_custom_name') }}" placeholder="เช่น Porsche 964 Turbo หรือ Carrera GT">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">หมายเลขทะเบียนรถ <span class="text-danger">*</span></label>
                                    <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror" value="{{ old('license_plate') }}" placeholder="เช่น 9กก 9911" required>
                                    @error('license_plate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">จังหวัดที่จดทะเบียน <span class="text-danger">*</span></label>
                                    <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province', 'กรุงเทพมหานคร') }}" required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">ปีที่จดทะเบียนรถ (ค.ศ.) <span class="text-danger">*</span></label>
                                    <input type="number" name="car_year" class="form-control @error('car_year') is-invalid @enderror" value="{{ old('car_year', 2023) }}" min="1970" max="{{ date('Y') + 1 }}" required>
                                    @error('car_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">สีตัวถังภายนอก <span class="text-danger">*</span></label>
                                    <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}" placeholder="เช่น Guards Red, GT Silver, Crayon" required>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">เลขไมล์ปัจจุบัน (กิโลเมตร) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="mileage" class="form-control @error('mileage') is-invalid @enderror" value="{{ old('mileage') }}" placeholder="เช่น 15000" min="0" required>
                                        <span class="input-group-text">km</span>
                                    </div>
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fw-medium mb-0">เลขตัวถัง (VIN 17 หลัก)</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" id="btnFillSampleVin">
                                            <i class="fa-solid fa-wand-magic-sparkles me-1 text-warning"></i> สุ่มตัวอย่าง
                                        </button>
                                    </div>
                                    <input type="text" name="vin_number" id="vinNumberInput" class="form-control text-uppercase @error('vin_number') is-invalid @enderror" value="{{ old('vin_number') }}" placeholder="WP0ZZZ99ZTS......" maxlength="17">
                                    @error('vin_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: รายละเอียดงานซ่อมและอาการเสีย -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <span class="badge bg-danger me-2">2</span> รายละเอียดงานบริการและอาการที่พบ
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">หมวดหมู่งานบริการ <span class="text-danger">*</span></label>
                                    <select name="service_category" class="form-select @error('service_category') is-invalid @enderror" required>
                                        <option value="">-- เลือกประเภทงานบริการ --</option>
                                        <option value="ตรวจเช็คระยะตามรอบ (Scheduled Service)">ตรวจเช็คระยะตามรอบ (Scheduled Service 111 จุด)</option>
                                        <option value="ระบบเบรก PCCB / ผ้าเบรก">ระบบเบรก PCCB คาร์บอนเซรามิก / เปลี่ยนผ้าเบรก</option>
                                        <option value="ระบบไฟฟ้า / แบตเตอรี่ EV (Taycan)">ระบบไฟฟ้าและแบตเตอรี่แรงดันสูง EV (Taycan / E-Hybrid)</option>
                                        <option value="ระบบเกียร์คลัตช์คู่ PDK">ระบบเกียร์คลัตช์คู่ PDK และน้ำมันเกียร์</option>
                                        <option value="ระบบช่วงล่างถุงลม PASM">ระบบช่วงล่างถุงลม PASM & Active Ride</option>
                                        <option value="ระบบเครื่องยนต์ Boxer & ระบายความร้อน">ระบบเครื่องยนต์ Boxer 6 สูบ & ระบบระบายความร้อน</option>
                                        <option value="ตัวถัง สี และอุปกรณ์เสริม Tequipment">ตัวถัง สี และติดตั้งอุปกรณ์เสริม Tequipment</option>
                                        <option value="อื่นๆ">อื่นๆ (ระบุในรายละเอียด)</option>
                                    </select>
                                    @error('service_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">ระดับความเร่งด่วน <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="urgency" id="urgencyNormal" value="normal" checked>
                                            <label class="form-check-label" for="urgencyNormal">
                                                <i class="fa-solid fa-circle text-primary me-1"></i> ปกติ (Normal)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="urgency" id="urgencyUrgent" value="urgent">
                                            <label class="form-check-label" for="urgencyUrgent">
                                                <i class="fa-solid fa-bolt text-warning me-1"></i> เร่งด่วน (Urgent)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="urgency" id="urgencyEmergency" value="emergency">
                                            <label class="form-check-label" for="urgencyEmergency">
                                                <i class="fa-solid fa-truck-pickup text-danger me-1"></i> ฉุกเฉิน/รถสไลด์
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium">หัวข้ออาการที่พบ / ปัญหาที่ต้องการแจ้งซ่อม <span class="text-danger">*</span></label>
                                    <input type="text" name="symptom_title" class="form-control @error('symptom_title') is-invalid @enderror" value="{{ old('symptom_title') }}" placeholder="เช่น มีเสียงเตือนผ้าเบรกและไฟ ABS โชว์ที่หน้าปัดดิจิทัล" required>
                                    @error('symptom_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium">รายละเอียดอาการเพิ่มเติม</label>
                                    <textarea name="symptom_detail" class="form-control" rows="3" placeholder="ระบุอาการให้ละเอียด เช่น เกิดขึ้นตอนความเร็วเท่าใด หรือมีสัญญาณเตือนอะไรขึ้นบนหน้าจอ PCM">{{ old('symptom_detail') }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">วันที่สะดวกนำรถเข้าศูนย์ <span class="text-danger">*</span></label>
                                    <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">เวลานัดหมาย <span class="text-danger">*</span></label>
                                    <select name="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                        <option value="09:00">09:00 น.</option>
                                        <option value="10:00" selected>10:00 น.</option>
                                        <option value="11:00">11:00 น.</option>
                                        <option value="13:30">13:30 น.</option>
                                        <option value="14:30">14:30 น.</option>
                                        <option value="15:30">15:30 น.</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">ประเภทการรับประกัน / การชำระเงิน <span class="text-danger">*</span></label>
                                    <select name="warranty_type" class="form-select" required>
                                        <option value="porsche_approved">Porsche Approved Warranty</option>
                                        <option value="insurance">เคลมประกันภัยชั้น 1</option>
                                        <option value="self_pay" selected>ลูกค้าชำระค่าบริการเอง (Self-Pay)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: แนบรูปถ่ายอาการเสีย (Multiple Image Upload with Live Preview) -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <span class="badge bg-danger me-2">3</span> แนบรูปถ่ายอาการเสียหรือจุดที่ต้องการซ่อม
                            </h5>

                            <div class="card bg-light border-dashed p-4 text-center mb-3">
                                <i class="fa-solid fa-cloud-arrow-up text-danger fs-1 mb-2"></i>
                                <div class="fw-bold mb-1">เลือกไฟล์รูปภาพที่ต้องการแนบ (แนบได้หลายรูป)</div>
                                <div class="text-muted small mb-3">รองรับไฟล์ JPG, PNG, WEBP, SVG ขนาดไม่เกิน 8MB ต่อรูป</div>
                                
                                <div class="d-flex justify-content-center">
                                    <input type="file" name="images[]" id="repairImagesInput" class="form-control" style="max-width: 400px;" multiple accept="image/*">
                                </div>
                            </div>

                            <!-- Live Image Preview Container -->
                            <div class="row g-2" id="imagePreviewContainer">
                                <div class="col-12 text-muted small text-center">
                                    <i class="fa-solid fa-images me-1"></i> ยังไม่ได้เลือกไฟล์รูปภาพ (สามารถถ่ายรูปไฟเตือนบนหน้าปัดหรือรอยชำรุดแล้วอัปโหลดได้เลย)
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('customer.repairs.index') }}" class="btn btn-outline-secondary px-4">ยกเลิก</a>
                            <button type="submit" class="btn btn-porsche py-2 px-5">
                                <i class="fa-solid fa-paper-plane me-2"></i>ยืนยันการส่งคำขอแจ้งซ่อม
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('modelSelect').addEventListener('change', function() {
        const customGrp = document.getElementById('customModelGroup');
        if (this.value === 'other') {
            customGrp.style.display = 'block';
        } else {
            customGrp.style.display = 'none';
        }
    });
</script>
@endpush
