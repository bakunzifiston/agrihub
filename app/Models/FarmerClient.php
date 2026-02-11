<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmerClient extends Model
{
    public const TYPE_INDIVIDUAL = 'individual';
    public const TYPE_SHOP = 'shop';
    public const TYPE_COMPANY = 'company';
    public const TYPE_OTHER = 'other';

    public const TYPES = [
        'individual' => 'Individual',
        'shop' => 'Shop',
        'company' => 'Company',
        'other' => 'Other',
    ];

    protected $fillable = [
        'farmer_id',
        'name',
        'client_type',
        'phone',
        'email',
        'address',
        'contact_person',
        'tax_id',
        'notes',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(FarmSale::class, 'client_id');
    }
}
