<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentChunk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $documentCount    = Document::where('user_id', $userId)->count();
        $totalStorageUsed = Document::where('user_id', $userId)->sum('size');
        $recentDocuments  = Document::where('user_id', $userId)
                                    ->orderBy('created_at', 'desc')
                                    ->take(3)
                                    ->get();
        $totalChunks      = DocumentChunk::whereHas('document', function ($q) use ($userId) {
                                $q->where('user_id', $userId);
                            })->count();

        return view('dashboard.index', compact(
            'documentCount',
            'totalStorageUsed',
            'recentDocuments',
            'totalChunks'
        ));
    }
}