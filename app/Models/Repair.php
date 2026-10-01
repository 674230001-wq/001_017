<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_no',
        'customer_id',
        'technician_id',
        'porsche_model_id',
        'model_custom_name',
        'license_plate',
        'province',
        'vin_number',
        'car_year',
        'color',
        'mileage',
        'service_category',
        'urgency',
        'symptom_title',
        'symptom_detail',
        'appointment_date',
        'appointment_time',
        'warranty_type',
        'status',
        'technician_notes',
        'inspection_checklist',
        'estimated_parts_total',
        'estimated_labor_total',
        'discount',
        'vat_amount',
        'net_total',
        'customer_approval_status',
        'customer_approval_note',
        'customer_approved_at',
        'repair_completed_at',
        'customer_rating',
        'customer_feedback',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'inspection_checklist' => 'array',
        'estimated_parts_total' => 'decimal:2',
        'estimated_labor_total' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'net_total' => 'decimal:2',
        'customer_approved_at' => 'datetime',
        'repair_completed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function porscheModel(): BelongsTo
    {
        return $this->belongsTo(PorscheModel::class, 'porsche_model_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RepairItem::class, 'repair_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RepairImage::class, 'repair_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(RepairLog::class, 'repair_id')->orderBy('id', 'desc');
    }

    // คำนวณยอดรวมใหม่จาก items
    public function recalculateTotals(): void
    {
        $partsTotal = $this->items()->whereIn('item_type', ['part', 'fluid'])->sum('subtotal');
        $laborTotal = $this->items()->where('item_type', 'labor')->sum('subtotal');
        $extraTotal = $this->items()->where('item_type', 'other')->sum('subtotal');

        $subtotal = $partsTotal + $laborTotal + $extraTotal;
        $discount = $this->discount ?? 0;
        $afterDiscount = max(0, $subtotal - $discount);
        $vat = round($afterDiscount * 0.07, 2);
        $net = round($afterDiscount + $vat, 2);

        $this->update([
            'estimated_parts_total' => $partsTotal + $extraTotal,
            'estimated_labor_total' => $laborTotal,
            'vat_amount' => $vat,
            'net_total' => $net,
        ]);
    }

    // ข้อความแสดงสถานะภาษาไทย
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'รอดำเนินการ / รอรับเรื่อง',
            'assigned' => 'ช่างรับเรื่องแล้ว',
            'inspecting' => 'กำลังตรวจเช็คสภาพ',
            'estimated' => 'ประเมินราคาแล้ว (รอลูกค้าอนุมัติ)',
            'approved' => 'ลูกค้าอนุมัติการซ่อม',
            'rejected' => 'ลูกค้าขอระงับการซ่อม',
            'in_progress' => 'กำลังดำเนินการซ่อม',
            'completed' => 'ซ่อมเสร็จสิ้น / รอส่งมอบ',
            'delivered' => 'ส่งมอบรถเรียบร้อย',
            'cancelled' => 'ยกเลิกคำขอ',
            default => $this->status,
        };
    }

    // สี Badge สถานะ
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-secondary',
            'assigned' => 'bg-info text-dark',
            'inspecting' => 'bg-primary',
            'estimated' => 'bg-warning text-dark',
            'approved' => 'bg-info',
            'rejected' => 'bg-danger',
            'in_progress' => 'bg-primary text-white',
            'completed' => 'bg-success',
            'delivered' => 'bg-dark',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    // ข้อความความเร่งด่วน
    public function getUrgencyTextAttribute(): string
    {
        return match ($this->urgency) {
            'emergency' => '🚨 ฉุกเฉิน / รถสไลด์',
            'urgent' => '⚡ เร่งด่วน',
            default => '🔵 ปกติ',
        };
    }

    public function getUrgencyBadgeAttribute(): string
    {
        return match ($this->urgency) {
            'emergency' => 'badge-danger bg-danger text-white',
            'urgent' => 'badge-warning bg-warning text-dark',
            default => 'badge-secondary bg-light text-dark border',
        };
    }

    // ประเภทการรับประกัน
    public function getWarrantyTextAttribute(): string
    {
        return match ($this->warranty_type) {
            'porsche_approved' => 'Porsche Approved Warranty',
            'insurance' => 'เคลมประกันภัยชั้น 1',
            default => 'ชำระค่าบริการเอง (Self-Pay)',
        };
    }
}
