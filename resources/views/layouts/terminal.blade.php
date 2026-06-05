{{-- resources/views/layouts/terminal.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NEURAVAULT // {{ $title ?? 'SYSTEM' }}</title>

    {{-- Google Fonts: JetBrains Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-black text-green-400 min-h-screen flex">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="w-64 min-h-screen border-r border-green-900 flex flex-col bg-black">

        {{-- Logo --}}
        <div class="p-6 border-b border-green-900">
            <div class="text-green-400 text-lg font-bold tracking-widest">NEURAVAULT_</div>
            <div class="text-green-700 text-xs mt-1 tracking-widest">INTELLIGENCE PLATFORM</div>
        </div>

        {{-- System Status --}}
        <div class="px-6 py-3 border-b border-green-900">
            <div class="text-xs text-green-700">SYSTEM STATUS</div>
            <div class="flex items-center gap-2 mt-1">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                <span class="text-green-400 text-xs tracking-widest">ONLINE</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1">

            <div class="text-green-800 text-xs tracking-widest mb-3 px-2">// MODULES</div>

            <a href="/dashboard"
               class="flex items-center gap-3 px-3 py-2 text-sm tracking-wider transition-all
                      {{ request()->is('dashboard') ? 'text-black bg-green-400' : 'text-green-600 hover:text-green-400 hover:bg-green-950' }}">
                <span>⬡</span> DASHBOARD
            </a>

            <a href="/documents"
               class="flex items-center gap-3 px-3 py-2 text-sm tracking-wider transition-all
                      {{ request()->is('documents*') ? 'text-black bg-green-400' : 'text-green-600 hover:text-green-400 hover:bg-green-950' }}">
                <span>⬡</span> DOCUMENTS
            </a>

            <a href="/chat"
               class="flex items-center gap-3 px-3 py-2 text-sm tracking-wider transition-all
                      {{ request()->is('chat*') ? 'text-black bg-green-400' : 'text-green-600 hover:text-green-400 hover:bg-green-950' }}">
                <span>⬡</span> RAG CHAT
            </a>

            <a href="/graph"
               class="flex items-center gap-3 px-3 py-2 text-sm tracking-wider transition-all
                      {{ request()->is('graph*') ? 'text-black bg-green-400' : 'text-green-600 hover:text-green-400 hover:bg-green-950' }}">
                <span>⬡</span> KNOWLEDGE GRAPH
            </a>

            <a href="/security"
               class="flex items-center gap-3 px-3 py-2 text-sm tracking-wider transition-all
                      {{ request()->is('security*') ? 'text-black bg-green-400' : 'text-green-600 hover:text-green-400 hover:bg-green-950' }}">
                <span>⬡</span> SECURITY SCAN
            </a>

            <a href="/analytics"
               class="flex items-center gap-3 px-3 py-2 text-sm tracking-wider transition-all
                      {{ request()->is('analytics*') ? 'text-black bg-green-400' : 'text-green-600 hover:text-green-400 hover:bg-green-950' }}">
                <span>⬡</span> ANALYTICS
            </a>

        </nav>

        {{-- User Info --}}
        <div class="px-6 py-4 border-t border-green-900">
            <div class="text-green-700 text-xs tracking-widest">OPERATOR</div>
            <div class="text-green-400 text-sm mt-1 truncate">{{ auth()->user()->email }}</div>
            <form method="POST" action="/logout" class="mt-3">
                @csrf
                <button class="text-xs text-green-800 hover:text-red-500 tracking-widest transition-colors">
                    [ LOGOUT ]
                </button>
            </form>
        </div>

    </aside>

    {{-- ============ MAIN AREA ============ --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Top Bar --}}
        <header class="border-b border-green-900 px-8 py-4 flex items-center justify-between">
            <div class="text-green-600 text-xs tracking-widest">
                &gt; {{ $title ?? 'SYSTEM_OVERVIEW' }}
            </div>
            <div class="text-green-800 text-xs tracking-widest">
                {{ now()->format('Y-m-d H:i:s') }} UTC
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>

</body>
</html>