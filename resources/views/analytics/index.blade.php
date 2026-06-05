{{-- resources/views/analytics/index.blade.php --}}
@extends('layouts.terminal')
@section('title', 'ANALYTICS')

@section('content')

{{-- Page Header --}}
<div class="mb-8">
    <div class="text-green-400 text-xl font-bold tracking-widest">ANALYTICS DASHBOARD</div>
    <div class="text-green-700 text-xs mt-1 tracking-widest">// USAGE METRICS & INSIGHTS</div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="border border-green-900 p-5">
        <div class="text-green-700 text-xs tracking-widest mb-2">DOCUMENTS UPLOADED</div>
        <div class="text-green-400 text-3xl font-bold">{{ $stats['documents_uploaded'] }}</div>
        <div class="text-green-800 text-xs mt-2">Last 30 days</div>
    </div>

    <div class="border border-green-900 p-5">
        <div class="text-green-700 text-xs tracking-widest mb-2">DOCUMENTS PROCESSED</div>
        <div class="text-green-400 text-3xl font-bold">{{ $stats['documents_processed'] }}</div>
        <div class="text-green-800 text-xs mt-2">Successfully scanned</div>
    </div>

    <div class="border border-green-900 p-5">
        <div class="text-green-700 text-xs tracking-widest mb-2">CHAT MESSAGES</div>
        <div class="text-green-400 text-3xl font-bold">{{ $stats['chat_messages'] }}</div>
        <div class="text-green-800 text-xs mt-2">In {{ $stats['total_chats'] }} conversations</div>
    </div>

    <div class="border border-green-900 p-5">
        <div class="text-green-700 text-xs tracking-widest mb-2">SECURITY THREATS</div>
        <div class="text-red-400 text-3xl font-bold">{{ $stats['security_threats'] }}</div>
        <div class="text-green-800 text-xs mt-2">Detected & masked</div>
    </div>
</div>

{{-- Documents by Date Chart --}}
<div class="border border-green-900 mb-6">
    <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
        &gt; DOCUMENTS_UPLOADED_TIMELINE
    </div>
    <div class="p-6">
        @if(count($stats['documents_by_date']) > 0)
            <div class="h-64 flex items-end gap-1">
                @php
                    $maxCount = max(array_column($stats['documents_by_date'], 'count'), 1);
                @endphp
                @foreach($stats['documents_by_date'] as $entry)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-green-700 rounded-t" 
                             style="height: {{ ($entry['count'] / $maxCount) * 100 }}%">
                        </div>
                        <div class="text-green-700 text-xs mt-2">{{ $entry['count'] }}</div>
                        <div class="text-green-900 text-xs">{{ substr($entry['date'], 5) }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-green-800 py-8">NO DATA YET</div>
        @endif
    </div>
</div>

{{-- Chat Messages by Date Chart --}}
<div class="border border-green-900 mb-6">
    <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
        &gt; CHAT_ACTIVITY_TIMELINE
    </div>
    <div class="p-6">
        @if(count($stats['chats_by_date']) > 0)
            <div class="h-64 flex items-end gap-1">
                @php
                    $maxCount = max(array_column($stats['chats_by_date'], 'count'), 1);
                @endphp
                @foreach($stats['chats_by_date'] as $entry)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-700 rounded-t" 
                             style="height: {{ ($entry['count'] / $maxCount) * 100 }}%">
                        </div>
                        <div class="text-blue-700 text-xs mt-2">{{ $entry['count'] }}</div>
                        <div class="text-blue-900 text-xs">{{ substr($entry['date'], 5) }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-blue-800 py-8">NO DATA YET</div>
        @endif
    </div>
</div>

{{-- Security Threats Table --}}
@if(count($stats['threat_types']) > 0)
    <div class="border border-red-900 mb-6">
        <div class="px-6 py-3 border-b border-red-900 text-red-600 text-xs tracking-widest">
            &gt; SECURITY_THREATS_BREAKDOWN
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-red-900 text-red-700 tracking-widest">
                    <th class="px-6 py-3 text-left">THREAT TYPE</th>
                    <th class="px-6 py-3 text-left">SEVERITY</th>
                    <th class="px-6 py-3 text-right">COUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['threat_types'] as $threat)
                    <tr class="border-b border-red-900/50 hover:bg-red-950/20">
                        <td class="px-6 py-3 text-red-400">{{ ucwords(str_replace('_', ' ', $threat['type'])) }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded text-xs 
                                {{ $threat['severity'] === 'critical' ? 'bg-red-950 text-red-400' : 
                                   ($threat['severity'] === 'high' ? 'bg-orange-950 text-orange-400' : 
                                   ($threat['severity'] === 'medium' ? 'bg-yellow-950 text-yellow-400' : 'bg-blue-950 text-blue-400')) }}">
                                {{ ucfirst($threat['severity']) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right text-red-400 font-bold">{{ $threat['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- Overall Stats Summary --}}
<div class="border border-green-900 p-6">
    <div class="text-green-600 text-xs tracking-widest mb-4">// SUMMARY</div>
    <div class="grid grid-cols-3 gap-6 text-xs">
        <div>
            <div class="text-green-700 tracking-widest">TOTAL DOCUMENTS</div>
            <div class="text-green-400 text-2xl font-bold mt-2">{{ $stats['total_documents'] }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">TOTAL CONVERSATIONS</div>
            <div class="text-green-400 text-2xl font-bold mt-2">{{ $stats['total_chats'] }}</div>
        </div>
        <div>
            <div class="text-green-700 tracking-widest">EMBEDDINGS GENERATED</div>
            <div class="text-green-400 text-2xl font-bold mt-2">{{ $stats['embeddings_generated'] }}</div>
        </div>
    </div>
</div>

@endsection