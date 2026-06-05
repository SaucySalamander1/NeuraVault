<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Relationship;

class GraphController extends Controller
{
    public function index()
    {
        $documentIds = auth()->user()->documents()->pluck('id')->toArray();

        $entities = Entity::whereIn('document_id', $documentIds)->get();

        $relationships = Relationship::whereIn('document_id', $documentIds)
                                    ->with(['entityA', 'entityB', 'document'])
                                    ->get();

        $stats = [
            'total_entities' => $entities->count(),
            'people' => $entities->where('type', 'person')->count(),
            'companies' => $entities->where('type', 'company')->count(),
            'locations' => $entities->where('type', 'location')->count(),
            'concepts' => $entities->where('type', 'concept')->count(),
            'total_relationships' => $relationships->count(),
        ];

        return view('graph.index', compact('entities', 'relationships', 'stats'));
    }
}