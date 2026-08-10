@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.assignment_letters.title')
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">@lang('dashboard.assignment_letters.title')</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Data</span>
            <span class="px-1">/</span>
            <span class="text-gray-700">@lang('dashboard.assignment_letters.title')</span>
        </nav>
    </div>

    <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
        <div>
            <strong>Penting !</strong>
            <ul class="mt-1 list-inside list-disc">
                <li>Untuk tanda terima surat, silakan klik tombol pada status surat hingga status diterima.</li>
            </ul>
        </div>
        <button type="button" @click="show = false" class="text-blue-500 hover:text-blue-700">
            <x-admin.icon name="x-mark" class="h-4 w-4" />
        </button>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-3 rounded-xl border border-green-100 bg-green-50 p-4 text-sm text-green-700">
            <strong>{{ session('message') }}</strong>
            <button type="button" @click="show = false" class="text-green-500 hover:text-green-700">
                <x-admin.icon name="x-mark" class="h-4 w-4" />
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        @livewire('assignment-letter-table')
    </div>
@endsection
