@extends('admin.layouts.app')

@section('title')
    Buku Panduan
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Buku Panduan</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Buku Panduan</span>
        </nav>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        <div id="pdfViewer"></div>
    </div>
@endsection

@push('styles')
    <style>
        .pdfobject-container {
            height: 800px;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.5/pdfobject.min.js"></script>
    <script>
        PDFObject.embed(@json($data->pdfManualBook ?? ''), "#pdfViewer");
    </script>
@endpush
