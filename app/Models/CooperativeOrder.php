<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeOrder extends Model
{
    protected $fillable = [
        'cooperative_id',
        'client_id',
        'inventory_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'product_name',
        'quantity',
        'unit',
        'unit_price',
        'total_amount',
        'order_date',
        'delivery_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'delivery_date' => 'date',
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(CooperativeClient::class, 'client_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(CooperativeInventory::class, 'inventory_id');
    }

    /** In-stock quantity when order is linked to inventory; null otherwise */
    public function getInStockQuantityAttribute(): ?float
    {
        return $this->inventory ? (float) $this->inventory->quantity_in_stock : null;
    }

    /** Remaining stock (inventory - order qty) when linked to inventory; null otherwise */
    public function getRemainingStockAttribute(): ?float
    {
        if (! $this->inventory) {
            return null;
        }
        $inStock = (float) $this->inventory->quantity_in_stock;
        $orderQty = (float) $this->quantity;
        return $inStock - $orderQty;
    }

    /** Display customer name: linked client or stored customer_name */
    public function getCustomerDisplayNameAttribute(): string
    {
        return $this->client?->name ?? $this->customer_name ?? '';
    }

    /** Auto-generated order number for display (e.g. ORD-0001). */
    public function getOrderIdAttribute(): string
    {
        return 'ORD-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }
}
