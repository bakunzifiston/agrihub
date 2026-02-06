<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livestock extends Model
{
    protected $table = 'livestock';

    protected $fillable = [
        'farmer_id',
        'livestock_type',
        'breed',
        'quantity',
        'purpose',
        'acquisition_date',
        'health_status',
        'vaccination_status',
    ];

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
