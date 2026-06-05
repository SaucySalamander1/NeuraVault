<?php

namespace App\Services;

use App\Models\Document;
use App\Models\ScanResult;
use Illuminate\Support\Facades\Log;

class SecurityScanService
{
    private array $patterns = [
        'email' => [
            'pattern'  => '/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/i',
            'severity' => 'high',
        ],
        'phone' => [
            'pattern'  => '/(\+?1[-.\s]?)?(\()?[0-9]{3}(\))?[-.\s]?[0-9]{3}[-.\s]?[0-9]{4}/',
            'severity' => 'medium',
        ],
        'ssn' => [
            'pattern'  => '/\d{3}-\d{2}-\d{4}/',
            'severity' => 'critical',
        ],
        'credit_card' => [
            'pattern'  => '/\d{4}[-\s]?\d{4}[-\s]?\d{4}[-\s]?\d{4}/',
            'severity' => 'critical',
        ],
        'api_key' => [
            'pattern'  => '/(sk_test_|sk_live_|pk_test_|pk_live_)[A-Za-z0-9_]{10,}/i',
            'severity' => 'critical',
        ],
        'password' => [
            'pattern'  => '/password\s*[=:]\s*[^\s]+/i',
            'severity' => 'critical',
        ],
        'url' => [
            'pattern'  => '/(https?:\/\/[^\s]+)/i',
            'severity' => 'high',
        ],
    ];

    public function scan(Document $document): void
    {
        // Clear existing scan results
        $document->scanResults()->delete();

        // Get all document content
        $contents = $document->contents()->orderBy('page_number')->get();

        if ($contents->isEmpty()) {
            return;
        }

        foreach ($contents as $content) {
            $text = $content->raw_text;
            $pageNumber = $content->page_number;

            foreach ($this->patterns as $findingType => $config) {
                if (preg_match_all($config['pattern'], $text, $matches)) {
                    foreach ($matches[0] as $match) {
                        $maskedValue = $this->maskValue($match, $findingType);

                        ScanResult::create([
                            'document_id'  => $document->id,
                            'finding_type' => $findingType,
                            'value_masked' => $maskedValue,
                            'location'     => "Page {$pageNumber}",
                            'severity'     => $config['severity'],
                        ]);
                    }
                }
            }
        }

        Log::info('Security scan completed', [
            'document_id'   => $document->id,
            'findings_count' => $document->scanResults()->count(),
        ]);
    }

    private function maskValue(string $value, string $type): string
    {
        return match($type) {
            'email'        => substr($value, 0, 2) . '***@***.' . substr($value, strrpos($value, '.') + 1),
            'phone'        => '***-***-' . substr(preg_replace('/\D/', '', $value), -4),
            'ssn'          => '***-**-' . substr($value, -4),
            'credit_card'  => '****-****-****-' . substr(preg_replace('/\D/', '', $value), -4),
            'api_key'      => substr($value, 0, 10) . '***' . substr($value, -5),
            'password'     => '***MASKED***',
            'url'          => '***URL_MASKED***',
            default        => '***MASKED***',
        };
    }
}