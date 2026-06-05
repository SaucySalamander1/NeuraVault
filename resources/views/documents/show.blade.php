{{-- resources/views/documents/show.blade.php --}}
@extends('layouts.terminal')

@section('title', 'DOCUMENT_' . strtoupper(pathinfo($document->original_name, PATHINFO_FILENAME)))

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-6 px-4 py-3 border border-green-500 bg-green-950 text-green-400 text-xs tracking-widest">
        &gt; {{ session('success') }}
    </div>
@endif

{{-- Page Header --}}
<div class="mb-8 flex items-start justify-between">
    <div>
        <div class="text-green-400 text-xl font-bold tracking-widest truncate max-w-lg">
            {{ strtoupper($document->original_name) }}
        </div>
        <div class="text-green-700 text-xs mt-1 tracking-widest">// DOCUMENT METADATA</div>
    </div>
    <a href="{{ route('documents.index') }}"
       class="text-green-700 hover:text-green-400 text-xs tracking-widest transition-colors">
        [ &larr; BACK TO VAULT ]
    </a>
</div>

{{-- Metadata Card --}}
<div class="border border-green-900 mb-6">
    <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
        &gt; FILE_METADATA
    </div>
    <div class="p-6 grid grid-cols-2 gap-6 text-xs">
        <div>
            <div class="text-green-700 tracking-widest">ORIGINAL NAME</div>
            <div class="text-green-400 mt-1">{{ $document->original_name }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">MIME TYPE</div>
            <div class="text-green-400 mt-1">{{ $document->mime_type }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">FILE SIZE</div>
            <div class="text-green-400 mt-1">{{ $document->formatted_size }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">PAGE COUNT</div>
            <div class="text-green-400 mt-1">
                {{ $document->page_count ?? '— (not yet processed)' }}
            </div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">UPLOADED</div>
            <div class="text-green-400 mt-1">{{ $document->created_at->format('Y-m-d H:i:s') }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">DOCUMENT ID</div>
            <div class="text-green-800 mt-1">{{ $document->id }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">TOTAL WORDS</div>
            <div class="text-green-400 mt-1">
                {{ $contents->sum('word_count') > 0 ? number_format($contents->sum('word_count')) : '— (not yet processed)' }}
            </div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">TOTAL PAGES EXTRACTED</div>
            <div class="text-green-400 mt-1">
                {{ $contents->count() > 0 ? $contents->count() : '— (not yet processed)' }}
            </div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">CHUNKS</div>
            <div class="text-green-400 mt-1">
                {{ $chunks > 0 ? $chunks . ' chunks' : '— (not yet chunked)' }}
            </div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">EMBEDDINGS</div>
            <div class="mt-1">
                @if($embeddingsCount > 0)
                    <span class="text-green-400">{{ $embeddingsCount }} / {{ $chunks }} VECTORS READY</span>
                @else
                    <span class="text-yellow-600">— PENDING EMBEDDING</span>
                @endif
            </div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">SECURITY FINDINGS</div>
            <div class="mt-1">
                @if($scanResults->count() > 0)
                    <span class="text-red-400">{{ $scanResults->count() }} THREAT(S) DETECTED</span>
                @else
                    <span class="text-green-400">✓ CLEAN</span>
                @endif
            </div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">ENTITIES</div>
            <div class="text-green-400 mt-1">
                {{ $entities->count() > 0 ? $entities->count() . ' extracted' : '— (not yet extracted)' }}
            </div>
        </div>
    </div>
</div>

{{-- Status Timeline --}}
<div class="border border-green-900 mb-6">
    <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
        &gt; PROCESSING_PIPELINE
    </div>
    <div class="p-6">
        <div class="flex items-center gap-0 text-xs tracking-widest">

            @php
                $stages  = ['pending', 'processing', 'processed'];
                $current = $document->status;
                $failed  = $current === 'failed';
            @endphp

            @foreach($stages as $stage)
                @php
                    $stageIndex   = array_search($stage, $stages);
                    $currentIndex = array_search($current, $stages);
                    $isDone       = !$failed && $currentIndex >= $stageIndex;
                    $isActive     = !$failed && $current === $stage;
                @endphp

                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full border
                            {{ $failed ? 'border-red-700 bg-transparent' :
                               ($isDone ? 'border-green-400 bg-green-400' : 'border-green-800 bg-transparent') }}">
                        </div>
                        <div class="mt-2 {{ $isActive ? 'text-green-400' : ($isDone ? 'text-green-600' : 'text-green-900') }}">
                            {{ strtoupper($stage) }}
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div class="w-24 h-px mx-2 mb-5
                            {{ !$failed && $currentIndex > $stageIndex ? 'bg-green-400' : 'bg-green-900' }}">
                        </div>
                    @endif
                </div>
            @endforeach

            @if($failed)
                <div class="ml-4 flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border border-red-500 bg-red-500"></div>
                    <div class="text-red-400 mt-0">FAILED</div>
                </div>
            @endif

        </div>

        {{-- Embedding Pipeline --}}
        <div class="mt-6 pt-6 border-t border-green-900/50">
            <div class="text-green-700 text-xs tracking-widest mb-3">VECTOR PIPELINE</div>
            <div class="flex items-center gap-4 text-xs tracking-widest">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border
                        {{ $chunks > 0 ? 'border-green-400 bg-green-400' : 'border-green-800 bg-transparent' }}">
                    </div>
                    <span class="{{ $chunks > 0 ? 'text-green-600' : 'text-green-900' }}">CHUNKED</span>
                </div>
                <div class="w-16 h-px {{ $chunks > 0 ? 'bg-green-400' : 'bg-green-900' }}"></div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border
                        {{ $embeddingsCount > 0 ? 'border-green-400 bg-green-400' : 'border-green-800 bg-transparent' }}">
                    </div>
                    <span class="{{ $embeddingsCount > 0 ? 'text-green-600' : 'text-green-900' }}">EMBEDDED</span>
                </div>
                <div class="w-16 h-px {{ $embeddingsCount > 0 ? 'bg-green-400' : 'bg-green-900' }}"></div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border
                        {{ $embeddingsCount > 0 ? 'border-green-400 bg-green-400' : 'border-green-800 bg-transparent' }}">
                    </div>
                    <span class="{{ $embeddingsCount > 0 ? 'text-green-400' : 'text-green-900' }}">READY FOR RAG</span>
                </div>
            </div>
        </div>

        {{-- Security Pipeline --}}
        <div class="mt-6 pt-6 border-t border-green-900/50">
            <div class="text-green-700 text-xs tracking-widest mb-3">SECURITY PIPELINE</div>
            <div class="flex items-center gap-4 text-xs tracking-widest">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border border-green-400 bg-green-400"></div>
                    <span class="text-green-600">SCANNED</span>
                </div>
                <div class="w-16 h-px bg-green-400"></div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border
                        {{ $scanResults->count() === 0 ? 'border-green-400 bg-green-400' : 'border-red-500 bg-red-500' }}">
                    </div>
                    <span class="{{ $scanResults->count() === 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $scanResults->count() === 0 ? 'CLEAN' : 'THREATS DETECTED' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Knowledge Graph Pipeline --}}
        <div class="mt-6 pt-6 border-t border-green-900/50">
            <div class="text-green-700 text-xs tracking-widest mb-3">KNOWLEDGE GRAPH PIPELINE</div>
            <div class="flex items-center gap-4 text-xs tracking-widest">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border
                        {{ $entities->count() > 0 ? 'border-blue-400 bg-blue-400' : 'border-blue-800 bg-transparent' }}">
                    </div>
                    <span class="{{ $entities->count() > 0 ? 'text-blue-600' : 'text-blue-900' }}">ENTITIES EXTRACTED</span>
                </div>
                <div class="w-16 h-px {{ $entities->count() > 0 ? 'bg-blue-400' : 'bg-blue-900' }}"></div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border
                        {{ $relationships->count() > 0 ? 'border-blue-400 bg-blue-400' : 'border-blue-800 bg-transparent' }}">
                    </div>
                    <span class="{{ $relationships->count() > 0 ? 'text-blue-600' : 'text-blue-900' }}">RELATIONSHIPS MAPPED</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Knowledge Graph --}}
@if($entities->count() > 0)
    <div class="border border-blue-900 mb-6">
        <div class="px-6 py-3 border-b border-blue-900 text-blue-600 text-xs tracking-widest">
            &gt; KNOWLEDGE_GRAPH ({{ $entities->count() }} ENTITIES, {{ $relationships->count() }} RELATIONSHIPS)
        </div>
        
        {{-- Entities --}}
        <div class="p-6 border-b border-blue-900/50">
            <div class="text-blue-700 text-xs tracking-widest mb-4">EXTRACTED ENTITIES</div>
            <div class="space-y-3">
                @foreach($entities->groupBy('type') as $type => $typeEntities)
                    <div>
                        <div class="text-blue-600 text-xs tracking-widest mb-2">{{ strtoupper($type) }}</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($typeEntities as $entity)
                                <div class="px-3 py-1 border border-blue-700 bg-blue-950 text-blue-400 text-xs rounded">
                                    {{ $entity->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Relationships --}}
        @if($relationships->count() > 0)
            <div class="p-6">
                <div class="text-blue-700 text-xs tracking-widest mb-4">ENTITY RELATIONSHIPS</div>
                <div class="space-y-2">
                    @foreach($relationships as $rel)
                        <div class="flex items-center gap-3 text-xs text-blue-400">
                            <span class="text-blue-500 font-bold">{{ $rel->entityA->name }}</span>
                            <span class="text-blue-700">{{ str_replace('_', ' ', strtoupper($rel->relationship_type)) }}</span>
                            <span class="text-blue-500 font-bold">{{ $rel->entityB->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@else
    <div class="border border-blue-900 mb-6 px-6 py-6 text-center">
        <div class="text-blue-800 text-xs tracking-widest">
            KNOWLEDGE GRAPH NOT YET BUILT
        </div>
        <div class="text-blue-900 text-xs mt-1">
            PROCESS DOCUMENT TO EXTRACT ENTITIES AND RELATIONSHIPS
        </div>
    </div>
@endif

{{-- Security Findings --}}
@if($scanResults->count() > 0)
    <div class="border border-red-900 mb-6">
        <div class="px-6 py-3 border-b border-red-900 text-red-600 text-xs tracking-widest">
            &gt; SECURITY_FINDINGS ({{ $scanResults->count() }} THREAT(S))
        </div>
        <div class="divide-y divide-red-900/50">
            @foreach($scanResults as $finding)
                <div class="p-6 {{ $finding->severity_bg }}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="text-xs tracking-widest {{ $finding->severity_color }} font-bold">
                                {{ strtoupper($finding->severity) }}: {{ strtoupper(str_replace('_', ' ', $finding->finding_type)) }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">{{ $finding->location }}</div>
                        </div>
                        <div class="text-xs text-gray-500">{{ $finding->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="text-xs text-gray-300 font-mono">
                        {{ $finding->value_masked }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="border border-green-900 mb-6 px-6 py-6 text-center">
        <div class="text-green-600 text-xs tracking-widest">✓ SECURITY SCAN CLEAN</div>
        <div class="text-green-800 text-xs mt-1">NO SENSITIVE DATA DETECTED</div>
    </div>
@endif

{{-- Extracted Content --}}
@if($contents->count() > 0)
    <div class="border border-green-900 mb-6">
        <div class="px-6 py-3 border-b border-green-900 flex items-center justify-between">
            <div class="text-green-600 text-xs tracking-widest">&gt; EXTRACTED_CONTENT</div>
            <div class="text-green-800 text-xs tracking-widest">
                {{ $contents->count() }} PAGE(S) — {{ number_format($contents->sum('word_count')) }} WORDS
            </div>
        </div>
        <div class="divide-y divide-green-900/50">
            @foreach($contents as $content)
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-green-700 text-xs tracking-widest">
                            PAGE {{ $content->page_number }}
                        </div>
                        <div class="text-green-900 text-xs tracking-widest">
                            {{ number_format($content->word_count) }} WORDS
                        </div>
                    </div>
                    <div class="text-green-600 text-xs leading-relaxed whitespace-pre-wrap max-h-48 overflow-y-auto
                                border border-green-900/50 bg-black/30 p-4">{{ $content->raw_text }}</div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="border border-green-900 mb-6 px-6 py-8 text-center">
        <div class="text-green-800 text-xs tracking-widest">&gt; NO CONTENT EXTRACTED YET</div>
        <div class="text-green-900 text-xs mt-2 tracking-widest">
            @if($document->status === 'pending')
                DOCUMENT IS QUEUED FOR PROCESSING — RUN THE QUEUE WORKER
            @elseif($document->status === 'processing')
                PROCESSING IN PROGRESS...
            @elseif($document->status === 'failed')
                PROCESSING FAILED — TRY RE-UPLOADING THE DOCUMENT
            @endif
        </div>
    </div>
@endif

{{-- Actions --}}
<div class="flex items-center gap-4">
    <form action="{{ route('documents.destroy', $document) }}"
          method="POST"
          onsubmit="return confirm('PERMANENTLY DELETE THIS DOCUMENT?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-6 py-2 border border-red-800 text-red-600 text-xs tracking-widest
                       hover:bg-red-950 hover:text-red-400 transition-colors">
            [ DELETE DOCUMENT ]
        </button>
    </form>

    <a href="{{ route('documents.index') }}"
       class="px-6 py-2 border border-green-800 text-green-600 text-xs tracking-widest
              hover:bg-green-950 hover:text-green-400 transition-colors">
        [ BACK TO VAULT ]
    </a>
</div>

@endsection