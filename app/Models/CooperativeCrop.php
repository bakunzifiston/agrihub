<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeCrop extends Model
{
    protected $table = 'cooperative_crops';

    protected $fillable = [
        'cooperative_id',
        'crop_name',
        'crop_type',
        'season',
        'planting_date',
        'expected_harvest_date',
        'land_area_used',
        'area_unit',
        'expected_yield',
        'yield_unit',
        'crop_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'planting_date' => 'date',
            'expected_harvest_date' => 'date',
            'land_area_used' => 'decimal:2',
            'expected_yield' => 'decimal:2',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }
}
