<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    protected $fillable = [
        'agribusiness_id',
        'customer_name',
        'product_name',
        'quantity_dispatched',
        'unit',
        'dispatch_date',
        'delivery_status',
    ];

    protected function casts(): array
    {
        return [
            'dispatch_date' => 'date',
            'quantity_dispatched' => 'decimal:2',
        ];
    }

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }
}
