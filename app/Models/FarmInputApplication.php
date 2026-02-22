<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmInputApplication extends Model
{
    protected $fillable = [
        'farmer_id',
        'farm_profile_id',
        'farm_profile_plot_id',
        'crop_id',
        'farmer_registered_product_id',
        'input_name',
        'input_type',
        'batch_number',
        'supplier',
        'farmer_supplier_id',
        'application_date',
        'quantity_used',
        'unit',
        'applied_by',
        'phi_days',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'quantity_used' => 'decimal:2',
            'phi_days' => 'integer',
        ];
    }

    public static function getInputTypeLabel(?string $type): string
    {
        if (! $type) {
            return 'Unknown';
        }

        $categories = config('agricultural-inputs');

        return $categories[$type]['label'] ?? ucfirst(str_replace('_', ' ', $type));
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function farmProfile(): BelongsTo
    {
        return $this->belongsTo(FarmProfile::class);
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(FarmProfilePlot::class, 'farm_profile_plot_id');
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function registeredProduct(): BelongsTo
    {
        return $this->belongsTo(FarmerRegisteredProduct::class, 'farmer_registered_product_id');
    }

    public function supplierRecord(): BelongsTo
    {
        return $this->belongsTo(FarmerSupplier::class, 'farmer_supplier_id');
    }
}
