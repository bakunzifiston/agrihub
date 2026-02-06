<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'agribusiness_id',
        'supplier_type',
        'supplier_name',
        'contact_person',
        'phone_number',
        'email',
        'location',
        'contract_status',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
        ];
    }

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
