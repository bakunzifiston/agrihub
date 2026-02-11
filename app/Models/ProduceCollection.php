<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduceCollection extends Model
{
    protected $fillable = [
        'cooperative_id',
        'farmer_id',
        'member_id',
        'contributor_name',
        'product_name',
        'collection_date',
        'quantity_collected',
        'unit',
        'quality_grade',
        'collection_point',
        'price_per_unit',
        'total_value',
    ];

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'quantity_collected' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
            'total_value' => 'decimal:2',
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

    public function member(): BelongsTo
    {
        return $this->belongsTo(CooperativeMember::class, 'member_id');
    }

    /** Name of the member/farmer for display (member, then manual name, then farmer). */
    public function getContributorNameAttribute(): string
    {
        if ($this->member) {
            return $this->member->display_name;
        }
        if (! empty($this->attributes['contributor_name'] ?? null)) {
            return (string) $this->attributes['contributor_name'];
        }
        return $this->farmer?->name ?? '—';
    }
}
