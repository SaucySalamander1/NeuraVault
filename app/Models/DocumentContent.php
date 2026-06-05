<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentContent extends Model
{
    protected $fillable = [
        'document_id',
        'page_number',
        'raw_text',
        'word_count',
    ];

    protected $casts = [
        'page_number' => 'integer',
        'word_count'  => 'integer',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentContent::class);
    }
}