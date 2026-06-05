@extends('layouts.auth')

@section('content')

<div class="mb-8">

```
<div class="text-green-800 text-xs tracking-[0.3em] mb-3">
    // EMAIL VERIFICATION
</div>

<h1 class="text-3xl font-bold text-green-400 mb-3">
    VERIFY OPERATOR
</h1>

<p class="text-green-700 text-sm leading-relaxed">
    Before accessing the intelligence network, verify ownership of your
    registered email address. A verification transmission has been sent
    to your inbox.
</p>
```

</div>

@if (session('status') == 'verification-link-sent')

```
<div class="mb-6 border border-green-900 bg-green-950/20 p-4">

    <div class="text-green-400 text-sm">
        New verification transmission successfully dispatched.
    </div>

</div>
```

@endif

<div class="border border-green-950 p-5 mb-8">

```
<div class="text-green-800 text-xs tracking-widest mb-2">
    STATUS
</div>

<div class="text-green-400">
    Awaiting email verification...
</div>
```

</div>

<form method="POST" action="{{ route('verification.send') }}">

```
@csrf

<button
    type="submit"
    class="w-full bg-green-400 text-black
           py-3 font-bold tracking-widest
           hover:bg-green-300 transition"
>
    RESEND VERIFICATION →
</button>
```

</form>

<div class="mt-6 pt-6 border-t border-green-950">

```
<form method="POST" action="{{ route('logout') }}">

    @csrf

    <button
        type="submit"
        class="w-full border border-green-800
               text-green-400 py-3
               tracking-widest
               hover:bg-green-400
               hover:text-black
               transition"
    >
        TERMINATE SESSION
    </button>

</form>
```

</div>

@endsection
