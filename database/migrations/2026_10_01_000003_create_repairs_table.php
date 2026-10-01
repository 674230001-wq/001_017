<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no')->unique(); // e.g. POR-2026-0001
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('porsche_model_id')->nullable()->constrained('porsche_models')->nullOnDelete();
            
            // ข้อมูลรถยนต์
            $table->string('model_custom_name')->nullable();
            $table->string('license_plate'); // e.g. 9กก 9911
            $table->string('province')->default('กรุงเทพมหานคร');
            $table->string('vin_number', 17)->nullable(); // เลขตัวถัง 17 หลัก
            $table->integer('car_year')->nullable(); // เช่น 2023
            $table->string('color')->nullable(); // สี เช่น Guards Red, GT Silver
            $table->integer('mileage')->default(0); // เลขไมล์ (km)
            
            // ข้อมูลการเข้ารับบริการ
            $table->string('service_category'); // ตรวจเช็คระยะ, ระบบเบรก PCCB, ระบบไฟฟ้า EV, ช่วงล่าง PASM, เครื่องยนต์, เกียร์ PDK, อื่นๆ
            $table->string('urgency')->default('normal'); // normal (ปกติ), urgent (เร่งด่วน), emergency (ฉุกเฉิน/รถสไลด์)
            $table->string('symptom_title'); // อาการเบื้องต้น
            $table->text('symptom_detail')->nullable(); // รายละเอียดเพิ่มเติม
            $table->date('appointment_date')->nullable();
            $table->string('appointment_time', 20)->nullable();
            $table->string('warranty_type')->default('self_pay'); // porsche_approved, insurance, self_pay
            
            // สถานะงานซ่อม
            // pending: รอดำเนินการ, assigned: ช่างรับเรื่อง, inspecting: กำลังตรวจเช็ค, estimated: ประเมินราคาแล้ว, 
            // approved: อนุมัติซ่อม, rejected: ไม่อนุมัติ, in_progress: กำลังซ่อม, completed: ซ่อมเสร็จ, delivered: ส่งมอบแล้ว, cancelled: ยกเลิก
            $table->string('status')->default('pending');
            
            // ข้อมูลการตรวจเช็คและการประเมินราคาโดยช่าง
            $table->text('technician_notes')->nullable();
            $table->json('inspection_checklist')->nullable(); // รายการตรวจเช็คสภาพรถ 5 หมวด (เบรก, ยาง, แบตเตอรี่, ของเหลว, อิเล็กทรอนิกส์)
            $table->decimal('estimated_parts_total', 12, 2)->default(0);
            $table->decimal('estimated_labor_total', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0); // 7%
            $table->decimal('net_total', 12, 2)->default(0); // รวมสุทธิ
            
            // การอนุมัติของลูกค้า
            $table->string('customer_approval_status')->nullable(); // pending, approved, rejected
            $table->text('customer_approval_note')->nullable();
            $table->timestamp('customer_approved_at')->nullable();
            $table->timestamp('repair_completed_at')->nullable();
            
            // ความพึงพอใจ
            $table->unsignedTinyInteger('customer_rating')->nullable(); // 1-5 ดาว
            $table->text('customer_feedback')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
