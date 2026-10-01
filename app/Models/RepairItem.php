<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'service_catalog_id',
        'item_type',
        'part_code',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal',
        'note',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class, 'repair_id');
    }

    public function serviceCatalog(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalog::class, 'service_catalog_id');
    }
}
