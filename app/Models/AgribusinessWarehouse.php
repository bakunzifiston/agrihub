<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgribusinessWarehouse extends Model
{
    protected $fillable = [
        'agribusiness_id',
        'name',
        'location',
        'description',
    ];

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(AgribusinessInventory::class, 'warehouse_id');
    }
}
