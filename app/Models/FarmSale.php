<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmSale extends Model
{
    protected $fillable = [
        'farmer_id',
        'farm_output_id',
        'client_id',
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

    public function output(): BelongsTo
    {
        return $this->belongsTo(FarmOutput::class, 'farm_output_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(FarmerClient::class, 'client_id');
    }

    /** Display buyer name: linked client or stored buyer_name */
    public function getBuyerDisplayNameAttribute(): string
    {
        return $this->client?->name ?? $this->buyer_name ?? '';
    }

    /** In-stock quantity when sale is linked to an output; null otherwise */
    public function getInStockQuantityAttribute(): ?float
    {
        return $this->output ? (float) $this->output->quantity_available : null;
    }

    /** Remaining stock (output quantity - quantity sold) when linked; null otherwise */
    public function getRemainingStockAttribute(): ?float
    {
        if (! $this->output) {
            return null;
        }
        $inStock = (float) $this->output->quantity_available;
        $sold = (float) $this->quantity_sold;
        return $inStock - $sold;
    }
}
