@extends('layouts.auth')

@section('content')

<div class="mb-8">

```
<div class="text-green-800 text-xs tracking-[0.3em] mb-3">
    // SECURITY CHECKPOINT
</div>

<h1 class="text-3xl font-bold text-green-400 mb-3">
    CONFIRM IDENTITY
</h1>

<p class="text-green-700 text-sm leading-relaxed">
    You are attempting to access a protected area of the intelligence
    platform. Re-enter your access key to continue.
</p>
```

</div>

<form method="POST" action="{{ route('password.confirm') }}">

```
@csrf

<div class="mb-8">

    <label
        for="password"
        class="block text-green-700 text-xs tracking-widest mb-2"
    >
        ACCESS KEY
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

<button
    type="submit"
    class="w-full bg-green-400 text-black
           py-3 font-bold tracking-widest
           hover:bg-green-300 transition"
>
    AUTHORIZE ACCESS →
</button>
```

</form>

@endsection
