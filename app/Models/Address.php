<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    protected $fillable = [
        'location_id',
        'territorial_zone_id',
        'street_name',
        'street_number',
        'unit',
        'postal_code',
        'reference',
        'latitude',
        'longitude',
        'road_id',
        'road_segment_id',
        'active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'active' => 'boolean',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function territorialZone(): BelongsTo
    {
        return $this->belongsTo(TerritorialZone::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(
            Property::class,
            'address_property'
        )->withPivot([
            'relation_type',
            'is_primary',
            'notes',
        ])->withTimestamps();
    }
    
    /**
     * Vía asociada a esta dirección.
     */
    public function road(): BelongsTo
    {
        return $this->belongsTo(Road::class);
    }

    /**
     * Tramo específico de la vía.
     */
    public function roadSegment(): BelongsTo
    {
        return $this->belongsTo(RoadSegment::class);
    }
}
