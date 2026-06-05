{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.terminal')
@section('title', 'DASHBOARD')

@section('content')

{{-- Boot sequence --}}
<div class="mb-8 text-xs text-green-700 space-y-1">
    <div>> NEURAVAULT INTELLIGENCE PLATFORM v1.0.0</div>
    <div>> OPERATOR AUTHENTICATED: {{ auth()->user()->email }}</div>
    <div>> ALL SYSTEMS NOMINAL</div>
    <div class="text-green-400">> AWAITING COMMAND_</div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-4 gap-4 mb-8">

    <div class="border border-green-900 p-5 hover:border-green-600 transition-colors">
        <div class="text-green-700 text-xs tracking-widest mb-2">DOCUMENTS INDEXED</div>
        <div class="text-green-400 text-3xl font-bold">{{ str_pad($documentCount, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="text-green-800 text-xs mt-2">
            @if($documentCount > 0)
                {{ $documentCount }} FILE(S) STORED
            @else
                NO DOCUMENTS YET
            @endif
        </div>
    </div>

    <div class="border border-green-900 p-5 hover:border-green-600 transition-colors">
        <div class="text-green-700 text-xs tracking-widest mb-2">STORAGE USED</div>
        <div class="text-green-400 text-3xl font-bold">
            @if($totalStorageUsed >= 1048576)
                {{ number_format($totalStorageUsed / 1048576, 1) }}
                <span class="text-lg">MB</span>
            @elseif($totalStorageUsed >= 1024)
                {{ number_format($totalStorageUsed / 1024, 1) }}
                <span class="text-lg">KB</span>
            @else
                {{ $totalStorageUsed }}
                <span class="text-lg">B</span>
            @endif
        </div>
        <div class="text-green-800 text-xs mt-2">LOCAL DISK</div>
    </div>

    <div class="border border-green-900 p-5 hover:border-green-600 transition-colors">
        <div class="text-green-700 text-xs tracking-widest mb-2">CHUNKS PROCESSED</div>
        <div class="text-green-400 text-3xl font-bold">{{ str_pad($totalChunks, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="text-green-800 text-xs mt-2">
            @if($totalChunks > 0)
                {{ $totalChunks }} CHUNK(S) INDEXED
            @else
                NO CHUNKS YET
            @endif
        </div>
    </div>

    <div class="border border-green-900 p-5 hover:border-green-600 transition-colors">
        <div class="text-green-700 text-xs tracking-widest mb-2">RAG CONVERSATIONS</div>
        <div class="text-green-400 text-3xl font-bold">{{ str_pad(App\Models\Conversation::where('user_id', auth()->id())->count(), 2, '0', STR_PAD_LEFT) }}</div>
        <div class="text-green-800 text-xs mt-2">
            @if(App\Models\Conversation::where('user_id', auth()->id())->count() > 0)
                {{ App\Models\Conversation::where('user_id', auth()->id())->count() }} ACTIVE
            @else
                NO CHATS YET
            @endif
        </div>
    </div>

</div>

{{-- Module Status + System Log --}}
<div class="grid grid-cols-2 gap-6 mb-6">

    {{-- Module Status Table --}}
    <div class="border border-green-900 p-6">
        <div class="text-green-600 text-xs tracking-widest mb-4">// MODULE STATUS</div>
        <div class="space-y-3 text-sm">
            @foreach([
                ['FOUNDATION',       '01', 'COMPLETE'],
                ['DOCUMENT MGMT',    '02', 'COMPLETE'],
                ['PDF PROCESSING',   '03', 'COMPLETE'],
                ['CHUNKING ENGINE',  '04', 'COMPLETE'],
                ['VECTOR SEARCH',    '05', 'COMPLETE'],
                ['RAG CHAT',         '06', 'COMPLETE'],
                ['SECURITY SCANNER', '07', 'PENDING'],
                ['KNOWLEDGE GRAPH',  '08', 'PENDING'],
                ['ANALYTICS',        '09', 'PENDING'],
            ] as [$name, $num, $status])
            <div class="flex items-center justify-between">
                <span class="text-green-700">MOD-{{ $num }} {{ $name }}</span>
                <span class="{{ $status === 'COMPLETE' ? 'text-green-400' : 'text-green-900' }} tracking-widest text-xs">
                    {{ $status === 'COMPLETE' ? '[■ ONLINE]' : '[□ OFFLINE]' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- System Log --}}
    <div class="border border-green-900 p-6">
        <div class="text-green-600 text-xs tracking-widest mb-4">// SYSTEM LOG</div>
        <div class="space-y-2 text-xs font-mono">
            <div class="text-green-800">[{{ now()->format('H:i:s') }}] AUTH — Operator session started</div>
            <div class="text-green-800">[{{ now()->format('H:i:s') }}] SYS  — Database connection OK</div>
            <div class="text-green-800">[{{ now()->format('H:i:s') }}] SYS  — Storage driver: local</div>
            <div class="text-green-400">[{{ now()->format('H:i:s') }}] MOD2 — Document vault online</div>
            <div class="text-green-400">[{{ now()->format('H:i:s') }}] MOD3 — PDF processing online</div>
            <div class="text-green-400">[{{ now()->format('H:i:s') }}] MOD4 — {{ $totalChunks }} chunk(s) indexed</div>
            <div class="text-green-400">[{{ now()->format('H:i:s') }}] MOD5 — Vector search online</div>
            <div class="text-green-400">[{{ now()->format('H:i:s') }}] MOD6 — RAG chat engine online</div>
            <div class="text-green-700">[{{ now()->format('H:i:s') }}] WARN — Security scanner offline</div>
            <div class="text-green-700">[{{ now()->format('H:i:s') }}] WARN — Knowledge graph not initialized</div>
        </div>
    </div>

</div>

{{-- Recent Documents --}}
<div class="border border-green-900">
    <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
        // RECENT_DOCUMENTS
    </div>

    @if($recentDocuments->count() > 0)
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-green-900 text-green-700 tracking-widest">
                    <th class="px-6 py-3 text-left">NAME</th>
                    <th class="px-6 py-3 text-left">SIZE</th>
                    <th class="px-6 py-3 text-left">STATUS</th>
                    <th class="px-6 py-3 text-left">UPLOADED</th>
                    <th class="px-6 py-3 text-left">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentDocuments as $doc)
                    <tr class="border-b border-green-900/50 hover:bg-green-950/20 transition-colors">
                        <td class="px-6 py-3 text-green-400 max-w-xs truncate">{{ $doc->original_name }}</td>
                        <td class="px-6 py-3 text-green-600">{{ $doc->formatted_size }}</td>
                        <td class="px-6 py-3 {{ $doc->status_color }} tracking-widest">{{ strtoupper($doc->status) }}</td>
                        <td class="px-6 py-3 text-green-700">{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('documents.show', $doc) }}"
                               class="text-green-600 hover:text-green-400 tracking-widest transition-colors">
                                [ VIEW ]
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-3 border-t border-green-900">
            <a href="{{ route('documents.index') }}"
               class="text-green-700 hover:text-green-400 text-xs tracking-widest transition-colors">
                &gt; VIEW ALL DOCUMENTS →
            </a>
        </div>
    @else
        <div class="px-6 py-8 text-center">
            <div class="text-green-900 text-xs tracking-widest">&gt; NO DOCUMENTS UPLOADED YET</div>
            <a href="{{ route('documents.index') }}"
               class="inline-block mt-3 text-green-700 hover:text-green-400 text-xs tracking-widest transition-colors">
                &gt; GO TO DOCUMENT VAULT →
            </a>
        </div>
    @endif
</div>

@endsection