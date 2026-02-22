<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeTraining extends Model
{
    protected $fillable = [
        'farmer_id',
        'employee_id',
        'training_name',
        'training_type',
        'provider',
        'description',
        'start_date',
        'end_date',
        'duration_hours',
        'status',
        'certificate_number',
        'certificate_expiry',
        'certificate_file',
        'cost',
        'location',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'certificate_expiry' => 'date',
            'cost' => 'decimal:2',
            'duration_hours' => 'integer',
        ];
    }

    public const TRAINING_TYPES = [
        'safety' => 'Health & Safety',
        'technical' => 'Technical Skills',
        'equipment' => 'Equipment Operation',
        'certification' => 'Certification',
        'compliance' => 'Compliance',
        'soft_skills' => 'Soft Skills',
        'management' => 'Management',
        'agriculture' => 'Agricultural Practices',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'scheduled' => 'Scheduled',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'failed' => 'Failed',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(FarmerEmployee::class, 'employee_id');
    }

    public static function getTrainingTypeLabel(?string $type): string
    {
        return self::TRAINING_TYPES[$type] ?? ucfirst(str_replace('_', ' ', $type ?? ''));
    }

    public function getTrainingTypeLabelAttribute(): string
    {
        return self::getTrainingTypeLabel($this->training_type);
    }

    public static function getStatusLabel(?string $status): string
    {
        return self::STATUSES[$status] ?? ucfirst(str_replace('_', ' ', $status ?? ''));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusLabel($this->status);
    }
}
