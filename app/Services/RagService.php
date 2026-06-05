<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Document;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RagService
{
    private EmbeddingService $embeddingService;
    private string $groqApiKey;
    private string $model = 'llama-3.3-70b-versatile';

    public function __construct(EmbeddingService $embeddingService)
    {
        $this->embeddingService = $embeddingService;
        $this->groqApiKey       = config('services.groq.api_key');
    }

    public function chat(Conversation $conversation, string $userMessage): array
    {
        // Step 1: Find similar chunks using vector search
        $similarChunks = $this->embeddingService->searchSimilar(
            $userMessage,
            $conversation->document_id,
            limit: 5
        );

        // Step 2: Extract chunk content and metadata
        $context = $this->buildContext($similarChunks);

        // Step 3: Call Groq API with context
        $response = $this->callGroqApi($userMessage, $context);

        if (!$response['success']) {
            return [
                'success' => false,
                'error'   => $response['error'],
            ];
        }

        // Step 4: Save messages to database
        Message::create([
            'conversation_id' => $conversation->id,
            'role'            => 'user',
            'content'         => $userMessage,
            'sources'         => null,
            'token_count'     => $this->estimateTokens($userMessage),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'role'            => 'assistant',
            'content'         => $response['content'],
            'sources'         => $this->formatSources($similarChunks),
            'token_count'     => $response['token_count'],
        ]);

        // Update conversation title from first user message
        if ($conversation->messages()->where('role', 'user')->count() === 1) {
            $title = substr($userMessage, 0, 50);
            $conversation->update(['title' => $title]);
        }

        return [
            'success' => true,
            'content' => $response['content'],
            'sources' => $this->formatSources($similarChunks),
        ];
    }

    private function buildContext(array $similarChunks): string
    {
        if (empty($similarChunks)) {
            return "No relevant content found in the document.";
        }

        $contextParts = [];
        foreach ($similarChunks as $item) {
            $chunk = $item['chunk'];
            $contextParts[] = "Page {$chunk->document_id}: {$chunk->content}";
        }

        return "Based on the document, here is relevant information:\n\n" . implode("\n\n", $contextParts);
    }

    private function callGroqApi(string $userMessage, string $context): array
    {
        $systemPrompt = "You are a helpful AI assistant that answers questions about documents. Answer based ONLY on the provided document content. If information is not in the document, say so clearly. Be concise and cite which parts of the document you're referencing.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->groqApiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model'       => $this->model,
                    'max_tokens'  => 1024,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role'    => 'user',
                            'content' => $context . "\n\nUser question: " . $userMessage,
                        ],
                    ],
                ]
            );

            if ($response->failed()) {
                Log::error('Groq API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'success'     => false,
                    'error'       => 'Failed to get response from AI service',
                ];
            }

            $data = $response->json();

            return [
                'success'     => true,
                'content'     => $data['choices'][0]['message']['content'],
                'token_count' => $data['usage']['completion_tokens'] ?? 0,
            ];

        } catch (\Exception $e) {
            Log::error('Groq API exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    private function formatSources(array $similarChunks): array
    {
        $sources = [];

        foreach ($similarChunks as $item) {
            $chunk      = $item['chunk'];
            $similarity = round($item['similarity'] * 100, 1);

            $sources[] = [
                'chunk_index' => $chunk->chunk_index,
                'similarity'  => $similarity,
                'preview'     => substr($chunk->content, 0, 100) . '...',
            ];
        }

        return $sources;
    }

    private function estimateTokens(string $text): int
    {
        return (int) ceil(strlen($text) / 4);
    }
}