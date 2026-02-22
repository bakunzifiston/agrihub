<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    public const TYPE_COUNTRY = 'country';
    public const TYPE_DISTRICT = 'district';
    public const TYPE_SECTOR = 'sector';
    public const TYPE_CELL = 'cell';
    public const TYPE_VILLAGE = 'village';

    protected $fillable = [
        'type',
        'name',
        'parent_id',
        'code',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function scopeCountries($query)
    {
        return $query->where('type', self::TYPE_COUNTRY);
    }

    public function scopeDistricts($query)
    {
        return $query->where('type', self::TYPE_DISTRICT);
    }

    public function scopeSectors($query)
    {
        return $query->where('type', self::TYPE_SECTOR);
    }

    public function scopeCells($query)
    {
        return $query->where('type', self::TYPE_CELL);
    }

    public function scopeVillages($query)
    {
        return $query->where('type', self::TYPE_VILLAGE);
    }

    public function scopeChildrenOf($query, ?int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }
}
