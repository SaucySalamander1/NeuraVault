<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Jobs\ProcessDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalSize = Document::where('user_id', auth()->id())->sum('size');
        $totalDocs = Document::where('user_id', auth()->id())->count();

        return view('documents.index', compact('documents', 'totalSize', 'totalDocs'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,txt|max:10240',
        ]);

        $file       = $request->file('file');
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path       = Storage::disk('local')
                             ->putFileAs('documents/' . auth()->id(), $file, $storedName);

        $document = Document::create([
            'user_id'       => auth()->id(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $storedName,
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'status'        => 'pending',
        ]);

        ProcessDocument::dispatch($document);

        return redirect()->route('documents.index')
                         ->with('success', 'Document uploaded. Processing started.');
    }

    public function show(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $contents        = $document->contents()->orderBy('page_number')->get();
        $chunks          = $document->chunks()->count();
        $embeddingsCount = $document->embeddings()->count();
        $scanResults     = $document->scanResults()->orderBy('severity', 'desc')->get();
        $entities        = $document->entities()->get();
        $relationships   = $document->relationships()->with(['entityA', 'entityB'])->get();

        return view('documents.show', compact(
            'document',
            'contents',
            'chunks',
            'embeddingsCount',
            'scanResults',
            'entities',
            'relationships'
        ));
    }

    public function destroy(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('local')->delete($document->path);
        $document->delete();

        return redirect()->route('documents.index')
                         ->with('success', 'Document deleted.');
    }
}