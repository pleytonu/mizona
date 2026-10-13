<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Structure extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'structure_type',
        'built_area_m2',
        'description',
        'active',
    ];

    protected $casts = [
        'built_area_m2' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(StructureLevel::class);
    }
}
