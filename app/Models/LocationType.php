<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationType extends Model
{
    protected $fillable = [
        'country_id',
        'parent_id',
        'name',
        'slug',
        'level',
        'is_administrative',
        'active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_administrative' => 'boolean',
        'active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(LocationType::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(LocationType::class, 'parent_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}