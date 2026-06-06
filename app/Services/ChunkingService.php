<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentChunk;

class ChunkingService
{
    private int $chunkSize = 1000;
    private int $overlap = 200;

    public function chunk(Document $document): void
    {
        $contents = $document->contents()->orderBy('page_number')->get();

        if ($contents->isEmpty()) {
            return;
        }

        $fullText = $contents->pluck('raw_text')->implode("\n\n");
        
        // Clean invalid UTF-8 sequences
        $fullText = mb_convert_encoding($fullText, 'UTF-8', 'UTF-8');

        $document->chunks()->delete();

        $chunks = $this->splitIntoChunks($fullText);

        foreach ($chunks as $index => $chunkText) {
            // Clean each chunk
            $cleanText = mb_convert_encoding($chunkText, 'UTF-8', 'UTF-8');
            
            DocumentChunk::create([
                'document_id' => $document->id,
                'chunk_index' => $index,
                'content'     => $cleanText,
                'token_count' => $this->estimateTokens($cleanText),
                'metadata'    => [
                    'char_count' => strlen($cleanText),
                    'word_count' => str_word_count($cleanText),
                ],
            ]);
        }
    }

    private function splitIntoChunks(string $text): array
    {
        $chunks = [];
        $textLength = strlen($text);
        $start = 0;

        while ($start < $textLength) {
            $end   = min($start + $this->chunkSize, $textLength);
            $chunk = substr($text, $start, $end - $start);

            if ($end < $textLength) {
                $lastBreak = max(
                    (int) strrpos($chunk, '. '),
                    (int) strrpos($chunk, "\n"),
                    (int) strrpos($chunk, '? '),
                    (int) strrpos($chunk, '! ')
                );

                if ($lastBreak > $this->chunkSize * 0.5) {
                    $chunk = substr($chunk, 0, $lastBreak + 1);
                } else {
                    $lastSpace = strrpos($chunk, ' ');
                    if ($lastSpace !== false) {
                        $chunk = substr($chunk, 0, $lastSpace);
                    }
                }
            }

            $trimmed = trim($chunk);
            if (!empty($trimmed)) {
                $chunks[] = $trimmed;
            }

            // Always advance by at least 1 to prevent infinite loop
            $advance = max(1, strlen($chunk) - $this->overlap);
            $start  += $advance;
        }

        return $chunks;
    }

    private function estimateTokens(string $text): int
    {
        return (int) ceil(strlen($text) / 4);
    }
}