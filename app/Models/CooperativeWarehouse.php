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
        'city',
        'district',
        'sector',
        'country',
        'gps_latitude',
        'gps_longitude',
        'phone',
        'email',
        'manager_member_id',
        'manager_name',
    ];

    protected function casts(): array
    {
        return [
            'gps_latitude' => 'decimal:7',
            'gps_longitude' => 'decimal:7',
        ];
    }

    /** Auto-generated warehouse ID for display (e.g. WH-0001). */
    public function getWarehouseIdAttribute(): string
    {
        return 'WH-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooperative_id');
    }

    public function managerMember(): BelongsTo
    {
        return $this->belongsTo(CooperativeMember::class, 'manager_member_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(CooperativeInventory::class, 'warehouse_id');
    }

    /** Manager display name: from member or manual manager_name. */
    public function getManagerDisplayNameAttribute(): string
    {
        if ($this->managerMember) {
            return $this->managerMember->display_name;
        }
        return $this->manager_name ?? '—';
    }
}
