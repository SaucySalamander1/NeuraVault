<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentEmbedding extends Model
{
    protected $fillable = [
        'document_id',
        'chunk_id',
        'embedding',
        'model',
        'dimensions',
    ];

    protected $casts = [
        'embedding'  => 'array',
        'dimensions' => 'integer',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function chunk(): BelongsTo
    {
        return $this->belongsTo(DocumentChunk::class);
    }
}