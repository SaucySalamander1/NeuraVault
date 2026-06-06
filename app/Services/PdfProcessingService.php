<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentContent;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class PdfProcessingService
{
    public function process(Document $document): void
    {
        $fullPath = Storage::disk('local')->path($document->path);

        if ($document->mime_type === 'application/pdf') {
            $this->processPdf($document, $fullPath);
        } else {
            $this->processTxt($document, $fullPath);
        }
    }

    private function processPdf(Document $document, string $fullPath): void
    {
        $parser = new Parser();
        $pdf    = $parser->parseFile($fullPath);
        $pages  = $pdf->getPages();

        // Clear any previous contents
        $document->contents()->delete();

        $pageNumber = 1;
        foreach ($pages as $page) {
            $text = $page->getText();
            $text = $this->cleanText($text);

            if (!empty(trim($text))) {
                DocumentContent::create([
                    'document_id' => $document->id,
                    'page_number' => $pageNumber,
                    'raw_text'    => $text,
                    'word_count'  => str_word_count($text),
                ]);
            }

            $pageNumber++;
        }

        $document->update([
            'page_count' => count($pages),
            'status'     => 'processed',
        ]);
    }

    private function processTxt(Document $document, string $fullPath): void
    {
        $text = file_get_contents($fullPath);
        $text = $this->cleanText($text);

        // Clear any previous contents
        $document->contents()->delete();

        DocumentContent::create([
            'document_id' => $document->id,
            'page_number' => 1,
            'raw_text'    => $text,
            'word_count'  => str_word_count($text),
        ]);

        $document->update([
            'page_count' => 1,
            'status'     => 'processed',
        ]);
    }

    private function cleanText(string $text): string
    {
        // Clean invalid UTF-8 sequences
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Remove null bytes
        $text = str_replace("\0", '', $text);

        // Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Remove excessive whitespace lines
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        // Trim
        return trim($text);
    }
}