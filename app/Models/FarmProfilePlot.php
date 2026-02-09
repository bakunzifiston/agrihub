<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FarmProfilePlot extends Model
{
    protected $table = 'farm_profile_plots';

    protected $fillable = [
        'farm_profile_id',
        'name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function farmProfile(): BelongsTo
    {
        return $this->belongsTo(FarmProfile::class);
    }

    public function crops(): BelongsToMany
    {
        return $this->belongsToMany(Crop::class, 'crop_farm_profile_plot')->withTimestamps();
    }
}
