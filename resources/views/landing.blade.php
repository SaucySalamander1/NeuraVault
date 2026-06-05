{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEURAVAULT // INTELLIGENCE PLATFORM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'JetBrains Mono', monospace; }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }
        .cursor { animation: blink 1s infinite; }

        @keyframes scanline {
            0%   { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }
        .scanline {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 2px;
            background: rgba(74, 222, 128, 0.05);
            animation: scanline 6s linear infinite;
            pointer-events: none;
            z-index: 50;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { opacity: 0; animation: fadeUp 0.6s ease forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.5s; }
        .delay-4 { animation-delay: 0.7s; }
        .delay-5 { animation-delay: 0.9s; }
        .delay-6 { animation-delay: 1.1s; }

        /* subtle grid background */
        body {
            background-color: #000;
            background-image:
                linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="text-green-400 min-h-screen">

    <div class="scanline"></div>

    {{-- NAV --}}
    <nav class="flex justify-between items-center px-10 py-5 border-b border-green-950 fade-up delay-1">
        <div>
            <span class="text-green-400 font-bold text-lg tracking-widest">NEURAVAULT_</span>
            <span class="text-green-800 text-xs ml-3 tracking-widest hidden sm:inline">v1.0.0</span>
        </div>
        <div class="flex items-center gap-6 text-sm tracking-widest">
            <a href="/about"   class="text-green-700 hover:text-green-400 transition-colors">/ABOUT</a>
            <a href="/login"   class="text-green-700 hover:text-green-400 transition-colors">/LOGIN</a>
            <a href="/register"
               class="border border-green-500 text-green-400 px-4 py-2 hover:bg-green-400 hover:text-black transition-all">
                [ ACCESS ]
            </a>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="max-w-5xl mx-auto px-10 pt-24 pb-16">

        {{-- Status badge --}}
        <div class="flex items-center gap-3 mb-10 fade-up delay-2">
            <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
            <span class="text-green-700 text-xs tracking-widest">SYSTEM ONLINE — ALL NODES OPERATIONAL</span>
        </div>

        {{-- Main heading --}}
        <h1 class="text-5xl sm:text-7xl font-bold leading-none tracking-tight mb-6 fade-up delay-3">
            <span class="text-green-400">CYBER</span><br>
            <span class="text-green-700">INTELLIGENCE</span><br>
            <span class="text-green-900">PLATFORM<span class="text-green-400 cursor">_</span></span>
        </h1>

        {{-- Descriptor --}}
        <p class="text-green-700 text-sm tracking-widest max-w-xl mb-12 leading-relaxed fade-up delay-4">
            RAG-POWERED DOCUMENT ANALYSIS · THREAT DETECTION ·
            KNOWLEDGE GRAPH EXTRACTION · VECTOR SEARCH · LLM REASONING
        </p>

        {{-- CTA --}}
        <div class="flex flex-wrap gap-4 fade-up delay-5">
            <a href="/register"
               class="bg-green-400 text-black px-8 py-3 font-bold tracking-widest text-sm
                      hover:bg-green-300 transition-colors">
                INITIALIZE SYSTEM →
            </a>
            <a href="/about"
               class="border border-green-900 text-green-700 px-8 py-3 tracking-widest text-sm
                      hover:border-green-600 hover:text-green-400 transition-all">
                READ DOCUMENTATION
            </a>
        </div>

    </section>

    {{-- SYSTEM PIPELINE --}}
    <section class="max-w-5xl mx-auto px-10 py-24">

        <div class="border-t border-green-950 pt-12 mb-12">
            <span class="text-green-800 text-xs tracking-widest">
                // INTELLIGENCE PIPELINE
            </span>
        </div>

        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-green-400 mb-4">
                DOCUMENT → INTELLIGENCE
            </h2>

            <p class="text-green-700 text-sm max-w-2xl mx-auto leading-relaxed">
                Every uploaded document is transformed into a searchable intelligence asset
                through semantic indexing, threat detection, knowledge extraction, and AI reasoning.
            </p>
        </div>

        <div class="grid md:grid-cols-6 gap-4">

            @foreach([
                'UPLOAD',
                'PROCESS',
                'EMBED',
                'SCAN',
                'GRAPH',
                'QUERY'
            ] as $step)

            <div class="border border-green-950 bg-black p-5 text-center hover:border-green-700 transition-all">

                <div class="text-green-400 font-bold tracking-widest text-sm">
                    {{ $step }}
                </div>

            </div>

            @endforeach

        </div>

        <div class="flex justify-center mt-6 text-green-900 text-sm tracking-widest">
            ↓ ↓ ↓ ↓ ↓
        </div>

    </section>

    {{-- CAPABILITY GRID --}}
    <section class="max-w-5xl mx-auto px-10 pb-24 fade-up delay-6">

        <div class="border-t border-green-950 pt-12 mb-8">
            <span class="text-green-800 text-xs tracking-widest">// CORE MODULES</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-px bg-green-950">

            @foreach([
                ['01', 'DOCUMENT INTEL',  'Upload PDFs. Extract, parse, and index every page into a searchable knowledge base.'],
                ['02', 'RAG CHAT',        'Query your documents with AI. Get answers with exact source citations.'],
                ['03', 'SECRET SCANNER',  'Detect exposed API keys, credentials, tokens, and sensitive data patterns.'],
                ['04', 'KNOWLEDGE GRAPH', 'Extract entities and relationships. Map intelligence visually.'],
                ['05', 'VECTOR SEARCH',   'Semantic similarity search across all indexed content using embeddings.'],
                ['06', 'ANALYTICS',       'Usage metrics, token counts, threat summaries, and document statistics.'],
            ] as [$num, $name, $desc])
            <div class="bg-black p-6 hover:bg-green-950 transition-colors group">
                <div class="text-green-900 text-xs mb-3 tracking-widest group-hover:text-green-700">MOD-{{ $num }}</div>
                <div class="text-green-400 text-sm font-bold tracking-wider mb-2">{{ $name }}</div>
                <div class="text-green-800 text-xs leading-relaxed">{{ $desc }}</div>
            </div>
            @endforeach

        </div>

    </section>

        {{-- STATS --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-green-950">

            <div class="bg-black p-8 text-center">
                <div class="text-4xl font-bold text-green-400 mb-2">
                    1M+
                </div>
                <div class="text-green-800 text-xs tracking-widest">
                    TOKENS PROCESSED
                </div>
            </div>

            <div class="bg-black p-8 text-center">
                <div class="text-4xl font-bold text-green-400 mb-2">
                    50K+
                </div>
                <div class="text-green-800 text-xs tracking-widest">
                    DOCUMENTS INDEXED
                </div>
            </div>

            <div class="bg-black p-8 text-center">
                <div class="text-4xl font-bold text-green-400 mb-2">
                    99.9%
                </div>
                <div class="text-green-800 text-xs tracking-widest">
                    UPTIME
                </div>
            </div>

            <div class="bg-black p-8 text-center">
                <div class="text-4xl font-bold text-green-400 mb-2">
                    100K+
                </div>
                <div class="text-green-800 text-xs tracking-widest">
                    AI QUERIES
                </div>
            </div>

        </div>

    </section>

        {{-- HOW IT WORKS --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="border-t border-green-950 pt-12 mb-12">
            <span class="text-green-800 text-xs tracking-widest">
                // HOW IT WORKS
            </span>
        </div>

        <div class="grid md:grid-cols-4 gap-6">

            <div class="border border-green-950 p-6">
                <div class="text-green-400 font-bold mb-4">01</div>
                <h3 class="font-bold mb-3">UPLOAD</h3>
                <p class="text-green-800 text-sm">
                    Upload PDF documents into the intelligence pipeline.
                </p>
            </div>

            <div class="border border-green-950 p-6">
                <div class="text-green-400 font-bold mb-4">02</div>
                <h3 class="font-bold mb-3">ANALYZE</h3>
                <p class="text-green-800 text-sm">
                    Extract text, entities, metadata and security indicators.
                </p>
            </div>

            <div class="border border-green-950 p-6">
                <div class="text-green-400 font-bold mb-4">03</div>
                <h3 class="font-bold mb-3">INDEX</h3>
                <p class="text-green-800 text-sm">
                    Generate embeddings and build semantic search indexes.
                </p>
            </div>

            <div class="border border-green-950 p-6">
                <div class="text-green-400 font-bold mb-4">04</div>
                <h3 class="font-bold mb-3">QUERY</h3>
                <p class="text-green-800 text-sm">
                    Chat with documents using AI-powered retrieval.
                </p>
            </div>

        </div>

    </section>

        {{-- TERMINAL DEMO --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="border-t border-green-950 pt-12 mb-10">
            <span class="text-green-800 text-xs tracking-widest">
                // LIVE ANALYSIS
            </span>
        </div>

        <div class="border border-green-950 bg-black p-8">

            <div class="flex items-center gap-2 mb-6">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
            </div>

    <pre class="text-green-400 text-sm leading-8 overflow-x-auto">
    > upload security_report.pdf

    [+] document uploaded
    [+] extracting text
    [+] generating embeddings
    [+] running secret scanner

    [!] AWS_ACCESS_KEY detected
    [!] OPENAI_SECRET detected

    [+] building knowledge graph
    [+] creating vector index

    SYSTEM READY_
    </pre>

        </div>

    </section>

        {{-- WHY NEURAVAULT --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="border-t border-green-950 pt-12 mb-12">
            <span class="text-green-800 text-xs tracking-widest">
                // WHY NEURAVAULT
            </span>
        </div>

        <div class="grid md:grid-cols-2 gap-10">

            <div>
                <h3 class="text-red-400 mb-6">
                    Traditional PDF Search
                </h3>

                <ul class="space-y-3 text-green-800">
                    <li>✗ Keyword matching only</li>
                    <li>✗ No context understanding</li>
                    <li>✗ No threat detection</li>
                    <li>✗ No knowledge extraction</li>
                </ul>
            </div>

            <div>
                <h3 class="text-green-400 mb-6">
                    NeuraVault Intelligence Engine
                </h3>

                <ul class="space-y-3 text-green-400">
                    <li>✓ Semantic search</li>
                    <li>✓ RAG-powered answers</li>
                    <li>✓ Secret detection</li>
                    <li>✓ Knowledge graph generation</li>
                </ul>
            </div>

        </div>

    </section>

        {{-- TECH STACK --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="border-t border-green-950 pt-12 mb-12">
            <span class="text-green-800 text-xs tracking-widest">
                // TECHNOLOGY STACK
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-green-950">

            @foreach([
                'Laravel',
                'PostgreSQL',
                'pgVector',
                'OpenAI',
                'TailwindCSS',
                'Blade',
                'Knowledge Graphs',
                'RAG Pipeline'
            ] as $tech)

            <div class="bg-black p-6 text-center">
                <span class="text-green-400">
                    {{ $tech }}
                </span>
            </div>

            @endforeach

        </div>

    </section>

        {{-- SECURITY --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="border-t border-green-950 pt-12 mb-12">
            <span class="text-green-800 text-xs tracking-widest">
                // SECURITY FEATURES
            </span>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="border border-green-950 p-6">
                <h3 class="text-green-400 font-bold mb-4">
                    SECRET DETECTION
                </h3>

                <p class="text-green-800 text-sm">
                    Detect exposed API keys, credentials,
                    authentication tokens and secrets.
                </p>
            </div>

            <div class="border border-green-950 p-6">
                <h3 class="text-green-400 font-bold mb-4">
                    VECTOR SEARCH
                </h3>

                <p class="text-green-800 text-sm">
                    Semantic retrieval powered by embeddings
                    instead of keyword matching.
                </p>
            </div>

            <div class="border border-green-950 p-6">
                <h3 class="text-green-400 font-bold mb-4">
                    KNOWLEDGE EXTRACTION
                </h3>

                <p class="text-green-800 text-sm">
                    Build relationship graphs between entities
                    discovered in documents.
                </p>
            </div>

        </div>

    </section>

        {{-- FINAL CTA / REGISTER --}}
    <section class="max-w-5xl mx-auto px-10 pb-24">

        <div class="border border-green-950 bg-black p-12 relative overflow-hidden">

            {{-- Background Glow --}}
            <div class="absolute inset-0 bg-green-500/5 blur-3xl"></div>

            <div class="relative z-10">

                {{-- Header --}}
                <div class="text-center mb-14">

                    <div class="text-green-800 text-xs tracking-[0.3em] mb-4">
                        // OPERATOR ACCESS
                    </div>

                    <h2 class="text-4xl sm:text-5xl font-bold text-green-400 mb-6 leading-tight">
                        READY TO UNLOCK<br>
                        DOCUMENT INTELLIGENCE?
                    </h2>

                    <p class="text-green-700 max-w-2xl mx-auto leading-relaxed">
                        Transform static documents into actionable intelligence.
                        Analyze PDFs, discover hidden relationships, detect exposed
                        credentials, and interact with your data through AI-powered reasoning.
                    </p>

                </div>

                {{-- Benefits --}}
                <div class="grid md:grid-cols-3 gap-px bg-green-950 mb-12">

                    <div class="bg-black p-8 text-center">
                        <div class="text-green-400 text-2xl font-bold mb-3">
                            AI
                        </div>

                        <div class="text-green-700 text-sm font-medium mb-2">
                            RAG CHAT
                        </div>

                        <p class="text-green-900 text-xs leading-relaxed">
                            Query documents naturally and receive context-aware answers with citations.
                        </p>
                    </div>

                    <div class="bg-black p-8 text-center">
                        <div class="text-green-400 text-2xl font-bold mb-3">
                            SECURE
                        </div>

                        <div class="text-green-700 text-sm font-medium mb-2">
                            SECRET DETECTION
                        </div>

                        <p class="text-green-900 text-xs leading-relaxed">
                            Identify API keys, credentials, tokens, and sensitive information instantly.
                        </p>
                    </div>

                    <div class="bg-black p-8 text-center">
                        <div class="text-green-400 text-2xl font-bold mb-3">
                            GRAPH
                        </div>

                        <div class="text-green-700 text-sm font-medium mb-2">
                            KNOWLEDGE EXTRACTION
                        </div>

                        <p class="text-green-900 text-xs leading-relaxed">
                            Visualize entities and relationships extracted from documents.
                        </p>
                    </div>

                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10">

                    <a href="/register"
                    class="bg-green-400 text-black px-8 py-4 font-bold tracking-widest text-center
                            hover:bg-green-300 transition-all duration-300">

                        INITIALIZE SYSTEM →
                    </a>

                    <a href="/login"
                    class="border border-green-800 text-green-400 px-8 py-4 tracking-widest text-center
                            hover:border-green-400 hover:bg-green-400 hover:text-black
                            transition-all duration-300">

                        EXISTING OPERATOR
                    </a>

                </div>

                {{-- Terminal Status --}}
                <div class="text-center text-green-900 text-sm tracking-widest leading-8">

                    <div>> intelligence network online</div>
                    <div>> awaiting operator credentials</div>
                    <div>> system ready<span class="cursor">_</span></div>

                </div>

            </div>

        </div>

    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-green-950 px-10 py-6 flex justify-between items-center text-xs text-green-900 tracking-widest">
        <span>NEURAVAULT_ // INTELLIGENCE PLATFORM</span>
        <span>{{ now()->format('Y') }} · BUILD 1.0.0</span>
    </footer>

</body>
</html>