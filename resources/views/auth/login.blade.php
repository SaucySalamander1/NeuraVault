@extends('layouts.auth')

@section('content')

<div class="mb-8">

```
<div class="text-green-800 text-xs tracking-[0.3em] mb-3">
    // AUTHENTICATION
</div>

<h1 class="text-3xl font-bold text-green-400 mb-2">
    OPERATOR LOGIN
</h1>

<p class="text-green-700 text-sm">
    Access the NeuraVault Intelligence Platform.
</p>
```

</div>

<x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">

```
@csrf

{{-- Email --}}
<div class="mb-5">

    <label
        for="email"
        class="block text-green-700 text-xs tracking-widest mb-2"
    >
        EMAIL ADDRESS
    </label>

    <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
        autofocus
        autocomplete="username"
        class="w-full bg-black border border-green-950
               text-green-400 px-4 py-3
               focus:border-green-500
               focus:outline-none"
    >

    @error('email')
        <p class="text-red-400 text-xs mt-2">
            {{ $message }}
        </p>
    @enderror

</div>

{{-- Password --}}
<div class="mb-5">

    <label
        for="password"
        class="block text-green-700 text-xs tracking-widest mb-2"
    >
        PASSWORD
    </label>

    <input
        id="password"
        type="password"
        name="password"
        required
        autocomplete="current-password"
        class="w-full bg-black border border-green-950
               text-green-400 px-4 py-3
               focus:border-green-500
               focus:outline-none"
    >

    @error('password')
        <p class="text-red-400 text-xs mt-2">
            {{ $message }}
        </p>
    @enderror

</div>

{{-- Remember Me --}}
<div class="flex items-center mb-6">

    <input
        id="remember_me"
        type="checkbox"
        name="remember"
        class="bg-black border-green-700"
    >

    <label
        for="remember_me"
        class="ml-3 text-green-700 text-sm"
    >
        Remember Operator
    </label>

</div>

{{-- Actions --}}
<div class="space-y-4">

    <button
        type="submit"
        class="w-full bg-green-400 text-black
               py-3 font-bold tracking-widest
               hover:bg-green-300 transition"
    >
        AUTHENTICATE →
    </button>

    @if (Route::has('password.request'))

    <div class="text-center">

        <a
            href="{{ route('password.request') }}"
            class="text-green-700 hover:text-green-400 text-sm"
        >
            Forgot credentials?
        </a>

    </div>

    @endif

    <div class="text-center pt-4 border-t border-green-950">

        <span class="text-green-900 text-sm">
            New operator?
        </span>

        <a
            href="{{ route('register') }}"
            class="text-green-400 ml-2"
        >
            Initialize Account
        </a>

    </div>

</div>
```

</form>

@endsection
