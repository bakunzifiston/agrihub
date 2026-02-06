<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgribusinessInventory extends Model
{
    protected $table = 'agribusiness_inventory';

    protected $fillable = [
        'agribusiness_id',
        'warehouse_id',
        'product_name',
        'category',
        'quantity_in_stock',
        'unit',
        'storage_location',
        'batch_number',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'quantity_in_stock' => 'decimal:2',
            'expiry_date' => 'date',
        ];
    }

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(AgribusinessWarehouse::class, 'warehouse_id');
    }
}
