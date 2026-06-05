{{-- resources/views/chat/show.blade.php --}}
@extends('layouts.terminal')
@section('title', 'CHAT_' . $conversation->id)

@section('content')

{{-- Header --}}
<div class="mb-6 flex items-start justify-between">
    <div>
        <div class="text-green-400 text-xl font-bold tracking-widest truncate max-w-lg">
            {{ $conversation->title }}
        </div>
        <div class="text-green-700 text-xs mt-1 tracking-widest">
            DOCUMENT: {{ $conversation->document->original_name }}
        </div>
    </div>
    <a href="{{ route('chat.index') }}"
       class="text-green-700 hover:text-green-400 text-xs tracking-widest transition-colors">
        [ &larr; BACK ]
    </a>
</div>

{{-- Chat Container --}}
<div class="border border-green-900 mb-6 flex flex-col" style="height: 600px;">

    {{-- Messages --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-black/30 border-b border-green-900/50">
        @forelse($messages as $msg)
            <div class="flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-2xl">
                    <div class="text-green-700 text-xs tracking-widest mb-1">
                        {{ $msg->role === 'user' ? '> YOU' : '> ASSISTANT' }}
                    </div>
                    <div class="px-4 py-3 rounded {{ $msg->role === 'user' 
                        ? 'bg-green-950 border border-green-800 text-green-400' 
                        : 'bg-black border border-green-900 text-green-600' }} text-sm">
                        {{ $msg->content }}
                    </div>

                    {{-- Show sources for assistant messages --}}
                    @if($msg->role === 'assistant' && $msg->sources)
                        <div class="mt-2 text-xs text-green-800">
                            <div class="tracking-widest mb-1">SOURCES:</div>
                            <div class="space-y-1">
                                @foreach($msg->sources as $source)
                                    <div class="text-green-700">
                                        Chunk {{ $source['chunk_index'] }} 
                                        ({{ $source['similarity'] }}% match)
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full text-center">
                <div class="text-green-900 text-xs tracking-widest">
                    NO MESSAGES YET — START ASKING QUESTIONS ABOUT YOUR DOCUMENT
                </div>
            </div>
        @endforelse
    </div>

    {{-- Input Form --}}
    <form id="messageForm" class="p-6 border-t border-green-900">
        <div class="flex gap-2">
            <input type="text" id="messageInput" placeholder="Ask a question about your document..."
                   class="flex-1 bg-black border border-green-800 text-green-400 px-4 py-2 text-xs
                          focus:border-green-600 focus:outline-none placeholder-green-900">
            <button type="submit"
                    class="px-6 py-2 border border-green-800 text-green-600 text-xs tracking-widest
                           hover:bg-green-950 hover:text-green-400 transition-colors"
                    id="sendBtn">
                [ SEND ]
            </button>
        </div>
    </form>
</div>

{{-- Delete Conversation --}}
<div class="flex gap-4">
    <form action="{{ route('chat.destroy', $conversation) }}" method="POST"
          onsubmit="return confirm('DELETE THIS CONVERSATION?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-6 py-2 border border-red-800 text-red-600 text-xs tracking-widest
                       hover:bg-red-950 hover:text-red-400 transition-colors">
            [ DELETE CONVERSATION ]
        </button>
    </form>
</div>

<script>
document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    input.value = '';
    document.getElementById('sendBtn').disabled = true;
    
    try {
        const response = await fetch('{{ route("chat.send", $conversation) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    } finally {
        document.getElementById('sendBtn').disabled = false;
    }
});
</script>

@endsection