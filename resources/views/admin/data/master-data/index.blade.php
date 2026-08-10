@extends('admin.layouts.app')

@section('title')
    Data {{ ucwords($category) }}
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Data {{ ucwords($category) }}</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Dashboard</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Data {{ ucwords($category) }}</span>
        </nav>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        <livewire:user-table category="{{ $category }}" />
    </div>
@endsection
