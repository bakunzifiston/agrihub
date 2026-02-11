<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmOutput extends Model
{
    protected $fillable = [
        'farmer_id',
        'product_name',
        'quantity_available',
        'unit',
        'storage_location',
        'harvest_date',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'harvest_date' => 'date',
            'expiry_date' => 'date',
            'quantity_available' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(FarmSale::class, 'farm_output_id');
    }
}
