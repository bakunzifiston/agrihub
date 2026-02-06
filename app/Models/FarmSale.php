<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmSale extends Model
{
    protected $fillable = [
        'farmer_id',
        'buyer_type',
        'buyer_name',
        'product_name',
        'quantity_sold',
        'unit',
        'unit_price',
        'total_amount',
        'payment_method',
        'payment_status',
        'sale_date',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'quantity_sold' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
