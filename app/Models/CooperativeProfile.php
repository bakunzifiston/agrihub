<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeProfile extends Model
{
    public const FOCUS_OPTIONS = [
        'crop' => 'Crop',
        'livestock' => 'Livestock',
        'dairy' => 'Dairy',
        'horticulture' => 'Horticulture',
        'fisheries' => 'Fisheries',
        'mixed' => 'Mixed',
        'other' => 'Other',
    ];

    protected $fillable = [
        'cooperative_id',
        'name',
        'registration_number',
        'phone',
        'email',
        'address',
        'district',
        'sector',
        'country',
        'description',
        'focus',
        'status',
        'registration_date',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
        ];
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }
}
