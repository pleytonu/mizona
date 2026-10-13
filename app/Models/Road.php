<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Road extends Model
{
    protected $fillable = [
        'road_type',
        'current_name',
        'official_code',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /**
     * Tramos de esta vía en distintas comunas.
     */
    public function segments(): HasMany
    {
        return $this->hasMany(RoadSegment::class);
    }

    /**
     * Historial de nombres y cambios oficiales.
     */
    public function nameHistories(): HasMany
    {
        return $this->hasMany(RoadNameHistory::class);
    }

    /**
     * Direcciones asociadas a esta vía.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
