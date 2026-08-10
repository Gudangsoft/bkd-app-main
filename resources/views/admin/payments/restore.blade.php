@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.payment.title')
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Data Terhapus</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Data Terhapus</span>
        </nav>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-3 rounded-xl border border-green-100 bg-green-50 p-4 text-sm text-green-700">
            <strong>{{ session('message') }}</strong>
            <button type="button" @click="show = false" class="text-green-500 hover:text-green-700">
                <x-admin.icon name="x-mark" class="h-4 w-4" />
            </button>
        </div>
    @endif

    <livewire:restore-payment-table />
@endsection
