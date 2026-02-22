<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmInput extends Model
{
    protected $fillable = [
        'farmer_id',
        'input_name',
        'input_category',
        'quantity',
        'unit',
        'purchase_date',
        'supplier_name',
        'cost_per_unit',
        'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'quantity' => 'decimal:2',
            'cost_per_unit' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public static function getInputCategoryLabel(?string $category): string
    {
        if (!$category) {
            return '-';
        }
        $categories = config('agricultural-inputs');
        return $categories[$category]['label'] ?? ucfirst(str_replace('_', ' ', $category));
    }

    public function getInputCategoryLabelAttribute(): string
    {
        return static::getInputCategoryLabel($this->input_category);
    }
}
