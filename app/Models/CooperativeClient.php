<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CooperativeClient extends Model
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
        'cooperative_id',
        'name',
        'client_type',
        'phone',
        'email',
        'address',
        'contact_person',
        'tax_id',
        'notes',
    ];

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(CooperativeOrder::class, 'client_id');
    }
}
