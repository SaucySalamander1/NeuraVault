@extends('layouts.terminal')

@section('content')

<div class="p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold">
            DASHBOARD
        </h1>

        <p class="text-green-700 mt-2">
            Intelligence Overview
        </p>
    </div>

    <div class="grid grid-cols-4 gap-4">

        <div class="border border-green-900 p-4">
            <div class="text-green-700 text-xs">
                DOCUMENTS
            </div>

            <div class="text-3xl mt-2">
                0
            </div>
        </div>

        <div class="border border-green-900 p-4">
            <div class="text-green-700 text-xs">
                CHATS
            </div>

            <div class="text-3xl mt-2">
                0
            </div>
        </div>

        <div class="border border-green-900 p-4">
            <div class="text-green-700 text-xs">
                FINDINGS
            </div>

            <div class="text-3xl mt-2">
                0
            </div>
        </div>

        <div class="border border-green-900 p-4">
            <div class="text-green-700 text-xs">
                KNOWLEDGE NODES
            </div>

            <div class="text-3xl mt-2">
                0
            </div>
        </div>

    </div>

</div>

@endsection