<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadNameHistory extends Model
{
    protected $fillable = [
        'road_id',
        'previous_name',
        'new_name',
        'effective_from',
        'effective_to',
        'change_type',
        'reason',
        'document_type',
        'document_number',
        'document_date',
        'document_reference',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'effective_from' => 'date',
            'effective_to' => 'date',
            'document_date' => 'date',
        ];
    }

    /**
     * Vía cuyo nombre se está documentando.
     */
    public function road(): BelongsTo
    {
        return $this->belongsTo(Road::class);
    }
}
