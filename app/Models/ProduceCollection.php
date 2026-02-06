<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduceCollection extends Model
{
    protected $fillable = [
        'cooperative_id',
        'farmer_id',
        'product_name',
        'collection_date',
        'quantity_collected',
        'unit',
        'quality_grade',
        'collection_point',
        'price_per_unit',
        'total_value',
    ];

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'quantity_collected' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
            'total_value' => 'decimal:2',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
