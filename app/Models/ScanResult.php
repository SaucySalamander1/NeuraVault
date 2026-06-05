<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanResult extends Model
{
    protected $fillable = [
        'document_id',
        'finding_type',
        'value_masked',
        'location',
        'severity',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'text-red-500',
            'high'     => 'text-orange-500',
            'medium'   => 'text-yellow-500',
            'low'      => 'text-blue-500',
            default    => 'text-gray-500',
        };
    }

    public function getSeverityBgAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'bg-red-950 border-red-700',
            'high'     => 'bg-orange-950 border-orange-700',
            'medium'   => 'bg-yellow-950 border-yellow-700',
            'low'      => 'bg-blue-950 border-blue-700',
            default    => 'bg-gray-950 border-gray-700',
        };
    }
}