<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'page_count',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'size'     => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(DocumentContent::class);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'text-yellow-400',
            'processing' => 'text-blue-400',
            'processed'  => 'text-green-400',
            'failed'     => 'text-red-400',
            default      => 'text-gray-400',
        };
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(DocumentChunk::class);
    }

    public function embeddings(): HasMany
    {
        return $this->hasMany(DocumentEmbedding::class);
    }

    public function conversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function scanResults(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ScanResult::class);
    }

    public function entities(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Entity::class);
}

    public function relationships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Relationship::class);
    }
}