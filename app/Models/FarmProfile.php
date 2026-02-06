<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmProfile extends Model
{
    protected $fillable = [
        'farmer_id',
        'tenant_id',
        'full_name',
        'national_id',
        'phone_number',
        'email',
        'gender',
        'date_of_birth',
        'farm_name',
        'farm_type',
        'total_land_size',
        'land_unit',
        'location_country',
        'location_district',
        'location_sector',
        'location_cell',
        'location_village',
        'gps_latitude',
        'gps_longitude',
        'registration_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'registration_date' => 'date',
            'total_land_size' => 'decimal:2',
            'gps_latitude' => 'decimal:7',
            'gps_longitude' => 'decimal:7',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
