<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RepairImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'uploaded_by',
        'image_type',
        'image_path',
        'file_name',
        'caption',
    ];

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class, 'repair_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->image_type) {
            'symptom' => 'ภาพอาการเสีย (ลูกค้าแจ้ง)',
            'inspection' => 'ภาพตรวจสภาพ (ช่างตรวจเช็ค)',
            'completed' => 'ภาพหลังการซ่อมเสร็จสิ้น',
            default => 'ภาพประกอบ',
        };
    }
}
