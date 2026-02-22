<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmerEmployee extends Model
{
    public const EMPLOYMENT_FULL_TIME = 'full_time';
    public const EMPLOYMENT_PART_TIME = 'part_time';
    public const EMPLOYMENT_SEASONAL = 'seasonal';
    public const EMPLOYMENT_CONTRACT = 'contract';

    public const EMPLOYMENT_TYPES = [
        'full_time' => 'Full Time',
        'part_time' => 'Part Time',
        'seasonal' => 'Seasonal',
        'contract' => 'Contract',
    ];

    protected $fillable = [
        'farmer_id',
        'farm_profile_id',
        'first_name',
        'last_name',
        'national_id',
        'phone_number',
        'email',
        'gender',
        'date_of_birth',
        'photo',
        'job_title',
        'department',
        'employment_type',
        'hire_date',
        'end_date',
        'salary',
        'salary_period',
        'country',
        'district',
        'sector',
        'cell',
        'village',
        'emergency_contact_name',
        'emergency_contact_phone',
        'skills',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'end_date' => 'date',
            'salary' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function farmProfile(): BelongsTo
    {
        return $this->belongsTo(FarmProfile::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    public static function getEmploymentTypeLabel(?string $type): string
    {
        return self::EMPLOYMENT_TYPES[$type] ?? ucfirst(str_replace('_', ' ', $type ?? ''));
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return self::getEmploymentTypeLabel($this->employment_type);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(EmployeeTraining::class, 'employee_id');
    }
}
