{{-- resources/views/security/index.blade.php --}}
@extends('layouts.terminal')
@section('title', 'SECURITY SCAN')

@section('content')

{{-- Page Header --}}
<div class="mb-8">
    <div class="text-red-400 text-xl font-bold tracking-widest">SECURITY SCAN CENTER</div>
    <div class="text-red-700 text-xs mt-1 tracking-widest">// THREAT DETECTION & ANALYSIS</div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-5 gap-4 mb-8">
    <div class="border border-red-900 p-5">
        <div class="text-red-700 text-xs tracking-widest mb-2">TOTAL THREATS</div>
        <div class="text-red-400 text-3xl font-bold">{{ $stats['total_threats'] }}</div>
    </div>

    <div class="border border-red-900 p-5">
        <div class="text-red-700 text-xs tracking-widest mb-2">CRITICAL</div>
        <div class="text-red-400 text-3xl font-bold">{{ $stats['critical'] }}</div>
    </div>

    <div class="border border-yellow-900 p-5">
        <div class="text-yellow-700 text-xs tracking-widest mb-2">HIGH</div>
        <div class="text-yellow-400 text-3xl font-bold">{{ $stats['high'] }}</div>
    </div>

    <div class="border border-orange-900 p-5">
        <div class="text-orange-700 text-xs tracking-widest mb-2">MEDIUM</div>
        <div class="text-orange-400 text-3xl font-bold">{{ $stats['medium'] }}</div>
    </div>

    <div class="border border-blue-900 p-5">
        <div class="text-blue-700 text-xs tracking-widest mb-2">LOW</div>
        <div class="text-blue-400 text-3xl font-bold">{{ $stats['low'] }}</div>
    </div>
</div>

{{-- Threats Table --}}
<div class="border border-red-900">
    <div class="px-6 py-3 border-b border-red-900 text-red-600 text-xs tracking-widest">
        &gt; DETECTED_THREATS ({{ $findings->total() }})
    </div>

    @if($findings->count() > 0)
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-red-900 text-red-700 tracking-widest">
                    <th class="px-6 py-3 text-left">TYPE</th>
                    <th class="px-6 py-3 text-left">SEVERITY</th>
                    <th class="px-6 py-3 text-left">VALUE</th>
                    <th class="px-6 py-3 text-left">DOCUMENT</th>
                    <th class="px-6 py-3 text-left">LOCATION</th>
                    <th class="px-6 py-3 text-left">DETECTED</th>
                </tr>
            </thead>
            <tbody>
                @foreach($findings as $threat)
                    <tr class="border-b border-red-900/50 hover:bg-red-950/20 transition-colors">
                        <td class="px-6 py-3 text-red-400 font-bold">
                            {{ ucwords(str_replace('_', ' ', $threat->finding_type)) }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $threat->severity_color }}">
                                {{ strtoupper($threat->severity) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-red-300 font-mono">{{ $threat->value_masked }}</td>
                        <td class="px-6 py-3 text-red-600 max-w-xs truncate">
                            <a href="{{ route('documents.show', $threat->document) }}" class="hover:text-red-400">
                                {{ $threat->document->original_name }}
                            </a>
                        </td>
                        <td class="px-6 py-3 text-red-700">{{ $threat->location }}</td>
                        <td class="px-6 py-3 text-red-800">{{ $threat->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-red-900">
            {{ $findings->links('pagination::simple-tailwind') }}
        </div>
    @else
        <div class="px-6 py-8 text-center">
            <div class="text-green-600 text-xs tracking-widest">✓ ALL SCANS CLEAN</div>
            <div class="text-red-900 text-xs mt-2">NO THREATS DETECTED ACROSS YOUR DOCUMENTS</div>
        </div>
    @endif
</div>

@endsection