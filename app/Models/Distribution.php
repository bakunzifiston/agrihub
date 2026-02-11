<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    protected $fillable = [
        'agribusiness_id',
        'inventory_id',
        'customer_id',
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

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(AgribusinessInventory::class, 'inventory_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(AgribusinessCustomer::class, 'customer_id');
    }

    /** Display customer name: linked customer or stored customer_name */
    public function getCustomerDisplayNameAttribute(): string
    {
        return $this->customer?->name ?? $this->customer_name ?? '';
    }

    /** In-stock quantity when distribution is linked to inventory; null otherwise */
    public function getInStockQuantityAttribute(): ?float
    {
        return $this->inventory ? (float) $this->inventory->quantity_in_stock : null;
    }

    /** Remaining stock (inventory - quantity dispatched) when linked; null otherwise */
    public function getRemainingStockAttribute(): ?float
    {
        if (! $this->inventory) {
            return null;
        }
        $inStock = (float) $this->inventory->quantity_in_stock;
        $dispatched = (float) $this->quantity_dispatched;
        return $inStock - $dispatched;
    }
}
