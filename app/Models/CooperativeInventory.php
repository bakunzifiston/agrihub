<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeInventory extends Model
{
    protected $table = 'cooperative_inventory';

    protected $fillable = [
        'cooperative_id',
        'warehouse_id',
        'product_name',
        'category',
        'quantity_in_stock',
        'unit',
        'storage_location',
        'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'quantity_in_stock' => 'decimal:2',
            'last_updated' => 'datetime',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(CooperativeWarehouse::class, 'warehouse_id');
    }
}
