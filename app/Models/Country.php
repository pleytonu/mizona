<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'iso2',
        'iso3',
        'phone_code',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function locationTypes(): HasMany
    {
        return $this->hasMany(LocationType::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}