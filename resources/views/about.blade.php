{{-- resources/views/about.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEURAVAULT // ABOUT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'JetBrains Mono', monospace; }
        body {
            background-color: #000;
            background-image:
                linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
        .cursor { animation: blink 1s infinite; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { opacity: 0; animation: fadeUp 0.5s ease forwards; }
        .d1{animation-delay:.1s} .d2{animation-delay:.25s}
        .d3{animation-delay:.4s} .d4{animation-delay:.55s}
        .d5{animation-delay:.7s} .d6{animation-delay:.85s}
    </style>
</head>
<body class="text-green-400 min-h-screen">

    {{-- NAV --}}
    <nav class="flex justify-between items-center px-10 py-5 border-b border-green-950 fade-up d1">
        <a href="/" class="text-green-400 font-bold text-lg tracking-widest hover:text-green-300 transition-colors">
            NEURAVAULT_
        </a>
        <div class="flex items-center gap-6 text-sm tracking-widest">
            <a href="/login"    class="text-green-700 hover:text-green-400 transition-colors">/LOGIN</a>
            <a href="/register"
               class="border border-green-500 text-green-400 px-4 py-2
                      hover:bg-green-400 hover:text-black transition-all">
                [ ACCESS ]
            </a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-10 py-20">

        {{-- Page header --}}
        <div class="mb-16 fade-up d2">
            <div class="text-green-800 text-xs tracking-widest mb-4">// SYSTEM DOCUMENTATION</div>
            <h1 class="text-5xl font-bold text-green-400 tracking-tight">
                ABOUT<span class="cursor">_</span>
            </h1>
            <p class="text-green-700 text-sm tracking-widest mt-4 max-w-xl leading-relaxed">
                NeuraVault is a full-stack AI intelligence platform built for
                document analysis, RAG-powered chat, secret detection,
                and knowledge graph generation.
            </p>
        </div>

        {{-- What it is --}}
        <div class="border border-green-900 p-8 mb-6 hover:border-green-700 transition-colors fade-up d3">
            <div class="text-green-600 text-xs tracking-widest mb-5">// PLATFORM OVERVIEW</div>
            <div class="space-y-3 text-sm text-green-700 leading-relaxed">
                <p>
                    NeuraVault transforms static documents into a living intelligence layer.
                    Upload any PDF and the platform automatically extracts, chunks, embeds,
                    and indexes every page into a vector database.
                </p>
                <p>
                    Ask questions in natural language. The RAG engine retrieves the most
                    relevant context and passes it to the LLM, returning precise answers
                    with exact source citations.
                </p>
                <p>
                    Every document is simultaneously scanned for exposed secrets —
                    API keys, tokens, credentials, private URLs — flagged and catalogued
                    in the security findings database.
                </p>
            </div>
        </div>

        {{-- Tech stack --}}
        <div class="border border-green-900 p-8 mb-6 hover:border-green-700 transition-colors fade-up d4">
            <div class="text-green-600 text-xs tracking-widest mb-5">// TECHNOLOGY STACK</div>
            <div class="grid grid-cols-2 gap-3 text-xs">
                @foreach([
                    ['BACKEND',     'Laravel 11 · PHP 8.3'],
                    ['FRONTEND',    'Blade · Tailwind CSS · Vite'],
                    ['AI / LLM',    'Anthropic Claude API'],
                    ['EMBEDDINGS',  'OpenAI text-embedding-3-small'],
                    ['VECTOR DB',   'pgvector · PostgreSQL'],
                    ['PDF ENGINE',  'smalot/pdfparser'],
                    ['AUTH',        'Laravel Breeze · Sessions'],
                    ['STORAGE',     'Laravel Filesystem · S3-ready'],
                ] as [$label, $value])
                <div class="flex justify-between border-b border-green-950 pb-2">
                    <span class="text-green-800 tracking-widest">{{ $label }}</span>
                    <span class="text-green-500">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Module list --}}
        <div class="border border-green-900 p-8 mb-12 hover:border-green-700 transition-colors fade-up d5">
            <div class="text-green-600 text-xs tracking-widest mb-5">// MODULE ARCHITECTURE</div>
            <div class="space-y-3">
                @foreach([
                    ['01', 'FOUNDATION',       'Auth, routing, terminal UI, layout system'],
                    ['02', 'DOCUMENT MGMT',    'Upload, store, list, delete PDFs'],
                    ['03', 'PDF PROCESSING',   'Text extraction, page parsing, storage'],
                    ['04', 'CHUNKING ENGINE',  'Sliding window chunking with overlap'],
                    ['05', 'VECTOR SEARCH',    'Embedding generation and similarity retrieval'],
                    ['06', 'RAG CHAT',         'Conversational AI with source citations'],
                    ['07', 'SECURITY SCANNER', 'Secrets, keys, credentials detection'],
                    ['08', 'KNOWLEDGE GRAPH',  'Entity extraction and relationship mapping'],
                    ['09', 'ANALYTICS',        'Usage metrics and system statistics'],
                ] as [$num, $name, $desc])
                <div class="flex gap-4 text-xs items-start">
                    <span class="text-green-900 w-12 shrink-0 tracking-widest">MOD-{{ $num }}</span>
                    <span class="text-green-500 w-44 shrink-0 tracking-wider">{{ $name }}</span>
                    <span class="text-green-800">{{ $desc }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CTA --}}
        <div class="flex gap-4 fade-up d6">
            <a href="/register"
               class="bg-green-400 text-black px-8 py-3 font-bold tracking-widest text-sm
                      hover:bg-green-300 transition-colors">
                INITIALIZE SYSTEM →
            </a>
            <a href="/"
               class="border border-green-900 text-green-700 px-8 py-3 tracking-widest text-sm
                      hover:border-green-600 hover:text-green-400 transition-all">
                ← BACK TO HOME
            </a>
        </div>

    </div>

    {{-- FOOTER --}}
    <footer class="border-t border-green-950 px-10 py-6 flex justify-between text-xs text-green-900 tracking-widest">
        <span>NEURAVAULT_ // INTELLIGENCE PLATFORM</span>
        <span>{{ now()->format('Y') }} · BUILD 1.0.0</span>
    </footer>

</body>
</html>