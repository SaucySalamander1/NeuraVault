<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relationship extends Model
{
    protected $fillable = [
        'document_id',
        'entity_a_id',
        'entity_b_id',
        'relationship_type',
        'strength',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function entityA(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_a_id');
    }

    public function entityB(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_b_id');
    }
}