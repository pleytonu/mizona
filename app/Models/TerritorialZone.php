<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TerritorialZone extends Model
{
    protected $fillable = [
        'location_id',
        'parent_id',
        'name',
        'slug',
        'zone_type',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            TerritorialZone::class,
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            TerritorialZone::class,
            'parent_id'
        );
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
