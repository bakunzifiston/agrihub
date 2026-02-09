<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FarmerSupplier extends Model
{
    protected $fillable = [
        'farmer_id',
        'name',
        'contact_phone',
        'contact_email',
        'address',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(FarmerRegisteredProduct::class, 'farmer_supplier_product');
    }
}
