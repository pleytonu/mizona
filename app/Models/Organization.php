<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'tax_identifier',
        'organization_type',
        'description',
        'website',
        'email',
        'phone',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function establishments(): HasMany
    {
        return $this->hasMany(Establishment::class);
    }
}
