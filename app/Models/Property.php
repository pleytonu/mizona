<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $fillable = [
        'territorial_zone_id',
        'name',
        'slug',
        'cadastral_code',
        'property_type',
        'area_m2',
        'description',
        'active',
    ];

    protected $casts = [
        'area_m2' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function territorialZone(): BelongsTo
    {
        return $this->belongsTo(TerritorialZone::class);
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(
            Address::class,
            'address_property'
        )->withPivot([
            'relation_type',
            'is_primary',
            'notes',
        ])->withTimestamps();
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }
}
