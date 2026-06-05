{{-- resources/views/graph/index.blade.php --}}
@extends('layouts.terminal')
@section('title', 'KNOWLEDGE GRAPH')

@section('content')

{{-- Page Header --}}
<div class="mb-8">
    <div class="text-blue-400 text-xl font-bold tracking-widest">KNOWLEDGE GRAPH</div>
    <div class="text-blue-700 text-xs mt-1 tracking-widest">// ENTITY & RELATIONSHIP MAPPING</div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="border border-blue-900 p-5">
        <div class="text-blue-700 text-xs tracking-widest mb-2">TOTAL ENTITIES</div>
        <div class="text-blue-400 text-3xl font-bold">{{ $stats['total_entities'] }}</div>
    </div>

    <div class="border border-blue-900 p-5">
        <div class="text-blue-700 text-xs tracking-widest mb-2">PEOPLE</div>
        <div class="text-blue-400 text-3xl font-bold">{{ $stats['people'] }}</div>
    </div>

    <div class="border border-blue-900 p-5">
        <div class="text-blue-700 text-xs tracking-widest mb-2">COMPANIES</div>
        <div class="text-blue-400 text-3xl font-bold">{{ $stats['companies'] }}</div>
    </div>

    <div class="border border-blue-900 p-5">
        <div class="text-blue-700 text-xs tracking-widest mb-2">RELATIONSHIPS</div>
        <div class="text-blue-400 text-3xl font-bold">{{ $stats['total_relationships'] }}</div>
    </div>
</div>

{{-- Entities by Type --}}
<div class="border border-blue-900 mb-6">
    <div class="px-6 py-3 border-b border-blue-900 text-blue-600 text-xs tracking-widest">
        &gt; EXTRACTED_ENTITIES ({{ $stats['total_entities'] }})
    </div>

    @if($stats['total_entities'] > 0)
        @foreach($entities->groupBy('type') as $type => $typeEntities)
            <div class="p-6 border-b border-blue-900/50">
                <div class="text-blue-700 text-xs tracking-widest mb-4">{{ strtoupper($type) }}</div>
                <div class="flex flex-wrap gap-3">
                    @foreach($typeEntities as $entity)
                        <div class="px-4 py-2 border border-blue-700 bg-blue-950 text-blue-400 rounded text-xs hover:border-blue-500 hover:bg-blue-900 transition-colors cursor-pointer">
                            {{ $entity->name }}
                            <span class="text-blue-600 ml-2">({{ $entity->relationshipsAsA()->count() + $entity->relationshipsAsB()->count() }})</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="px-6 py-8 text-center">
            <div class="text-blue-800 text-xs tracking-widest">NO ENTITIES EXTRACTED YET</div>
            <div class="text-blue-900 text-xs mt-2">UPLOAD DOCUMENTS TO BUILD YOUR KNOWLEDGE GRAPH</div>
        </div>
    @endif
</div>

{{-- Relationships --}}
@if($stats['total_relationships'] > 0)
    <div class="border border-blue-900">
        <div class="px-6 py-3 border-b border-blue-900 text-blue-600 text-xs tracking-widest">
            &gt; ENTITY_RELATIONSHIPS ({{ $stats['total_relationships'] }})
        </div>
        <div class="divide-y divide-blue-900/50">
            @foreach($relationships as $rel)
                <div class="px-6 py-4 hover:bg-blue-950/20 transition-colors">
                    <div class="flex items-center gap-4 text-xs">
                        <span class="px-3 py-1 border border-blue-700 bg-blue-950 text-blue-400 rounded">
                            {{ $rel->entityA->name }}
                        </span>
                        <span class="text-blue-600 tracking-widest">
                            {{ str_replace('_', ' ', strtoupper($rel->relationship_type)) }}
                        </span>
                        <span class="px-3 py-1 border border-blue-700 bg-blue-950 text-blue-400 rounded">
                            {{ $rel->entityB->name }}
                        </span>
                        <span class="ml-auto text-blue-700">
                            from {{ $rel->document->original_name }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="border border-blue-900 px-6 py-8 text-center">
        <div class="text-blue-800 text-xs tracking-widest">NO RELATIONSHIPS MAPPED YET</div>
    </div>
@endif

@endsection