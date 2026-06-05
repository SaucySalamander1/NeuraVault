<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} // AUTH</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'JetBrains Mono', monospace;
        }

        body {
            background-color: #000;
            background-image:
                linear-gradient(rgba(74,222,128,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74,222,128,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @keyframes blink {
            0%,100% { opacity:1; }
            50% { opacity:0; }
        }

        .cursor {
            animation: blink 1s infinite;
        }

        @keyframes scanline {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(100vh);
            }
        }

        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(74,222,128,.05);
            animation: scanline 6s linear infinite;
            pointer-events: none;
            z-index: 50;
        }
    </style>
</head>
<body class="text-green-400 min-h-screen">

    <div class="scanline"></div>

    <div class="min-h-screen flex items-center justify-center px-6">

        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="text-center mb-10">

                <a href="/" class="inline-block">
                    <div class="text-2xl font-bold tracking-widest">
                        NEURAVAULT_
                    </div>

                    <div class="text-green-800 text-xs mt-2 tracking-[0.3em]">
                        INTELLIGENCE PLATFORM
                    </div>
                </a>

            </div>

            {{-- Card --}}
            <div class="border border-green-950 bg-black p-8">

                @yield('content')

            </div>

            {{-- Footer --}}
            <div class="mt-6 text-center text-green-900 text-xs tracking-widest">

                > intelligence network online<span class="cursor">_</span>

            </div>

        </div>

    </div>

</body>
</html>