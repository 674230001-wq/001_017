<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'part_code',
        'name',
        'description',
        'unit_price',
        'labor_fee',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'labor_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
