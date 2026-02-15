<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrder extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_FULFILLED = 'fulfilled';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'fulfilled' => 'Fulfilled',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'pre_order_listing_id',
        'quantity',
        'status',
        'woocommerce_order_id',
        'customer_name',
        'customer_email',
        'customer_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

    public function preOrderListing(): BelongsTo
    {
        return $this->belongsTo(PreOrderListing::class, 'pre_order_listing_id');
    }
}
