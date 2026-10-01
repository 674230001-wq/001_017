<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PorscheModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'series',
        'model_name',
        'engine_type',
        'year_range',
        'image_url',
        'description',
    ];

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class, 'porsche_model_id');
    }
}
