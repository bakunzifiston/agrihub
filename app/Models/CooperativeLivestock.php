<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeLivestock extends Model
{
    protected $table = 'cooperative_livestock';

    protected $fillable = [
        'cooperative_id',
        'livestock_type',
        'breed',
        'quantity',
        'purpose',
        'acquisition_date',
        'health_status',
        'vaccination_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }
}
