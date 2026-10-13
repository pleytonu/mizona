<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadSegment extends Model
{
    protected $fillable = [
        'road_id',
        'location_id',
        'segment_name',
        'valid_from',
        'valid_to',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'geometry',
        'direction_type',
        'forward_allowed',
        'reverse_allowed',
        'circulation_source',
        'circulation_verified_at',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_to' => 'date',
            'start_latitude' => 'decimal:7',
            'start_longitude' => 'decimal:7',
            'end_latitude' => 'decimal:7',
            'end_longitude' => 'decimal:7',
            'geometry' => 'array',
            'forward_allowed' => 'boolean',
            'reverse_allowed' => 'boolean',
            'circulation_verified_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    /**
     * Vía a la que pertenece este tramo.
     */
    public function road(): BelongsTo
    {
        return $this->belongsTo(Road::class);
    }

    /**
     * Comuna o ubicación territorial del tramo.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
