<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    protected $fillable = [
        'document_id',
        'name',
        'type',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function relationshipsAsA(): HasMany
    {
        return $this->hasMany(Relationship::class, 'entity_a_id');
    }

    public function relationshipsAsB(): HasMany
    {
        return $this->hasMany(Relationship::class, 'entity_b_id');
    }

    public function allRelationships()
    {
        return $this->relationshipsAsA()->get()
                    ->merge($this->relationshipsAsB()->get());
    }
}