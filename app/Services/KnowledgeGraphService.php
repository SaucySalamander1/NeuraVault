<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Entity;
use App\Models\Relationship;
use Illuminate\Support\Facades\Log;

class KnowledgeGraphService
{
    private array $commonCompanies = [
        'Google', 'Apple', 'Microsoft', 'Amazon', 'Facebook', 'Meta', 'Netflix', 'Tesla', 'IBM', 'Oracle',
        'Uber', 'Airbnb', 'Stripe', 'Spotify', 'Adobe', 'Salesforce', 'Slack', 'Discord', 'GitHub', 'GitLab'
    ];

    private array $commonLocations = [
        'New York', 'London', 'Tokyo', 'Paris', 'Singapore', 'Berlin', 'Mumbai', 'Sydney', 'Toronto', 'Dubai',
        'USA', 'UK', 'Japan', 'Germany', 'India', 'China', 'France', 'Canada', 'Australia', 'Brazil'
    ];

    public function buildGraph(Document $document): void
    {
        $document->entities()->delete();
        $document->relationships()->delete();

        $contents = $document->contents()->orderBy('page_number')->get();

        if ($contents->isEmpty()) {
            return;
        }

        $fullText = $contents->pluck('raw_text')->implode(' ');

        $entities = $this->extractEntities($fullText, $document);

        if ($entities->count() > 1) {
            $this->extractRelationships($fullText, $entities, $document);
        }

        Log::info('Knowledge graph built', [
            'document_id' => $document->id,
            'entities'    => $entities->count(),
            'relationships' => $document->relationships()->count(),
        ]);
    }

    private function extractEntities(string $text, Document $document)
    {
        $entities = collect();

        foreach ($this->commonCompanies as $company) {
            if (stripos($text, $company) !== false) {
                $entity = Entity::firstOrCreate(
                    [
                        'document_id' => $document->id,
                        'name'        => $company,
                        'type'        => 'company',
                    ],
                    [
                        'description' => "Company mentioned in document",
                    ]
                );
                $entities->push($entity);
            }
        }

        foreach ($this->commonLocations as $location) {
            if (stripos($text, $location) !== false) {
                $entity = Entity::firstOrCreate(
                    [
                        'document_id' => $document->id,
                        'name'        => $location,
                        'type'        => 'location',
                    ],
                    [
                        'description' => "Location mentioned in document",
                    ]
                );
                $entities->push($entity);
            }
        }

        $personPattern = '/\b(Mr|Ms|Dr|Prof|CEO|founder)\s+([A-Z][a-z]+\s+[A-Z][a-z]+)/i';
        if (preg_match_all($personPattern, $text, $matches)) {
            foreach ($matches[2] as $person) {
                $entity = Entity::firstOrCreate(
                    [
                        'document_id' => $document->id,
                        'name'        => trim($person),
                        'type'        => 'person',
                    ],
                    [
                        'description' => "Person mentioned in document",
                    ]
                );
                $entities->push($entity);
            }
        }

        $conceptPattern = '/\b[A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\b/';
        if (preg_match_all($conceptPattern, $text, $matches)) {
            $wordFreq = array_count_values($matches[0]);
            arsort($wordFreq);

            foreach (array_slice($wordFreq, 0, 5) as $concept => $count) {
                if ($count >= 2 && strlen($concept) > 3 && !in_array($concept, $this->commonCompanies) && !in_array($concept, $this->commonLocations)) {
                    $entity = Entity::firstOrCreate(
                        [
                            'document_id' => $document->id,
                            'name'        => $concept,
                            'type'        => 'concept',
                        ],
                        [
                            'description' => "Concept mentioned {$count} times",
                            'metadata'    => ['frequency' => $count],
                        ]
                    );
                    $entities->push($entity);
                }
            }
        }

        return $entities;
    }

    private function extractRelationships(string $text, $entities, Document $document): void
    {
        $patterns = [
            'works_at' => '/(\w+\s+\w+)\s+(?:works|worked)\s+at\s+(\w+)/i',
            'located_in' => '/(\w+)\s+(?:is\s+)?located\s+in\s+(\w+)/i',
            'founded_by' => '/(\w+)\s+(?:was\s+)?founded\s+by\s+(\w+)/i',
            'is_a' => '/(\w+\s+\w+)\s+is\s+(?:a|the)\s+(\w+)/i',
            'member_of' => '/(\w+\s+\w+)\s+(?:is\s+a\s+)?member\s+of\s+(\w+)/i',
        ];

        foreach ($patterns as $relType => $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                for ($i = 0; $i < count($matches[1]); $i++) {
                    $entityAName = trim($matches[1][$i]);
                    $entityBName = trim($matches[2][$i]);

                    $entityA = $entities->firstWhere('name', $entityAName);
                    $entityB = $entities->firstWhere('name', $entityBName);

                    if ($entityA && $entityB && $entityA->id !== $entityB->id) {
                        Relationship::firstOrCreate(
                            [
                                'document_id'      => $document->id,
                                'entity_a_id'      => $entityA->id,
                                'entity_b_id'      => $entityB->id,
                                'relationship_type' => $relType,
                            ]
                        );
                    }
                }
            }
        }
    }
}