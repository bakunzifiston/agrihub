<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRecord extends Model
{
    protected $fillable = [
        'farmer_id',
        'product_type',
        'product_name',
        'production_date',
        'quantity_produced',
        'quantity_unit',
        'quality_grade',
        'losses_quantity',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'production_date' => 'date',
            'quantity_produced' => 'decimal:2',
            'losses_quantity' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
