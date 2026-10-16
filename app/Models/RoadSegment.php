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
            'length_meters' => 'decimal:2',
            'forward_allowed' => 'boolean',
            'reverse_allowed' => 'boolean',
            'circulation_verified_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    /**
     * Calcular y guardar automáticamente la longitud
     * cada vez que se crea o modifica el tramo.
     */
    protected static function booted(): void
    {
        static::saving(function (RoadSegment $segment) {
            $segment->length_meters =
                $segment->calculateLengthMeters();
        });
    }

    /**
     * Calcular longitud a partir de GeoJSON.
     *
     * GeoJSON utiliza coordenadas [longitud, latitud].
     * Admite LineString y MultiLineString.
     * Si no existe una geometría válida, utiliza
     * las coordenadas de inicio y término.
     */
    public function calculateLengthMeters(): ?float
    {
        $geometry = $this->geometry;

        // Admitir un GeoJSON Feature.
        if (
            is_array($geometry)
            && ($geometry['type'] ?? null) === 'Feature'
        ) {
            $geometry = $geometry['geometry'] ?? null;
        }

        if (is_array($geometry)) {
            $type = $geometry['type'] ?? null;
            $coordinates = $geometry['coordinates'] ?? null;

            if (
                $type === 'LineString'
                && is_array($coordinates)
            ) {
                $length = $this->measureLine($coordinates);

                if ($length !== null) {
                    return $length;
                }
            }

            if (
                $type === 'MultiLineString'
                && is_array($coordinates)
            ) {
                $total = 0.0;
                $found = false;

                foreach ($coordinates as $line) {
                    if (!is_array($line)) {
                        continue;
                    }

                    $lineLength = $this->measureLine($line);

                    if ($lineLength !== null) {
                        $total += $lineLength;
                        $found = true;
                    }
                }

                if ($found) {
                    return $total;
                }
            }
        }

        // Alternativa: distancia entre los extremos.
        if (
            $this->start_latitude !== null
            && $this->start_longitude !== null
            && $this->end_latitude !== null
            && $this->end_longitude !== null
        ) {
            return $this->distanceBetweenPoints(
                (float) $this->start_latitude,
                (float) $this->start_longitude,
                (float) $this->end_latitude,
                (float) $this->end_longitude
            );
        }

        return null;
    }

    /**
     * Sumar las distancias entre puntos consecutivos
     * de una línea GeoJSON.
     */
    private function measureLine(array $coordinates): ?float
    {
        if (count($coordinates) < 2) {
            return null;
        }

        $total = 0.0;
        $previous = null;
        $validPoints = 0;

        foreach ($coordinates as $point) {
            if (
                !is_array($point)
                || !isset($point[0], $point[1])
                || !is_numeric($point[0])
                || !is_numeric($point[1])
            ) {
                continue;
            }

            // GeoJSON: [longitud, latitud].
            $longitude = (float) $point[0];
            $latitude = (float) $point[1];

            if (
                abs($latitude) > 90
                || abs($longitude) > 180
            ) {
                continue;
            }

            $current = [$latitude, $longitude];

            if ($previous !== null) {
                $total += $this->distanceBetweenPoints(
                    $previous[0],
                    $previous[1],
                    $current[0],
                    $current[1]
                );
            }

            $previous = $current;
            $validPoints++;
        }

        return $validPoints >= 2 ? $total : null;
    }

    /**
     * Distancia geodésica aproximada mediante Haversine.
     * Devuelve metros.
     */
    private function distanceBetweenPoints(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371008.8;

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1)
            * cos($lat2)
            * sin($deltaLon / 2) ** 2;

        $a = min(1.0, max(0.0, $a));

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }

    /**
     * Longitud expresada en kilómetros.
     */
    public function getLengthKilometersAttribute(): ?float
    {
        if ($this->length_meters === null) {
            return null;
        }

        return (float) $this->length_meters / 1000;
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
