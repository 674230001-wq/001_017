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
        Schema::create('service_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // เครื่องยนต์, ระบบเบรก, ระบบเกียร์ PDK, ช่วงล่าง PASM, แบตเตอรี่ EV/Hybrid, เช็คระยะ, ตัวถังและสี
            $table->string('part_code')->nullable(); // รหัสอะไหล่แท้ เช่น P-992-BRK-01
            $table->string('name'); // ชื่อรายการอะไหล่หรือบริการ
            $table->text('description')->nullable();
            $table->decimal('unit_price', 12, 2)->default(0); // ราคาอะไหล่ต่อหน่วย
            $table->decimal('labor_fee', 12, 2)->default(0); // ค่าแรงติดตั้ง/บริการ
            $table->string('unit')->default('ชิ้น'); // หน่วยนับ เช่น ชิ้น, ลิตร, ชุด, ครั้ง
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_catalogs');
    }
};
