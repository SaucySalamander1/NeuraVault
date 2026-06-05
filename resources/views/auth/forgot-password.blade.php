@extends('layouts.auth')

@section('content')

<div class="mb-8">

```
<div class="text-green-800 text-xs tracking-[0.3em] mb-3">
    // RECOVERY PROTOCOL
</div>

<h1 class="text-3xl font-bold text-green-400 mb-3">
    RESET ACCESS
</h1>

<p class="text-green-700 text-sm leading-relaxed">
    Lost access credentials? Enter your registered email address and a
    secure recovery link will be transmitted to your inbox.
</p>
```

</div>

<x-auth-session-status
 class="mb-6 text-green-400"
 :status="session('status')"
/>

<form method="POST" action="{{ route('password.email') }}">

```
@csrf

<div class="mb-8">

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
        value="{{ old('email') }}"
        required
        autofocus
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

<button
    type="submit"
    class="w-full bg-green-400 text-black
           py-3 font-bold tracking-widest
           hover:bg-green-300 transition"
>
    TRANSMIT RECOVERY LINK →
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
