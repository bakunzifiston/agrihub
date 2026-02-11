<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerEmployee extends Model
{
    public const EMPLOYMENT_FULL_TIME = 'full_time';
    public const EMPLOYMENT_PART_TIME = 'part_time';
    public const EMPLOYMENT_SEASONAL = 'seasonal';
    public const EMPLOYMENT_CONTRACT = 'contract';

    public const EMPLOYMENT_TYPES = [
        'full_time' => 'Full time',
        'part_time' => 'Part time',
        'seasonal' => 'Seasonal',
        'contract' => 'Contract',
    ];

    protected $fillable = [
        'farmer_id',
        'name',
        'role',
        'employment_type',
        'phone',
        'email',
        'address',
        'hire_date',
        'id_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
