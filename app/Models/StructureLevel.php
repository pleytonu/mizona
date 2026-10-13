<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StructureLevel extends Model
{
    protected $fillable = [
        'structure_id',
        'level_number',
        'name',
        'description',
    ];

    protected $casts = [
        'level_number' => 'integer',
    ];

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(PropertyUnit::class);
    }
}
