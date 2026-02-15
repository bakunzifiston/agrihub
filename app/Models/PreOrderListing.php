<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreOrderListing extends Model
{
    protected $fillable = [
        'farmer_id',
        'crop_id',
        'farm_output_id',
        'title',
        'quantity_available',
        'unit',
        'price_per_unit',
        'expected_harvest_date',
        'is_active',
        'woocommerce_product_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity_available' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
            'expected_harvest_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

    public function farmOutput(): BelongsTo
    {
        return $this->belongsTo(FarmOutput::class, 'farm_output_id');
    }

    public function preOrders(): HasMany
    {
        return $this->hasMany(PreOrder::class, 'pre_order_listing_id');
    }

    /** Display name: from crop, output, or title */
    public function getProductNameAttribute(): string
    {
        if ($this->crop) {
            return $this->crop->crop_name . ($this->crop->crop_type ? " ({$this->crop->crop_type})" : '');
        }
        if ($this->farmOutput) {
            return $this->farmOutput->product_name;
        }
        return $this->title;
    }

    /** Quantity reserved by pending/confirmed pre-orders */
    public function getReservedQuantityAttribute(): float
    {
        return (float) $this->preOrders()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('quantity');
    }

    /** Quantity available to sell (available minus reserved) */
    public function getAvailableToSellAttribute(): float
    {
        return max(0, (float) $this->quantity_available - $this->reserved_quantity);
    }
}
