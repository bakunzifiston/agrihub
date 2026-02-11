<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessingRawMaterial extends Model
{
    protected $fillable = [
        'processing_record_id',
        'raw_material',
        'quantity_input',
        'input_unit',
        'supplier_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity_input' => 'decimal:2',
        ];
    }

    public function processingRecord(): BelongsTo
    {
        return $this->belongsTo(ProcessingRecord::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
