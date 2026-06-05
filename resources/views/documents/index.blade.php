{{-- resources/views/documents/index.blade.php --}}
@extends('layouts.terminal')

@section('title', 'DOCUMENT_VAULT')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-6 px-4 py-3 border border-green-500 bg-green-950 text-green-400 text-xs tracking-widest">
        &gt; {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 px-4 py-3 border border-red-500 bg-red-950 text-red-400 text-xs tracking-widest">
        &gt; {{ session('error') }}
    </div>
@endif

{{-- Page Header --}}
<div class="mb-8">
    <div class="text-green-400 text-xl font-bold tracking-widest">DOCUMENT_VAULT</div>
    <div class="text-green-700 text-xs mt-1 tracking-widest">// UPLOAD AND MANAGE YOUR DOCUMENTS</div>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="border border-green-900 bg-green-950/20 p-4">
        <div class="text-green-700 text-xs tracking-widest">TOTAL DOCUMENTS</div>
        <div class="text-green-400 text-2xl font-bold mt-1">{{ $totalDocs }}</div>
    </div>
    <div class="border border-green-900 bg-green-950/20 p-4">
        <div class="text-green-700 text-xs tracking-widest">STORAGE USED</div>
        <div class="text-green-400 text-2xl font-bold mt-1">
            @if($totalSize >= 1048576)
                {{ number_format($totalSize / 1048576, 2) }} MB
            @elseif($totalSize >= 1024)
                {{ number_format($totalSize / 1024, 2) }} KB
            @else
                {{ $totalSize }} B
            @endif
        </div>
    </div>
    <div class="border border-green-900 bg-green-950/20 p-4">
        <div class="text-green-700 text-xs tracking-widest">PENDING PROCESSING</div>
        <div class="text-yellow-400 text-2xl font-bold mt-1">
            {{ $documents->where('status', 'pending')->count() }}
        </div>
    </div>
</div>

{{-- Upload Zone --}}
<div class="border border-green-800 bg-green-950/10 p-6 mb-8">
    <div class="text-green-600 text-xs tracking-widest mb-4">&gt; UPLOAD_NEW_DOCUMENT</div>

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Drag & Drop Zone --}}
        <div id="drop-zone"
             class="border-2 border-dashed border-green-800 p-8 text-center cursor-pointer
                    hover:border-green-500 hover:bg-green-950/30 transition-all"
             onclick="document.getElementById('file-input').click()">

            <div class="text-green-600 text-xs tracking-widest" id="drop-text">
                &gt; DRAG AND DROP FILE HERE OR CLICK TO SELECT<br>
                <span class="text-green-800 mt-2 block">ACCEPTED: PDF, TXT — MAX 10MB</span>
            </div>

            <div id="file-preview" class="hidden mt-4">
                <div class="text-green-400 text-xs tracking-widest" id="file-name"></div>
                <div class="text-green-700 text-xs mt-1" id="file-size"></div>
            </div>
        </div>

        <input type="file"
               id="file-input"
               name="file"
               accept=".pdf,.txt"
               class="hidden">

        {{-- Validation Errors --}}
        @error('file')
            <div class="mt-3 text-red-400 text-xs tracking-widest">&gt; ERROR: {{ $message }}</div>
        @enderror

        <button type="submit"
                class="mt-4 px-6 py-2 bg-green-400 text-black text-xs font-bold tracking-widest
                       hover:bg-green-300 transition-colors">
            [ UPLOAD DOCUMENT ]
        </button>
    </form>
</div>

{{-- Documents Table --}}
<div class="border border-green-900">
    <div class="px-6 py-3 border-b border-green-900 text-green-600 text-xs tracking-widest">
        &gt; DOCUMENT_INDEX
    </div>

    @if($documents->count() > 0)
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-green-900 text-green-700 tracking-widest">
                    <th class="px-6 py-3 text-left">NAME</th>
                    <th class="px-6 py-3 text-left">TYPE</th>
                    <th class="px-6 py-3 text-left">SIZE</th>
                    <th class="px-6 py-3 text-left">STATUS</th>
                    <th class="px-6 py-3 text-left">UPLOADED</th>
                    <th class="px-6 py-3 text-left">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr class="border-b border-green-900/50 hover:bg-green-950/20 transition-colors">
                        <td class="px-6 py-4 text-green-400 max-w-xs truncate">
                            {{ $document->original_name }}
                        </td>
                        <td class="px-6 py-4 text-green-600 uppercase">
                            {{ $document->mime_type === 'application/pdf' ? 'PDF' : 'TXT' }}
                        </td>
                        <td class="px-6 py-4 text-green-600">
                            {{ $document->formatted_size }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="tracking-widest {{ $document->status_color }}">
                                {{ strtoupper($document->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-green-700">
                            {{ $document->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('documents.show', $document) }}"
                                   class="text-green-600 hover:text-green-400 tracking-widest transition-colors">
                                    [ VIEW ]
                                </a>
                                <form action="{{ route('documents.destroy', $document) }}"
                                      method="POST"
                                      onsubmit="return confirm('DELETE THIS DOCUMENT?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-700 hover:text-red-500 tracking-widest transition-colors">
                                        [ DELETE ]
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($documents->hasPages())
            <div class="px-6 py-4 border-t border-green-900 text-xs text-green-700">
                {{ $documents->links() }}
            </div>
        @endif

    @else
        <div class="px-6 py-12 text-center">
            <div class="text-green-800 text-xs tracking-widest">&gt; NO DOCUMENTS FOUND</div>
            <div class="text-green-900 text-xs mt-2 tracking-widest">UPLOAD YOUR FIRST DOCUMENT ABOVE</div>
        </div>
    @endif
</div>

{{-- Vanilla JS: drag & drop + file preview --}}
<script>
const dropZone  = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const dropText  = document.getElementById('drop-text');
const preview   = document.getElementById('file-preview');
const fileName  = document.getElementById('file-name');
const fileSize  = document.getElementById('file-size');

function formatBytes(bytes) {
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
    if (bytes >= 1024)    return (bytes / 1024).toFixed(2) + ' KB';
    return bytes + ' B';
}

function showPreview(file) {
    dropText.classList.add('hidden');
    preview.classList.remove('hidden');
    fileName.textContent = '> FILE: ' + file.name;
    fileSize.textContent = 'SIZE: ' + formatBytes(file.size);
}

fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) showPreview(fileInput.files[0]);
});

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-green-400', 'bg-green-950/40');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-green-400', 'bg-green-950/40');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-green-400', 'bg-green-950/40');
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        showPreview(file);
    }
});
</script>

@endsection