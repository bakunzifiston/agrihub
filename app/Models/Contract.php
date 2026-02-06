<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'agribusiness_id',
        'supplier_id',
        'product_name',
        'contract_quantity',
        'unit',
        'price_per_unit',
        'start_date',
        'end_date',
        'delivery_schedule',
        'contract_status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'contract_quantity' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
        ];
    }

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
