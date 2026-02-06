<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessingRecord extends Model
{
    protected $fillable = [
        'agribusiness_id',
        'raw_material',
        'quantity_input',
        'input_unit',
        'quantity_output',
        'output_unit',
        'processing_date',
        'processing_cost',
        'wastage_quantity',
    ];

    protected function casts(): array
    {
        return [
            'processing_date' => 'date',
            'quantity_input' => 'decimal:2',
            'quantity_output' => 'decimal:2',
            'processing_cost' => 'decimal:2',
            'wastage_quantity' => 'decimal:2',
        ];
    }

    public function agribusiness(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agribusiness_id');
    }
}
