<nav x-data="{ open: false }"
     class="bg-black border-b border-green-950">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <div class="flex items-center gap-10">

                <a href="{{ route('dashboard') }}"
                   class="text-green-400 font-bold tracking-widest">

                    NEURAVAULT_

                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center gap-8 text-sm">

                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard')
                            ? 'text-green-400'
                            : 'text-green-700 hover:text-green-400' }} transition">

                        /DASHBOARD

                    </a>

                    <a href="#"
                       class="text-green-700 hover:text-green-400 transition">

                        /DOCUMENTS

                    </a>

                    <a href="#"
                       class="text-green-700 hover:text-green-400 transition">

                        /GRAPH

                    </a>

                    <a href="#"
                       class="text-green-700 hover:text-green-400 transition">

                        /ANALYTICS

                    </a>

                </div>

            </div>

            {{-- Right Side --}}
            <div class="hidden md:flex items-center gap-6">

                <div class="text-right">

                    <div class="text-green-400 text-sm">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="text-green-800 text-xs">
                        OPERATOR
                    </div>

                </div>

                <a href="{{ route('profile.edit') }}"
                   class="border border-green-900 px-4 py-2
                          text-green-700 text-xs
                          hover:text-green-400
                          hover:border-green-500
                          transition">

                    PROFILE

                </a>

                <form method="POST"
                      action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="bg-green-400 text-black
                               px-4 py-2 text-xs
                               font-bold tracking-widest
                               hover:bg-green-300 transition">

                        LOGOUT

                    </button>

                </form>

            </div>

            {{-- Mobile Menu Button --}}
            <button
                @click="open = !open"
                class="md:hidden text-green-400"
            >
                ☰
            </button>

        </div>

    </div>

    {{-- Mobile Menu --}}
    <div x-show="open"
         x-transition
         class="md:hidden border-t border-green-950">

        <div class="p-4 space-y-4">

            <a href="{{ route('dashboard') }}"
               class="block text-green-400">

                /DASHBOARD

            </a>

            <a href="#"
               class="block text-green-700">

                /DOCUMENTS

            </a>

            <a href="#"
               class="block text-green-700">

                /GRAPH

            </a>

            <a href="#"
               class="block text-green-700">

                /ANALYTICS

            </a>

            <hr class="border-green-950">

            <a href="{{ route('profile.edit') }}"
               class="block text-green-400">

                PROFILE

            </a>

            <form method="POST"
                  action="{{ route('logout') }}">

                @csrf

                <button
                    type="submit"
                    class="text-red-400"
                >
                    LOGOUT
                </button>

            </form>

        </div>

    </div>

</nav>