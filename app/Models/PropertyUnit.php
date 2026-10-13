<?php

namespace App\Models;

use App\Models\StructureLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyUnit extends Model
{
    protected $fillable = [
        'structure_level_id',
        'unit_code',
        'name',
        'unit_type',
        'area_m2',
        'description',
        'active',
    ];

    protected $casts = [
        'area_m2' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(
            StructureLevel::class,
            'structure_level_id'
        );
    }

    public function structureLevel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StructureLevel::class);
    }
}
