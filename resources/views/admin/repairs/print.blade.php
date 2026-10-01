<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบสั่งซ่อมและใบประเมินราคา - {{ $repair->ticket_no }}</title>
    
    <!-- Google Fonts: Sarabun & Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
            color: #1a1a1a;
            font-size: 13px;
        }
        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Kanit', sans-serif;
        }
        .print-page {
            background-color: #ffffff;
            max-width: 900px;
            margin: 20px auto;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .header-logo {
            letter-spacing: 3px;
            font-weight: 700;
            font-size: 24px;
            color: #d5001c;
        }
        .header-sub {
            letter-spacing: 1px;
            font-size: 11px;
            color: #64748b;
        }
        .table-custom th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        .signature-box {
            border-top: 1px dashed #64748b;
            margin-top: 50px;
            padding-top: 6px;
            text-align: center;
            font-size: 12px;
        }
        @media print {
            body {
                background-color: #ffffff;
                margin: 0;
                padding: 0;
            }
            .print-page {
                box-shadow: none;
                border: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print Button (ซ่อนเมื่อกดพิมพ์) -->
    <div class="text-center my-3 no-print">
        <button onclick="window.print()" class="btn btn-danger px-4 py-2 me-2">
            <i class="fa-solid fa-print me-1"></i> พิมพ์เอกสารฉบับนี้ (Print / Save PDF)
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-3 py-2">
            ปิดหน้านี้
        </button>
    </div>

    <div class="print-page">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start border-bottom border-2 border-danger pb-3 mb-3">
            <div>
                <div class="header-logo">PORSCHE</div>
                <div class="fw-bold text-dark fs-5">ศูนย์บริการและซ่อมบำรุงรถยนต์ปอร์เช่</div>
                <div class="header-sub">PORSCHE SPECIALIST SERVICE & GENUINE PARTS THAILAND</div>
                <div class="text-muted small mt-1">
                    โทร: 02-999-9911 | อีเมล: service@porsche-care.th | สาขาสำนักงานใหญ่
                </div>
            </div>
            <div class="text-end">
                <div class="badge bg-danger fs-6 mb-1">ใบสั่งซ่อมและใบเสนอราคา</div>
                <div class="fw-bold fs-5 text-dark">{{ $repair->ticket_no }}</div>
                <div class="text-muted small">วันที่ออกเอกสาร: {{ date('d/m/Y') }}</div>
                <div class="text-muted small">สถานะ: {{ $repair->status_text }}</div>
            </div>
        </div>

        <!-- Customer & Vehicle Info Grid -->
        <div class="row g-3 mb-3">
            <!-- Customer -->
            <div class="col-6">
                <div class="border p-2 rounded bg-light">
                    <div class="fw-bold text-dark border-bottom pb-1 mb-2 small text-uppercase">ข้อมูลลูกค้า (Customer Information)</div>
                    <div class="row g-1 small">
                        <div class="col-4 text-muted">ชื่อลูกค้า:</div>
                        <div class="col-8 fw-bold">{{ $repair->customer?->name }}</div>
                        <div class="col-4 text-muted">เบอร์โทรศัพท์:</div>
                        <div class="col-8">{{ $repair->customer?->phone ?: '-' }}</div>
                        <div class="col-4 text-muted">อีเมล:</div>
                        <div class="col-8">{{ $repair->customer?->email }}</div>
                        <div class="col-4 text-muted">การรับประกัน:</div>
                        <div class="col-8">{{ $repair->warranty_text }}</div>
                    </div>
                </div>
            </div>

            <!-- Vehicle -->
            <div class="col-6">
                <div class="border p-2 rounded bg-light">
                    <div class="fw-bold text-dark border-bottom pb-1 mb-2 small text-uppercase">ข้อมูลรถยนต์ (Vehicle Information)</div>
                    <div class="row g-1 small">
                        <div class="col-4 text-muted">รุ่นรถยนต์:</div>
                        <div class="col-8 fw-bold">{{ $repair->porscheModel?->model_name ?? $repair->model_custom_name }}</div>
                        <div class="col-4 text-muted">หมายเลขทะเบียน:</div>
                        <div class="col-8"><span class="badge bg-dark">{{ $repair->license_plate }} {{ $repair->province }}</span></div>
                        <div class="col-4 text-muted">เลขตัวถัง (VIN):</div>
                        <div class="col-8 text-monospace fw-bold">{{ $repair->vin_number ?: '-' }}</div>
                        <div class="col-4 text-muted">เลขไมล์ / สี / ปี:</div>
                        <div class="col-8">{{ number_format($repair->mileage) }} km | {{ $repair->color }} | {{ $repair->car_year }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Symptoms & Inspection -->
        <div class="border p-2 rounded mb-3 bg-white">
            <div class="row g-2 small">
                <div class="col-md-6 border-end">
                    <strong class="text-danger">อาการเบื้องต้นที่ลูกค้าแจ้ง:</strong>
                    <div class="mt-1">{{ $repair->symptom_title }}</div>
                    <div class="text-muted">{{ $repair->symptom_detail ?: '-' }}</div>
                </div>
                <div class="col-md-6 ps-md-3">
                    <strong class="text-primary">ผลการตรวจสภาพโดยช่าง (Thanasak 017):</strong>
                    <div class="mt-1">{{ $repair->technician_notes ?: 'ตรวจเช็คตามเกณฑ์มาตรฐาน 111 จุด' }}</div>
                    @if($repair->inspection_checklist)
                        <div class="text-muted mt-1" style="font-size: 11px;">
                            เบรก: {{ ($repair->inspection_checklist['brake_system'] ?? '') === 'pass' ? 'ปกติ' : 'ต้องเปลี่ยน' }} | 
                            ยาง: {{ ($repair->inspection_checklist['tires'] ?? '') === 'pass' ? 'ปกติ' : 'สึกหรอ' }} | 
                            แบตเตอรี่: {{ ($repair->inspection_checklist['battery'] ?? '') === 'pass' ? 'สมบูรณ์' : 'ผิดปกติ' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Parts & Labor Itemized Table -->
        <table class="table table-bordered table-sm table-custom mb-3">
            <thead>
                <tr>
                    <th class="text-center" style="width: 45px;">ลำดับ</th>
                    <th>รายการอะไหล่แท้ / ค่าบริการช่าง</th>
                    <th style="width: 140px;">รหัสอะไหล่ (Part No.)</th>
                    <th class="text-center" style="width: 70px;">จำนวน</th>
                    <th class="text-end" style="width: 130px;">ราคา/หน่วย (฿)</th>
                    <th class="text-end" style="width: 140px;">รวมเงิน (฿)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($repair->items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $item->item_name }}</strong>
                            @if($item->note)
                                <span class="text-muted small">({{ $item->note }})</span>
                            @endif
                        </td>
                        <td><code>{{ $item->part_code ?: '-' }}</code></td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-muted">ไม่มีรายการอะไหล่ระบุ</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end">รวมค่าอะไหล่และอุปกรณ์:</td>
                    <td class="text-end">{{ number_format($repair->estimated_parts_total, 2) }} ฿</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">รวมค่าแรงช่างเทคนิค:</td>
                    <td class="text-end">{{ number_format($repair->estimated_labor_total, 2) }} ฿</td>
                </tr>
                @if($repair->discount > 0)
                    <tr>
                        <td colspan="5" class="text-end text-danger">ส่วนลดพิเศษ (Discount):</td>
                        <td class="text-end text-danger">-{{ number_format($repair->discount, 2) }} ฿</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5" class="text-end">ภาษีมูลค่าเพิ่ม (VAT 7%):</td>
                    <td class="text-end">{{ number_format($repair->vat_amount, 2) }} ฿</td>
                </tr>
                <tr class="table-warning fw-bold fs-6">
                    <td colspan="5" class="text-end">ยอดสุทธิรวมทั้งสิ้น (Total Net Amount):</td>
                    <td class="text-end text-danger">{{ number_format($repair->net_total, 2) }} ฿</td>
                </tr>
            </tfoot>
        </table>

        <!-- Terms and Conditions -->
        <div class="border p-2 rounded mb-4 bg-light" style="font-size: 11px;">
            <strong>เงื่อนไขการรับประกันและข้อกำหนด:</strong>
            <ol class="mb-0 ps-3">
                <li>อะไหล่แท้ Porsche Genuine Parts รับประกันคุณภาพ 2 ปี โดยไม่จำกัดระยะทาง</li>
                <li>งานบริการและค่าแรงรับประกัน 6 เดือน หรือ 10,000 กิโลเมตร แล้วแต่อย่างใดอย่างหนึ่งถึงก่อน</li>
                <li>ใบเสนอราคานี้มีผลบังคับใช้ 30 วันนับจากวันที่ออกเอกสาร</li>
            </ol>
        </div>

        <!-- 3 Signatures Block -->
        <div class="row text-center mt-4">
            <div class="col-4">
                <div class="signature-box">
                    <strong>( {{ $repair->customer?->name }} )</strong>
                    <div class="text-muted">ลูกค้าผู้อนุมัติการซ่อม</div>
                    <div class="text-muted" style="font-size: 10px;">วันที่: _____/_____/_________</div>
                </div>
            </div>

            <div class="col-4">
                <div class="signature-box">
                    <strong>( {{ $repair->technician?->name ?? 'นายธนศักดิ์ (Thanasak 017)' }} )</strong>
                    <div class="text-muted">ช่างเทคนิคผู้ตรวจเช็ค & ดำเนินการ</div>
                    <div class="text-muted" style="font-size: 10px;">Porsche Master Certified</div>
                </div>
            </div>

            <div class="col-4">
                <div class="signature-box">
                    <strong>( นายรภิภัทร [Raphiphat001] )</strong>
                    <div class="text-muted">ผู้จัดการศูนย์บริการ / ผู้มีอำนาจลงนาม</div>
                    <div class="text-muted" style="font-size: 10px;">Service Center Director</div>
                </div>
            </div>
        </div>

        <!-- Footer Student Project Watermark -->
        <div class="text-center text-muted border-top mt-4 pt-2" style="font-size: 10px;">
            โครงงานพัฒนาระบบแจ้งซ่อมรถยนต์ Porsche | ผู้พัฒนา: นายรภิภัทร [Raphiphat001] & นายธนศักดิ์ [Thanasak 017] | สาขาวิชาวิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ (NPRU)
        </div>

    </div>

</body>
</html>
