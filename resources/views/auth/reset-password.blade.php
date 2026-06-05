@extends('layouts.auth')

@section('content')

<div class="mb-8">

```
<div class="text-green-800 text-xs tracking-[0.3em] mb-3">
    // ACCESS RECOVERY
</div>

<h1 class="text-3xl font-bold text-green-400 mb-3">
    RESET ACCESS KEY
</h1>

<p class="text-green-700 text-sm leading-relaxed">
    Recovery token verified. Configure a new access key to regain
    access to the intelligence network.
</p>
```

</div>

<form method="POST" action="{{ route('password.store') }}">

```
@csrf

<input
    type="hidden"
    name="token"
    value="{{ $request->route('token') }}"
>

{{-- Email --}}
<div class="mb-5">

    <label
        for="email"
        class="block text-green-700 text-xs tracking-widest mb-2"
    >
        OPERATOR EMAIL
    </label>

    <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $request->email) }}"
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

{{-- New Password --}}
<div class="mb-5">

    <label
        for="password"
        class="block text-green-700 text-xs tracking-widest mb-2"
    >
        NEW ACCESS KEY
    </label>

    <input
        id="password"
        type="password"
        name="password"
        required
        autocomplete="new-password"
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

{{-- Confirm Password --}}
<div class="mb-8">

    <label
        for="password_confirmation"
        class="block text-green-700 text-xs tracking-widest mb-2"
    >
        CONFIRM ACCESS KEY
    </label>

    <input
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        required
        autocomplete="new-password"
        class="w-full bg-black border border-green-950
               text-green-400 px-4 py-3
               focus:border-green-500
               focus:outline-none"
    >

    @error('password_confirmation')
        <p class="text-red-400 text-xs mt-2">
            {{ $message }}
        </p>
    @enderror

</div>

<button
    type="submit"
    class="w-full bg-green-400 text-black
           py-3 font-bold tracking-widest
           hover:bg-green-300 transition"
>
    UPDATE ACCESS KEY →
</button>

<div class="mt-6 pt-6 border-t border-green-950 text-center">

    <a
        href="{{ route('login') }}"
        class="text-green-400"
    >
        Return to Authentication
    </a>

</div>
```

</form>

@endsection
