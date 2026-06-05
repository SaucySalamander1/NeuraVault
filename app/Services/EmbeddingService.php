<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentChunk;
use App\Models\DocumentEmbedding;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbeddingService
{
    private string $apiKey;
    private string $model = 'sentence-transformers/all-MiniLM-L6-v2';
    private int $dimensions = 384;

    public function __construct()
    {
        $this->apiKey = config('services.huggingface.api_key');
    }

    public function embedDocument(Document $document): void
    {
        $chunks = $document->chunks()->get();

        if ($chunks->isEmpty()) {
            return;
        }

        $document->embeddings()->delete();

        foreach ($chunks as $chunk) {
            try {
                $embedding = $this->generateEmbedding($chunk->content);

                if ($embedding) {
                    DocumentEmbedding::create([
                        'document_id' => $document->id,
                        'chunk_id'    => $chunk->id,
                        'embedding'   => $embedding,
                        'model'       => $this->model,
                        'dimensions'  => $this->dimensions,
                    ]);
                }

                usleep(100000);

            } catch (\Exception $e) {
                Log::error('Embedding generation failed for chunk', [
                    'chunk_id' => $chunk->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }
    }

    public function generateEmbedding(string $text): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(30)->post(
           // CORRECT
        'https://router.huggingface.co/hf-inference/models/' . $this->model . '/pipeline/feature-extraction',
            ['inputs' => $text, 'options' => ['wait_for_model' => true]]
        );

        if ($response->failed()) {
            Log::error('HuggingFace API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        }

        $data = $response->json();

        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            return $data[0];
        }

        return is_array($data) ? $data : null;
    }

    public function searchSimilar(string $query, int $documentId, int $limit = 5): array
    {
        $queryEmbedding = $this->generateEmbedding($query);

        if (!$queryEmbedding) {
            return [];
        }

        $embeddings = DocumentEmbedding::where('document_id', $documentId)
                                       ->with('chunk')
                                       ->get();

        if ($embeddings->isEmpty()) {
            return [];
        }

        $results = [];
        foreach ($embeddings as $embedding) {
            $similarity = $this->cosineSimilarity($queryEmbedding, $embedding->embedding);
            $results[]  = [
                'chunk'      => $embedding->chunk,
                'similarity' => $similarity,
            ];
        }

        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        return array_slice($results, 0, $limit);
    }

    private function cosineSimilarity(array $vectorA, array $vectorB): float
    {
        $dotProduct = 0.0;
        $magnitudeA = 0.0;
        $magnitudeB = 0.0;
        $length     = min(count($vectorA), count($vectorB));

        for ($i = 0; $i < $length; $i++) {
            $dotProduct += $vectorA[$i] * $vectorB[$i];
            $magnitudeA += $vectorA[$i] ** 2;
            $magnitudeB += $vectorB[$i] ** 2;
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }
}