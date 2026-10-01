<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PorscheModel;
use App\Models\ServiceCatalog;
use App\Models\Repair;
use App\Models\RepairItem;
use App\Models\RepairImage;
use App\Models\RepairLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. สร้างผู้ใช้ระบบ (Admin, ช่าง, ลูกค้า)
        // Admin: Raphiphat001
        $admin = User::create([
            'name' => 'Raphiphat001',
            'email' => 'raphiphat001@porsche-service.th',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081-999-0001',
            'specialty' => 'ผู้จัดการศูนย์บริการและหัวหน้าควบคุมระบบ (Service Manager)',
        ]);

        // ช่าง: Thanasak 017
        $techThanasak = User::create([
            'name' => 'Thanasak 017',
            'email' => 'thanasak017@porsche-service.th',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'phone' => '089-777-0017',
            'specialty' => 'Porsche Master Certified Technician & ผู้เชี่ยวชาญระบบเครื่องยนต์ Boxer และ High-Voltage EV',
        ]);

        // ช่างเสริม
        $techSomchai = User::create([
            'name' => 'Somchai PDK',
            'email' => 'somchai008@porsche-service.th',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'phone' => '085-333-0008',
            'specialty' => 'ช่างเทคนิคชำนาญการพิเศษ ระบบเกียร์ PDK และช่วงล่างถุงลม PASM',
        ]);

        // ลูกค้า
        $customer1 = User::create([
            'name' => 'คุณสมพงษ์ เจริญทรัพย์ (Sompong)',
            'email' => 'sompong@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '084-555-1234',
        ]);

        $customer2 = User::create([
            'name' => 'คุณวิชัย สปีดสเตอร์ (Wichai)',
            'email' => 'wichai@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '086-111-5678',
        ]);

        $customer3 = User::create([
            'name' => 'คุณณัฐพร พรสวรรค์ (Nattaporn)',
            'email' => 'nattaporn@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '082-333-8899',
        ]);

        // 2. ข้อมูลรุ่นรถยนต์ Porsche
        $models = [
            [
                'series' => '911',
                'model_name' => 'Porsche 911 GT3 RS (992)',
                'engine_type' => '4.0L Naturally Aspirated Boxer-6 (525 แรงม้า)',
                'year_range' => '2022 - ปัจจุบัน',
                'description' => 'รถแข่งที่วิ่งบนถนนได้ตามกฎหมาย พร้อมระบบแอโรไดนามิกส์ DRS และช่วงล่างปรับได้ 4 โหมดจากพวงมาลัย',
            ],
            [
                'series' => '911',
                'model_name' => 'Porsche 911 Turbo S (992)',
                'engine_type' => '3.7L Twin-Turbo Boxer-6 (650 แรงม้า)',
                'year_range' => '2020 - ปัจจุบัน',
                'description' => 'ซูเปอร์คาร์ขับเคลื่อน 4 ล้ออัตราเร่ง 0-100 ใน 2.7 วินาที ผสานความหรูหราและความแรงขั้นสุดยอด',
            ],
            [
                'series' => '911',
                'model_name' => 'Porsche 911 Carrera GTS (992.2 T-Hybrid)',
                'engine_type' => '3.6L Boxer + Electric Exhaust Turbo (541 แรงม้า)',
                'year_range' => '2024 - ปัจจุบัน',
                'description' => 'ไฮบริดสมรรถนะสูงรุ่นแรกในประวัติศาสตร์ของตระกูล 911 การตอบสนองคันเร่งฉับไวระดับมอเตอร์สปอร์ต',
            ],
            [
                'series' => '718',
                'model_name' => 'Porsche 718 Cayman GT4 RS',
                'engine_type' => '4.0L Boxer-6 Mid-Engine (500 แรงม้า, 9,000 รอบ/นาที)',
                'year_range' => '2022 - ปัจจุบัน',
                'description' => 'เครื่องยนต์วางกลางขนานแท้ นำเครื่องยนต์เดียวกับ 911 GT3 มาใส่ในตัวถัง Cayman น้ำหนักเบาพิเศษ',
            ],
            [
                'series' => '718',
                'model_name' => 'Porsche 718 Boxster GTS 4.0',
                'engine_type' => '4.0L Naturally Aspirated Boxer-6 (400 แรงม้า)',
                'year_range' => '2020 - ปัจจุบัน',
                'description' => 'โรดสเตอร์เปิดประทุน 2 ที่นั่ง เสียงเครื่องยนต์ 6 สูบแท้ๆ ขับสนุกและคล่องตัวสูง',
            ],
            [
                'series' => 'Taycan',
                'model_name' => 'Porsche Taycan Turbo S Cross Turismo',
                'engine_type' => 'Dual Electric Motors (952 แรงม้า, สถาปัตยกรรม 800V)',
                'year_range' => '2024 - ปัจจุบัน',
                'description' => 'สปอร์ตวากอนไฟฟ้าสมรรถนะสูง ลุยได้ทุกสภาพถนน พร้อมแบตเตอรี่รุ่นใหม่ชาร์จไว 10-80% ใน 18 นาที',
            ],
            [
                'series' => 'Taycan',
                'model_name' => 'Porsche Taycan 4S',
                'engine_type' => 'Dual Electric Motors (544 แรงม้า)',
                'year_range' => '2021 - ปัจจุบัน',
                'description' => 'รถสปอร์ตซีดานไฟฟ้าล้วนที่ตอบโจทย์การใช้งานประจำวันและการเดินทางไกลอย่างลงตัว',
            ],
            [
                'series' => 'Cayenne',
                'model_name' => 'Porsche Cayenne Turbo E-Hybrid Coupe',
                'engine_type' => '4.0L V8 Twin-Turbo + E-Motor (739 แรงม้า)',
                'year_range' => '2023 - ปัจจุบัน',
                'description' => 'SUV สไตล์คูเป้ที่ทรงพลังที่สุดในตระกูล Cayenne รองรับการขับเคลื่อนด้วยไฟฟ้าล้วนและลุยความเร็วสูง',
            ],
            [
                'series' => 'Macan',
                'model_name' => 'Porsche Macan EV Turbo',
                'engine_type' => 'Dual Electric Motors AWD (639 แรงม้า, 1,130 Nm)',
                'year_range' => '2024 - ปัจจุบัน',
                'description' => 'SUV ไฟฟ้า 100% รุ่นใหม่ล่าสุด แพลตฟอร์ม PPE พร้อมระบบเลี้ยว 4 ล้อและช่วงล่างถุงลม',
            ],
            [
                'series' => 'Panamera',
                'model_name' => 'Porsche Panamera Turbo E-Hybrid',
                'engine_type' => '4.0L V8 Turbo Hybrid (680 แรงม้า) + Porsche Active Ride',
                'year_range' => '2024 - ปัจจุบัน',
                'description' => 'สปอร์ตลักชัวรีซาลูนระดับผู้บริหาร ผสานช่วงล่างปฏิวัติวงการ Active Ride นิ่งสนิทในทุกโค้ง',
            ],
        ];

        $createdModels = [];
        foreach ($models as $m) {
            $createdModels[] = PorscheModel::create($m);
        }

        // 3. แคตตาล็อกอะไหล่แท้และค่าบริการมาตรฐาน Porsche Genuine Parts
        $catalogs = [
            [
                'category' => 'ระบบเบรก',
                'part_code' => 'P-992-PCCB-F',
                'name' => 'ชุดจานและผ้าเบรกคาร์บอนเซรามิก PCCB คู่หน้า (Porsche Ceramic Composite Brake)',
                'description' => 'อะไหล่แท้ศูนย์เยอรมนี สำหรับรุ่น 911 GT3 / Turbo S ทนความร้อนสูงพิเศษ ไม่เกิดฝุ่นเบรก',
                'unit_price' => 185000.00,
                'labor_fee' => 4500.00,
                'unit' => 'ชุด',
            ],
            [
                'category' => 'ระบบเบรก',
                'part_code' => 'P-BRK-PAD-R',
                'name' => 'ชุดผ้าเบรกคู่หลัง Porsche Genuine High-Performance Brake Pads',
                'description' => 'ผ้าเบรกแท้ศูนย์เกรดศูนย์บริการมาตรฐาน ให้ระยะเบรกสั้น มั่นใจทุกย่านความเร็ว',
                'unit_price' => 24500.00,
                'labor_fee' => 1800.00,
                'unit' => 'ชุด',
            ],
            [
                'category' => 'เช็คระยะ',
                'part_code' => 'P-MOB-C40',
                'name' => 'น้ำมันเครื่องสังเคราะห์แท้ Mobil 1 C40 GT 0W-40 (มาตรฐานโรงงาน Porsche)',
                'description' => 'น้ำมันเครื่องเกรดมอเตอร์สปอร์ต รองรับเครื่องยนต์ Boxer 4.0L และเครื่องยนต์เบนซินสมรรถนะสูง',
                'unit_price' => 4800.00,
                'labor_fee' => 1200.00,
                'unit' => 'แกลลอน (5 ลิตร)',
            ],
            [
                'category' => 'เช็คระยะ',
                'part_code' => 'P-OIL-FLT',
                'name' => 'ไส้กรองน้ำมันเครื่องแท้ศูนย์ Porsche OEM Oil Filter พร้อมโอริงแท้',
                'description' => 'กรองสิ่งสกปรกและเศษโลหะในระบบหล่อลื่นอย่างมีประสิทธิภาพสูงสุด',
                'unit_price' => 1850.00,
                'labor_fee' => 500.00,
                'unit' => 'ชิ้น',
            ],
            [
                'category' => 'ระบบเกียร์ PDK',
                'part_code' => 'P-PDK-FLUID',
                'name' => 'น้ำมันเกียร์คลัตช์คู่ PDK Fluid Pentosin FFL-3 (ชุดเปลี่ยนถ่าย 8 ลิตร)',
                'description' => 'น้ำมันเกียร์เฉพาะทางสำหรับเกียร์ Porsche Doppelkupplung 7/8 จังหวะ',
                'unit_price' => 16800.00,
                'labor_fee' => 3500.00,
                'unit' => 'ชุด',
            ],
            [
                'category' => 'ช่วงล่าง PASM',
                'part_code' => 'P-PASM-AIR',
                'name' => 'โช้คอัพถุงลมช่วงล่าง PASM Air Suspension Strut (ข้างหน้า)',
                'description' => 'ชุดโช้คไฟฟ้าถุงลมแท้ ปรับความหนืดอัตโนมัติ นุ่มนวลและเกาะถนน',
                'unit_price' => 68000.00,
                'labor_fee' => 4000.00,
                'unit' => 'ต้น',
            ],
            [
                'category' => 'แบตเตอรี่ EV/Hybrid',
                'part_code' => 'P-TYC-BATT',
                'name' => 'โมดูลเซลล์แบตเตอรี่แรงดันสูง High-Voltage Battery Cell Module (Taycan/E-Hybrid)',
                'description' => 'ชุดเซลล์ลิเธียมไอออนมาตรฐานโรงงาน พร้อมระบบ Water-Cooling ในตัว',
                'unit_price' => 145000.00,
                'labor_fee' => 9500.00,
                'unit' => 'โมดูล',
            ],
            [
                'category' => 'ระบบเครื่องยนต์',
                'part_code' => 'P-SPK-PLUG',
                'name' => 'ชุดหัวเทียนแท้ Porsche Iridium Laser Spark Plugs (ชุด 6 หัว)',
                'description' => 'ให้การจุดระเบิดที่แม่นยำและสมบูรณ์ในรอบสูง ทนทานต่อแรงดันบูสต์เทอร์โบ',
                'unit_price' => 7200.00,
                'labor_fee' => 2400.00,
                'unit' => 'ชุด',
            ],
            [
                'category' => 'ระบบไฟฟ้า',
                'part_code' => 'P-PCM-UPDATE',
                'name' => 'บริการอัปเดตและคาริเบรทซอฟต์แวร์กล่อง ECU & ระบบ PCM 6.0 ด้วย PIWIS III',
                'description' => 'ตรวจเช็คกล่องควบคุมทุกโมดูลด้วยคอมพิวเตอร์วินิจฉัย PIWIS 3 แท้จากเยอรมนี',
                'unit_price' => 0.00,
                'labor_fee' => 3500.00,
                'unit' => 'ครั้ง',
            ],
            [
                'category' => 'เช็คระยะ',
                'part_code' => 'P-SRV-MAJOR',
                'name' => 'แพ็กเกจเช็คระยะครั้งใหญ่ Porsche Major Service (ตรวจเช็ค 111 จุดละเอียด)',
                'description' => 'ตรวจเช็คระบบเบรก, ช่วงล่าง, ระบบส่งกำลัง, ของเหลว, อิเล็กทรอนิกส์ และทดสอบวิ่งบนถนน',
                'unit_price' => 8500.00,
                'labor_fee' => 6000.00,
                'unit' => 'แพ็กเกจ',
            ],
            [
                'category' => 'ระบบระบายความร้อน',
                'part_code' => 'P-RAD-COOL',
                'name' => 'หม้อน้ำระบายความร้อน Center Radiator Kit แท้ Porsche Motorsport',
                'description' => 'แผงรังผึ้งอลูมิเนียมคุณภาพสูง ช่วยลดอุณหภูมิน้ำหล่อเย็นขณะวิ่งในสนามหรือขับขี่สภาพอากาศร้อน',
                'unit_price' => 38000.00,
                'labor_fee' => 3800.00,
                'unit' => 'ชุด',
            ],
            [
                'category' => 'ระบบเบรก',
                'part_code' => 'P-FLUID-DOT4',
                'name' => 'น้ำมันเบรกสมรรถนะสูง Porsche High-Performance Brake Fluid DOT 4 (จุดเดือดสูง)',
                'description' => 'น้ำมันเบรกเกรดเรซซิ่งจุดเดือดแห้งเกิน 300 องศาเซลเซียส สำหรับระบบเบรก PCCB',
                'unit_price' => 2200.00,
                'labor_fee' => 1500.00,
                'unit' => 'ชุด (2 ลิตร)',
            ],
        ];

        $createdCatalogs = [];
        foreach ($catalogs as $c) {
            $createdCatalogs[] = ServiceCatalog::create($c);
        }

        // 4. สร้างข้อมูลการแจ้งซ่อมตัวอย่าง (หลากหลายสถานะและมีรูปภาพ)
        
        // เคสที่ 1: 911 GT3 RS - ประเมินราคาแล้ว รอลูกค้าอนุมัติ (ช่าง Thanasak 017 ดูแล)
        $r1 = Repair::create([
            'ticket_no' => 'POR-2026-0001',
            'customer_id' => $customer1->id,
            'technician_id' => $techThanasak->id,
            'porsche_model_id' => $createdModels[0]->id, // 911 GT3 RS
            'license_plate' => '9กก 9911',
            'province' => 'กรุงเทพมหานคร',
            'vin_number' => 'WP0ZZZ99ZTS192834',
            'car_year' => 2023,
            'color' => 'Guards Red',
            'mileage' => 12450,
            'service_category' => 'ระบบเบรก PCCB',
            'urgency' => 'urgent',
            'symptom_title' => 'มีไฟเตือน Brake Wear ขึ้นเตือนหน้าปัด และเบรกมีเสียงเสียดสีเวลาเบรกหนัก',
            'symptom_detail' => 'ขับขี่บนทางด่วนแล้วมีเสียงดังแหลมจากล้อหน้าขวา และมีข้อความเตือนบนหน้าจอ PCM ให้ตรวจสอบผ้าเบรกคาร์บอนเซรามิก',
            'appointment_date' => now()->subDays(2)->format('Y-m-d'),
            'appointment_time' => '09:30',
            'warranty_type' => 'porsche_approved',
            'status' => 'estimated',
            'technician_notes' => 'ช่างธนศักดิ์ (Thanasak 017) ได้นำรถขึ้นลิฟต์และใช้เครื่องมือวัดความหนาผ้าเบรก PCCB พบว่าผ้าเบรกหน้าเหลือเพียง 2.1 มม. (เกณฑ์ต่ำสุด 2.5 มม.) จำเป็นต้องเปลี่ยนชุดผ้าเบรกหน้าและเปลี่ยนถ่ายน้ำมันเบรกจุดเดือดสูงใหม่ ส่วนจานคาร์บอนยังอยู่ในเกณฑ์มาตรฐาน ไม่แตกบิ่น',
            'inspection_checklist' => [
                'brake_system' => 'pass_with_repair', // ต้องเปลี่ยนผ้าเบรกหน้า
                'tires' => 'pass', // ดอกยางเหลือ 5.2 มม.
                'battery' => 'pass', // 12.6V ปกติ
                'fluids' => 'pass_with_repair', // น้ำมันเบรกมีความชื้นเกิน 3%
                'suspension' => 'pass', // โช้คอัพแห้งสนิท
                'diagnostics_codes' => 'Fault Code: P1489 - Front Brake Pad Wear Limit Reached',
            ],
            'customer_approval_status' => 'pending',
        ]);

        // รายการประเมินราคาสำหรับเคสที่ 1
        $item1_1 = RepairItem::create([
            'repair_id' => $r1->id,
            'service_catalog_id' => $createdCatalogs[0]->id,
            'item_type' => 'part',
            'part_code' => 'P-992-PCCB-F',
            'item_name' => 'ชุดผ้าเบรกคาร์บอนเซรามิก PCCB คู่หน้าแท้ (Genuine Porsche GT3 RS)',
            'quantity' => 1,
            'unit_price' => 185000.00,
            'subtotal' => 185000.00,
            'note' => 'อะไหล่แท้เบิกศูนย์เยอรมนี พร้อมเซนเซอร์เตือนผ้าเบรกใหม่ 2 ตัว',
        ]);

        $item1_2 = RepairItem::create([
            'repair_id' => $r1->id,
            'service_catalog_id' => $createdCatalogs[11]->id,
            'item_type' => 'fluid',
            'part_code' => 'P-FLUID-DOT4',
            'item_name' => 'น้ำมันเบรกสมรรถนะสูง Porsche High-Performance DOT 4',
            'quantity' => 1,
            'unit_price' => 2200.00,
            'subtotal' => 2200.00,
            'note' => 'ไล่อากาศระบบเบรกด้วยเครื่องอัดแรงดันมาตรฐานศูนย์',
        ]);

        $item1_3 = RepairItem::create([
            'repair_id' => $r1->id,
            'item_type' => 'labor',
            'part_code' => 'LAB-BRK-01',
            'item_name' => 'ค่าแรงช่างถอดประกอบระบบเบรก PCCB และทดสอบ Caliper',
            'quantity' => 1,
            'unit_price' => 4500.00,
            'subtotal' => 4500.00,
            'note' => 'โดยช่าง Thanasak 017 (Certified Porsche Master)',
        ]);

        $r1->recalculateTotals();

        // Logs สำหรับเคสที่ 1
        RepairLog::create([
            'repair_id' => $r1->id,
            'user_id' => $customer1->id,
            'status' => 'pending',
            'action_title' => 'ส่งแบบฟอร์มแจ้งซ่อมใหม่',
            'comment' => 'ลูกค้าส่งคำขอผ่านระบบออนไลน์ แจ้งอาการไฟเตือนผ้าเบรก',
            'created_at' => now()->subDays(2)->subHours(3),
        ]);
        RepairLog::create([
            'repair_id' => $r1->id,
            'user_id' => $admin->id,
            'status' => 'assigned',
            'action_title' => 'Admin (Raphiphat001) มอบหมายช่าง',
            'comment' => 'มอบหมายงานให้ช่าง Thanasak 017 รับผิดชอบตรวจเช็ค',
            'created_at' => now()->subDays(2)->subHours(1),
        ]);
        RepairLog::create([
            'repair_id' => $r1->id,
            'user_id' => $techThanasak->id,
            'status' => 'estimated',
            'action_title' => 'ช่าง Thanasak 017 บันทึกผลตรวจเช็คและประเมินราคา',
            'comment' => 'ประเมินราคาชุดผ้าเบรก PCCB หน้า และน้ำมันเบรกเรซซิ่ง รวมสุทธิ 205,121.00 บาท (รวม VAT)',
            'created_at' => now()->subDays(1),
        ]);

        // เคสที่ 2: Taycan Turbo S - กำลังดำเนินการซ่อม (in_progress) ลูกค้าอนุมัติแล้ว
        $r2 = Repair::create([
            'ticket_no' => 'POR-2026-0002',
            'customer_id' => $customer2->id,
            'technician_id' => $techThanasak->id,
            'porsche_model_id' => $createdModels[5]->id, // Taycan Turbo S
            'license_plate' => '2ขข 8888',
            'province' => 'กรุงเทพมหานคร',
            'vin_number' => 'WP0AA2Y15PSA11209',
            'car_year' => 2024,
            'color' => 'Frozen Blue Metallic',
            'mileage' => 8400,
            'service_category' => 'ระบบไฟฟ้า/แบตเตอรี่ EV',
            'urgency' => 'emergency',
            'symptom_title' => 'รถชาร์จไฟ DC Fast Charge ไม่เข้า และมีไฟเตือนระบบแรงดันสูง High-Voltage System Fault',
            'symptom_detail' => 'ขณะแวะชาร์จที่สถานี ปรากฏไฟสีแดงที่พอร์ตชาร์จและตัดไฟอัตโนมัติ รถวิ่งได้เฉพาะโหมดฉุกเฉิน (Turtle mode)',
            'appointment_date' => now()->subDays(4)->format('Y-m-d'),
            'appointment_time' => '10:00',
            'warranty_type' => 'porsche_approved',
            'status' => 'in_progress',
            'technician_notes' => 'ตรวจสอบพบการแจ้งเตือนโมดูลตรวจจับฉนวนแบตเตอรี่ เซลล์ที่ 4 มีความต้านทานผิดปกติ จำเป็นต้องเปลี่ยนชุด Battery Module และอัปเดต BMS Software ล่าสุด',
            'inspection_checklist' => [
                'brake_system' => 'pass',
                'tires' => 'pass',
                'battery' => 'fail', // High voltage module issue
                'fluids' => 'pass',
                'suspension' => 'pass',
                'diagnostics_codes' => 'BMS Error: P0B3C00 - High Voltage Battery Module Resistance Out of Spec',
            ],
            'customer_approval_status' => 'approved',
            'customer_approval_note' => 'อนุมัติให้ดำเนินการเปลี่ยนโมดูลแบตเตอรี่ได้เลยครับ ขอให้ช่างธนศักดิ์ช่วยตรวจเช็คให้ละเอียดด้วยครับ',
            'customer_approved_at' => now()->subDays(2),
        ]);

        RepairItem::create([
            'repair_id' => $r2->id,
            'service_catalog_id' => $createdCatalogs[6]->id,
            'item_type' => 'part',
            'part_code' => 'P-TYC-BATT',
            'item_name' => 'โมดูลเซลล์แบตเตอรี่แรงดันสูง High-Voltage Battery Cell Module (Taycan)',
            'quantity' => 1,
            'unit_price' => 145000.00,
            'subtotal' => 145000.00,
        ]);
        RepairItem::create([
            'repair_id' => $r2->id,
            'service_catalog_id' => $createdCatalogs[8]->id,
            'item_type' => 'labor',
            'part_code' => 'P-PCM-UPDATE',
            'item_name' => 'บริการอัปเดตและคาริเบรทระบบ High-Voltage BMS ด้วยคอมพิวเตอร์ PIWIS III',
            'quantity' => 1,
            'unit_price' => 3500.00,
            'subtotal' => 3500.00,
        ]);
        RepairItem::create([
            'repair_id' => $r2->id,
            'item_type' => 'labor',
            'part_code' => 'LAB-HV-01',
            'item_name' => 'ค่าแรงเทคนิคพิเศษ High-Voltage Certified Specialist (ถอดแพ็กแบตเตอรี่ 800V)',
            'quantity' => 1,
            'unit_price' => 9500.00,
            'subtotal' => 9500.00,
        ]);
        $r2->recalculateTotals();

        RepairLog::create([
            'repair_id' => $r2->id,
            'user_id' => $customer2->id,
            'status' => 'pending',
            'action_title' => 'ส่งคำขอแจ้งซ่อมฉุกเฉิน',
            'comment' => 'ลูกค้านำรถสไลด์เข้าศูนย์บริการ มีปัญหาแบตเตอรี่ EV',
            'created_at' => now()->subDays(4),
        ]);
        RepairLog::create([
            'repair_id' => $r2->id,
            'user_id' => $admin->id,
            'status' => 'assigned',
            'action_title' => 'Admin (Raphiphat001) มอบหมายช่าง Thanasak 017',
            'comment' => 'ส่งต่องานซ่อมให้ผู้เชี่ยวชาญ EV โดยตรง',
            'created_at' => now()->subDays(4)->addHours(1),
        ]);
        RepairLog::create([
            'repair_id' => $r2->id,
            'user_id' => $techThanasak->id,
            'status' => 'estimated',
            'action_title' => 'ช่าง Thanasak 017 ส่งใบประเมินราคา',
            'comment' => 'เสนอราคาเปลี่ยนโมดูลแบตเตอรี่ HV พร้อมคาริเบรทระบบไฟ',
            'created_at' => now()->subDays(3),
        ]);
        RepairLog::create([
            'repair_id' => $r2->id,
            'user_id' => $customer2->id,
            'status' => 'approved',
            'action_title' => 'ลูกค้ากดยืนยันอนุมัติการซ่อมผ่านระบบ',
            'comment' => 'ลูกค้ายินยอมราคาและเริ่มการซ่อม',
            'created_at' => now()->subDays(2),
        ]);
        RepairLog::create([
            'repair_id' => $r2->id,
            'user_id' => $techThanasak->id,
            'status' => 'in_progress',
            'action_title' => 'ช่าง Thanasak 017 เริ่มดำเนินการซ่อม',
            'comment' => 'นำรถเข้าห้องเฉพาะแรงดันสูง ตัดไฟ Safety Disconnect และเริ่มเปลี่ยนโมดูล',
            'created_at' => now()->subDays(1),
        ]);

        // เคสที่ 3: Cayenne Turbo E-Hybrid - ซ่อมเสร็จสิ้น (completed) พร้อมรีวิว 5 ดาว
        $r3 = Repair::create([
            'ticket_no' => 'POR-2026-0003',
            'customer_id' => $customer3->id,
            'technician_id' => $techSomchai->id,
            'porsche_model_id' => $createdModels[7]->id, // Cayenne Turbo E-Hybrid
            'license_plate' => '7กศ 7777',
            'province' => 'กรุงเทพมหานคร',
            'vin_number' => 'WP1ZZZ9YZPDA45912',
            'car_year' => 2023,
            'color' => 'Crayon Grey',
            'mileage' => 29800,
            'service_category' => 'ตรวจเช็คระยะ & ช่วงล่าง PASM',
            'urgency' => 'normal',
            'symptom_title' => 'เช็คระยะ 30,000 km และมีเสียงกระแทกเบาๆ จากช่วงล่างด้านซ้ายเวลาขึ้นลูกระนาด',
            'symptom_detail' => 'ต้องการเปลี่ยนถ่ายน้ำมันเครื่องตามระยะ และให้ตรวจสอบระบบช่วงล่างถุงลม',
            'appointment_date' => now()->subDays(10)->format('Y-m-d'),
            'appointment_time' => '13:30',
            'warranty_type' => 'porsche_approved',
            'status' => 'completed',
            'technician_notes' => 'ตรวจเช็ค 111 จุดเรียบร้อย เปลี่ยนถ่ายน้ำมันเครื่อง Mobil 1 C40 และไส้กรองแท้ เปลี่ยนบูชปีกนกและคาลิเบรทระบบถุงลม PASM ใหม่ทั้งหมด ทดสอบขับขี่แล้วเสียงเงียบสนิท สมบูรณ์แบบ 100%',
            'inspection_checklist' => [
                'brake_system' => 'pass',
                'tires' => 'pass',
                'battery' => 'pass',
                'fluids' => 'pass',
                'suspension' => 'pass',
                'diagnostics_codes' => 'All modules healthy - No fault codes',
            ],
            'customer_approval_status' => 'approved',
            'customer_approved_at' => now()->subDays(8),
            'repair_completed_at' => now()->subDays(6),
            'customer_rating' => 5,
            'customer_feedback' => 'ประทับใจศูนย์บริการมากครับ บริการรวดเร็ว ช่างอธิบายละเอียด แจ้งเตือนสถานะในเว็บชัดเจนมากครับ!',
        ]);

        RepairItem::create([
            'repair_id' => $r3->id,
            'service_catalog_id' => $createdCatalogs[2]->id,
            'item_type' => 'fluid',
            'part_code' => 'P-MOB-C40',
            'item_name' => 'น้ำมันเครื่อง Mobil 1 C40 GT 0W-40 (2 แกลลอน)',
            'quantity' => 2,
            'unit_price' => 4800.00,
            'subtotal' => 9600.00,
        ]);
        RepairItem::create([
            'repair_id' => $r3->id,
            'service_catalog_id' => $createdCatalogs[3]->id,
            'item_type' => 'part',
            'part_code' => 'P-OIL-FLT',
            'item_name' => 'ไส้กรองน้ำมันเครื่องแท้ศูนย์ Porsche Cayenne E-Hybrid',
            'quantity' => 1,
            'unit_price' => 1850.00,
            'subtotal' => 1850.00,
        ]);
        RepairItem::create([
            'repair_id' => $r3->id,
            'service_catalog_id' => $createdCatalogs[9]->id,
            'item_type' => 'labor',
            'part_code' => 'P-SRV-MAJOR',
            'item_name' => 'แพ็กเกจเช็คระยะ 30,000 กม. พร้อมตรวจเช็คระบบช่วงล่าง PASM 111 จุด',
            'quantity' => 1,
            'unit_price' => 6000.00,
            'subtotal' => 6000.00,
        ]);
        $r3->recalculateTotals();

        // เคสที่ 4: 718 Cayman GT4 RS - กำลังตรวจเช็คสภาพ (inspecting) โดยช่าง Thanasak 017
        $r4 = Repair::create([
            'ticket_no' => 'POR-2026-0004',
            'customer_id' => $customer1->id,
            'technician_id' => $techThanasak->id,
            'porsche_model_id' => $createdModels[3]->id, // 718 Cayman GT4 RS
            'license_plate' => '5สส 7188',
            'province' => 'กรุงเทพมหานคร',
            'vin_number' => 'WP0AC2A82NSA09182',
            'car_year' => 2023,
            'color' => 'Shark Blue',
            'mileage' => 6200,
            'service_category' => 'ระบบเกียร์ PDK',
            'urgency' => 'urgent',
            'symptom_title' => 'เกียร์เปลี่ยนกระตุกช่วงรอบต่ำ และมีข้อความ Transmission Warning บนหน้าปัด',
            'symptom_detail' => 'ขับในเมืองช่วงจังหวะเกียร์ 1 ไป 2 มีอาการหน่วงและกระตุก หลังจากนั้นมีไฟเตือนขึ้นเตือน',
            'appointment_date' => now()->format('Y-m-d'),
            'appointment_time' => '11:00',
            'warranty_type' => 'porsche_approved',
            'status' => 'inspecting',
            'technician_notes' => 'ช่างธนศักดิ์ (Thanasak 017) กำลังต่อเครื่อง PIWIS เพื่ออ่าน Data stream ของคลัตช์ชุดที่ 1 และวัดอุณหภูมิน้ำมันเกียร์ PDK',
            'inspection_checklist' => [
                'brake_system' => 'pass',
                'tires' => 'pass',
                'battery' => 'pass',
                'fluids' => 'inspecting',
                'suspension' => 'pass',
            ],
        ]);

        RepairLog::create([
            'repair_id' => $r4->id,
            'user_id' => $customer1->id,
            'status' => 'pending',
            'action_title' => 'ส่งแบบฟอร์มแจ้งซ่อมใหม่',
            'comment' => 'ลูกค้านำรถเข้ามาตรวจเช็คระบบเกียร์ PDK',
            'created_at' => now()->subHours(5),
        ]);
        RepairLog::create([
            'repair_id' => $r4->id,
            'user_id' => $admin->id,
            'status' => 'assigned',
            'action_title' => 'Admin (Raphiphat001) ส่งมอบหมายงาน',
            'comment' => 'มอบหมายงานด่วนให้ช่าง Thanasak 017 ดำเนินการ',
            'created_at' => now()->subHours(4),
        ]);
        RepairLog::create([
            'repair_id' => $r4->id,
            'user_id' => $techThanasak->id,
            'status' => 'inspecting',
            'action_title' => 'ช่าง Thanasak 017 เริ่มการตรวจเช็ค',
            'comment' => 'กำลังรัน Diagnostic test ระบบเกียร์คลัตช์คู่ PDK',
            'created_at' => now()->subHours(2),
        ]);

        // เคสที่ 5: Macan EV Turbo - รอดำเนินการ (pending) รอมอบหมายช่าง
        $r5 = Repair::create([
            'ticket_no' => 'POR-2026-0005',
            'customer_id' => $customer2->id,
            'technician_id' => null,
            'porsche_model_id' => $createdModels[8]->id, // Macan EV Turbo
            'license_plate' => '1ขผ 1122',
            'province' => 'กรุงเทพมหานคร',
            'vin_number' => 'WP1AA2A17RSA99812',
            'car_year' => 2024,
            'color' => 'Provence Purple',
            'mileage' => 3100,
            'service_category' => 'ระบบไฟฟ้า/EV',
            'urgency' => 'normal',
            'symptom_title' => 'ระบบเปิด-ปิดฝาท้ายไฟฟ้าด้วยเซนเซอร์เท้าไม่ทำงาน และต้องการอัปเดตระบบสัมผัส PCM',
            'symptom_detail' => 'แกว่งเท้าใต้กันชนหลังแล้วฝาท้ายไม่ตอบสนอง เป็นมาประมาณ 3 วันแล้ว',
            'appointment_date' => now()->addDays(1)->format('Y-m-d'),
            'appointment_time' => '14:00',
            'warranty_type' => 'porsche_approved',
            'status' => 'pending',
        ]);

        RepairLog::create([
            'repair_id' => $r5->id,
            'user_id' => $customer2->id,
            'status' => 'pending',
            'action_title' => 'ส่งคำขอแจ้งซ่อมผ่านหน้าเว็บ',
            'comment' => 'ลูกค้านัดหมายล่วงหน้าสำหรับวันพรุ่งนี้',
            'created_at' => now()->subHours(1),
        ]);

        // เคสที่ 6: 911 Carrera GTS - อนุมัติการซ่อมแล้ว รอคิวช่างเริ่มซ่อม (approved)
        $r6 = Repair::create([
            'ticket_no' => 'POR-2026-0006',
            'customer_id' => $customer3->id,
            'technician_id' => $techThanasak->id,
            'porsche_model_id' => $createdModels[2]->id, // 911 Carrera GTS T-Hybrid
            'license_plate' => '3กก 3399',
            'province' => 'กรุงเทพมหานคร',
            'vin_number' => 'WP0ZZZ99ZTS771021',
            'car_year' => 2024,
            'color' => 'Carmine Red',
            'mileage' => 4500,
            'service_category' => 'ระบบระบายความร้อน',
            'urgency' => 'normal',
            'symptom_title' => 'ติดตั้งชุด Center Radiator เพิ่มเติมเพื่อรองรับการขับในสนามแข่ง Track Day',
            'symptom_detail' => 'ต้องการเสริมระบบระบายความร้อนตรงกลาง และเปลี่ยนถ่ายน้ำยาหล่อเย็นเกรดแข่งขัน',
            'appointment_date' => now()->format('Y-m-d'),
            'appointment_time' => '15:30',
            'warranty_type' => 'self_pay',
            'status' => 'approved',
            'technician_notes' => 'ตรวจสอบความพร้อมของห้องเครื่องและช่องดักลมด้านหน้า พร้อมติดตั้งชุด Center Radiator แท้จาก Porsche Tequipment',
            'customer_approval_status' => 'approved',
            'customer_approved_at' => now()->subHours(3),
        ]);

        RepairItem::create([
            'repair_id' => $r6->id,
            'service_catalog_id' => $createdCatalogs[10]->id,
            'item_type' => 'part',
            'part_code' => 'P-RAD-COOL',
            'item_name' => 'หม้อน้ำระบายความร้อน Center Radiator Kit แท้ Porsche Tequipment',
            'quantity' => 1,
            'unit_price' => 38000.00,
            'subtotal' => 38000.00,
        ]);
        RepairItem::create([
            'repair_id' => $r6->id,
            'item_type' => 'labor',
            'part_code' => 'LAB-RAD-01',
            'item_name' => 'ค่าแรงติดตั้งชุดหม้อน้ำกลางและไล่ระบบน้ำยาหล่อเย็น',
            'quantity' => 1,
            'unit_price' => 3800.00,
            'subtotal' => 3800.00,
        ]);
        $r6->recalculateTotals();

        RepairLog::create([
            'repair_id' => $r6->id,
            'user_id' => $customer3->id,
            'status' => 'pending',
            'action_title' => 'ส่งแบบฟอร์มแจ้งบริการติดตั้งอุปกรณ์เสริม',
            'comment' => 'นัดหมายติดตั้ง Center Radiator Kit',
            'created_at' => now()->subDays(1),
        ]);
        RepairLog::create([
            'repair_id' => $r6->id,
            'user_id' => $admin->id,
            'status' => 'assigned',
            'action_title' => 'Admin (Raphiphat001) มอบหมายช่าง Thanasak 017',
            'comment' => 'ช่างรับเรื่องและจัดเตรียมชุดอะไหล่',
            'created_at' => now()->subHours(6),
        ]);
        RepairLog::create([
            'repair_id' => $r6->id,
            'user_id' => $techThanasak->id,
            'status' => 'estimated',
            'action_title' => 'ช่าง Thanasak 017 ประเมินราคาอะไหล่ Tequipment และค่าแรง',
            'comment' => 'รวมทั้งสิ้น 44,726.00 บาท (รวม VAT)',
            'created_at' => now()->subHours(4),
        ]);
        RepairLog::create([
            'repair_id' => $r6->id,
            'user_id' => $customer3->id,
            'status' => 'approved',
            'action_title' => 'ลูกค้ากดยืนยันอนุมัติซ่อมผ่านระบบ',
            'comment' => 'ลูกค้ายืนยันพร้อมให้นำรถเข้าช่องบริการ',
            'created_at' => now()->subHours(3),
        ]);

        // 5. สร้างรูปภาพตัวอย่างและบันทึกลงใน repair_images
        // เราจะสร้างรูปภาพตัวอย่างสวยงามเก็บไว้ใน storage/app/public/repairs
        // เพื่อให้หน้าแสดงผลมีรูปภาพรถและอาการเสียจริง ไม่ขึ้นเป็นภาพแตก
        $sampleImages = [
            [
                'repair_id' => $r1->id,
                'uploaded_by' => $customer1->id,
                'image_type' => 'symptom',
                'image_path' => 'repairs/porsche-brake-warning.svg',
                'file_name' => 'porsche-brake-warning.svg',
                'caption' => 'ไฟเตือน Brake Pad Wear Warning แสดงผลบนหน้าปัดเรือนไมล์ดิจิทัล',
            ],
            [
                'repair_id' => $r1->id,
                'uploaded_by' => $techThanasak->id,
                'image_type' => 'inspection',
                'image_path' => 'repairs/porsche-pccb-caliper.svg',
                'file_name' => 'porsche-pccb-caliper.svg',
                'caption' => 'ภาพคาลิปเปอร์เบรกสีเหลือง PCCB และผ้าเบรกที่สึกหรอจากการตรวจสภาพโดยช่างธนศักดิ์',
            ],
            [
                'repair_id' => $r2->id,
                'uploaded_by' => $customer2->id,
                'image_type' => 'symptom',
                'image_path' => 'repairs/porsche-taycan-fault.svg',
                'file_name' => 'porsche-taycan-fault.svg',
                'caption' => 'ไฟสีแดงเตือนความผิดปกติระบบชาร์จไฟที่พอร์ต DC Fast Charge ข้างตัวรถ Taycan',
            ],
            [
                'repair_id' => $r2->id,
                'uploaded_by' => $techThanasak->id,
                'image_type' => 'inspection',
                'image_path' => 'repairs/porsche-battery-pack.svg',
                'file_name' => 'porsche-battery-pack.svg',
                'caption' => 'ตรวจเช็คความต้านทานเซลล์แบตเตอรี่แรงดันสูง 800V ขณะยกแพ็กแบตเตอรี่',
            ],
            [
                'repair_id' => $r3->id,
                'uploaded_by' => $techSomchai->id,
                'image_type' => 'completed',
                'image_path' => 'repairs/porsche-cayenne-done.svg',
                'file_name' => 'porsche-cayenne-done.svg',
                'caption' => 'รถ Porsche Cayenne ผ่านการตรวจเช็ค 111 จุดและล้างทำความสะอาดพร้อมส่งมอบ',
            ],
            [
                'repair_id' => $r4->id,
                'uploaded_by' => $customer1->id,
                'image_type' => 'symptom',
                'image_path' => 'repairs/porsche-pdk-warning.svg',
                'file_name' => 'porsche-pdk-warning.svg',
                'caption' => 'ข้อความเตือน Transmission Emergency Run ปรากฏบนจอแสดงผล',
            ],
        ];

        foreach ($sampleImages as $img) {
            RepairImage::create($img);
        }
    }
}
