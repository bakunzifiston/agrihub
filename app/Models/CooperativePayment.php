<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativePayment extends Model
{
    protected $fillable = [
        'cooperative_id',
        'farmer_id',
        'amount_paid',
        'payment_method',
        'reference_number',
        'payment_date',
        'payment_status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
