{{-- resources/views/chat/index.blade.php --}}
@extends('layouts.terminal')
@section('title', 'RAG_CHAT')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-6 px-4 py-3 border border-green-500 bg-green-950 text-green-400 text-xs tracking-widest">
        &gt; {{ session('success') }}
    </div>
@endif

{{-- Page Header --}}
<div class="mb-8">
    <div class="text-green-400 text-xl font-bold tracking-widest">RAG CHAT INTERFACE</div>
    <div class="text-green-700 text-xs mt-1 tracking-widest">// RETRIEVAL-AUGMENTED GENERATION</div>
</div>

{{-- Start New Conversation --}}
@if($documents->count() > 0)
    <div class="border border-green-900 mb-6">
        <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
            &gt; START_NEW_CONVERSATION
        </div>
        <div class="p-6">
            <form action="{{ route('chat.create') }}" method="POST" class="flex items-center gap-4">
                @csrf
                <select name="document_id" required
                        class="flex-1 bg-black border border-green-800 text-green-400 px-4 py-2 text-xs
                               focus:border-green-600 focus:outline-none">
                    <option value="">SELECT A DOCUMENT...</option>
                    @foreach($documents as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->original_name }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-6 py-2 border border-green-800 text-green-600 text-xs tracking-widest
                               hover:bg-green-950 hover:text-green-400 transition-colors">
                    [ START CHAT ]
                </button>
            </form>
        </div>
    </div>
@else
    <div class="border border-yellow-900 mb-6 px-6 py-6 text-center">
        <div class="text-yellow-600 text-xs tracking-widest">
            NO DOCUMENTS AVAILABLE FOR CHAT
        </div>
        <div class="text-yellow-800 text-xs mt-2">
            <a href="{{ route('documents.index') }}" class="hover:text-yellow-600">Upload a document first →</a>
        </div>
    </div>
@endif

{{-- Conversation History --}}
@if($conversations->count() > 0)
    <div class="border border-green-900">
        <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
            &gt; CONVERSATION_HISTORY
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-green-900 text-green-700 tracking-widest">
                    <th class="px-6 py-3 text-left">TITLE</th>
                    <th class="px-6 py-3 text-left">DOCUMENT</th>
                    <th class="px-6 py-3 text-left">MESSAGES</th>
                    <th class="px-6 py-3 text-left">CREATED</th>
                    <th class="px-6 py-3 text-left">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conv)
                    <tr class="border-b border-green-900/50 hover:bg-green-950/20 transition-colors">
                        <td class="px-6 py-3 text-green-400 max-w-xs truncate">
                            {{ $conv->title }}
                        </td>
                        <td class="px-6 py-3 text-green-600 text-xs">
                            {{ $conv->document->original_name }}
                        </td>
                        <td class="px-6 py-3 text-green-700">
                            {{ $conv->messages()->count() }}
                        </td>
                        <td class="px-6 py-3 text-green-700 text-xs">
                            {{ $conv->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('chat.show', $conv) }}"
                               class="text-green-600 hover:text-green-400 tracking-widest text-xs transition-colors">
                                [ VIEW ]
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-6 py-3 border-t border-green-900">
            {{ $conversations->links() }}
        </div>
    </div>
@else
    <div class="border border-green-900 px-6 py-8 text-center">
        <div class="text-green-900 text-xs tracking-widest">
            NO CONVERSATIONS YET
        </div>
        <div class="text-green-800 text-xs mt-2">
            START A CONVERSATION ABOVE TO BEGIN CHATTING WITH YOUR DOCUMENTS
        </div>
    </div>
@endif

@endsection