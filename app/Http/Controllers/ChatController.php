<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Document;
use App\Services\RagService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(private RagService $ragService)
    {
    }

    public function index()
    {
        $documents = Document::where('user_id', auth()->id())
                              ->where('status', 'processed')
                              ->orderBy('created_at', 'desc')
                              ->get();

        $conversations = Conversation::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(10);

        return view('chat.index', compact('documents', 'conversations'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $document = Document::findOrFail($request->document_id);

        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $conversation = Conversation::create([
            'user_id'      => auth()->id(),
            'document_id'  => $document->id,
            'title'        => $document->original_name,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $messages = $conversation->messages()->orderBy('created_at')->get();

        return view('chat.show', compact('conversation', 'messages'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $result = $this->ragService->chat($conversation, $request->message);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error'   => $result['error'],
            ], 400);
        }

        $messages = $conversation->messages()->orderBy('created_at')->get();

        return response()->json([
            'success'  => true,
            'messages' => $messages,
        ]);
    }

    public function destroy(Conversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $conversation->delete();

        return redirect()->route('chat.index')
                         ->with('success', 'Conversation deleted.');
    }
}