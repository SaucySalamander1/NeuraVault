<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\PdfProcessingService;
use App\Services\ChunkingService;
use App\Services\EmbeddingService;
use App\Services\SecurityScanService;
use App\Services\KnowledgeGraphService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(public Document $document)
    {
        //
    }

    public function handle(
        PdfProcessingService $pdfService,
        ChunkingService $chunkingService,
        EmbeddingService $embeddingService,
        SecurityScanService $securityScanService,
        KnowledgeGraphService $knowledgeGraphService
    ): void {
        $this->document->update(['status' => 'processing']);

        try {
            // Step 1: Extract text
            $pdfService->process($this->document);

            // Step 2: Chunk the text
            $chunkingService->chunk($this->document);

            // Step 3: Generate embeddings
            $embeddingService->embedDocument($this->document);

            // Step 4: Security scan
            $securityScanService->scan($this->document);

            // Step 5: Build knowledge graph
            $knowledgeGraphService->buildGraph($this->document);

            Log::info('Document fully processed', [
                'document_id'   => $this->document->id,
                'chunks'        => $this->document->chunks()->count(),
                'embeddings'    => $this->document->embeddings()->count(),
                'scan_findings' => $this->document->scanResults()->count(),
                'entities'      => $this->document->entities()->count(),
                'relationships' => $this->document->relationships()->count(),
            ]);

        } catch (\Exception $e) {
            $this->document->update(['status' => 'failed']);
            Log::error('Document processing failed', [
                'document_id' => $this->document->id,
                'error'       => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Exception $exception): void
    {
        $this->document->update(['status' => 'failed']);
        Log::error('ProcessDocument job failed permanently', [
            'document_id' => $this->document->id,
            'error'       => $exception->getMessage(),
        ]);
    }
}