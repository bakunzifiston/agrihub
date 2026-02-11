<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeMember extends Model
{
    protected $fillable = [
        'cooperative_id',
        'farmer_id',
        'full_name',
        'national_id',
        'phone',
        'email',
        'address',
        'membership_number',
        'join_date',
        'contribution_amount',
        'role',
        'status',
    ];

    /**
     * Display name: full_name if set, otherwise linked farmer's name.
     */
    public function getDisplayNameAttribute(): string
    {
        if (! empty($this->full_name)) {
            return $this->full_name;
        }
        return $this->farmer?->name ?? '—';
    }

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'contribution_amount' => 'decimal:2',
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
