@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.assessor.data_dosen')
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">@lang('dashboard.assessor.data_dosen')</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">@lang('dashboard.assessor.data_dosen')</span>
        </nav>
    </div>

    <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
        <div>
            <strong>Penting !</strong>
            <ol class="mt-1 list-inside list-decimal">
                <li>Penilaian dapat dilakukan ketika pembayaran sudah lunas.</li>
                <li>Penilaian dapat dilakukan jika asesor sudah menerima surat tugas.</li>
                <li>Penilaian hanya bisa dilakukan oleh asesor terkait.</li>
            </ol>
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

    <livewire:data-dosen-livewire-table />
@endsection
