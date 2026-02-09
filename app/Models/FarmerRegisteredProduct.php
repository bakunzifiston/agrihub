<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FarmerRegisteredProduct extends Model
{
    protected $fillable = [
        'farmer_id',
        'name',
        'product_type',
    ];

    public const TYPE_SEED = 'seed';
    public const TYPE_FERTILIZER = 'fertilizer';
    public const TYPE_PESTICIDE = 'pesticide';
    public const TYPE_HERBICIDE = 'herbicide';
    public const TYPE_OTHER = 'other';

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(FarmerSupplier::class, 'farmer_supplier_product');
    }
}
