<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgribusinessCustomer extends Model
{
    public const TYPE_INDIVIDUAL = 'individual';
    public const TYPE_RETAILER = 'retailer';
    public const TYPE_WHOLESALER = 'wholesaler';
    public const TYPE_OTHER = 'other';

    public const TYPES = [
        'individual' => 'Individual',
        'retailer' => 'Retailer',
        'wholesaler' => 'Wholesaler',
        'other' => 'Other',
    ];

    protected $fillable = [
        'agribusiness_id',
        'name',
        'customer_type',
        'phone',
        'email',
        'address',
        'contact_person',
        'tax_id',
        'notes',
    ];

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class, 'customer_id');
    }
}
