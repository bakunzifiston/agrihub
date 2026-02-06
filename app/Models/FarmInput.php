<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmInput extends Model
{
    protected $fillable = [
        'farmer_id',
        'input_name',
        'input_category',
        'quantity',
        'unit',
        'purchase_date',
        'supplier_name',
        'cost_per_unit',
        'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'quantity' => 'decimal:2',
            'cost_per_unit' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
