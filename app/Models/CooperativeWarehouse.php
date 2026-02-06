<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CooperativeWarehouse extends Model
{
    protected $fillable = [
        'cooperative_id',
        'name',
        'location',
        'description',
    ];

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(CooperativeInventory::class, 'warehouse_id');
    }
}
