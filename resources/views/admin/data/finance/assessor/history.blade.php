@extends('admin.layouts.app')

@section('title')
    Riwayat Pembayaran Dana Asesor
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Riwayat Pembayaran Dana Asesor</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <a href="{{ route('finances.index', ['category' => 'assessor']) }}" class="hover:text-gray-700">@lang('dashboard.finance.titles') Asesor</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Riwayat Pembayaran Dana Asesor</span>
        </nav>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-3 rounded-xl border border-green-100 bg-green-50 p-4 text-sm text-green-700">
            <strong>{{ session('message') }}</strong>
            <button type="button" @click="show = false" class="text-green-500 hover:text-green-700">
                <x-admin.icon name="x-mark" class="h-4 w-4" />
            </button>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        <div class="divide-y divide-gray-100">
            @foreach ($data as $item)
                <div x-data="{ open: false }" class="flex items-start justify-between gap-4 py-4">
                    <div>
                        <p class="font-medium text-gray-900">{{ $item->date }}</p>
                        <p class="mt-1 text-sm text-gray-600">
                            Rp {{ number_format($item->amount) }}
                            <button type="button" @click="open = true" class="ml-1 inline-flex items-center rounded-full bg-accent-50 px-2 py-0.5 text-[11px] font-medium uppercase text-accent-700 hover:bg-accent-100">Bukti Bayar</button>
                        </p>
                        <p class="mt-1 text-sm text-gray-400">{{ $item->description }}</p>
                    </div>

                    <div x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
                        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4">
                            <div x-show="open" x-transition class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
                                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Bukti Bayar</h3>
                                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                                    </button>
                                </div>
                                <div class="px-6 py-4">
                                    <img src="{{ asset('storage/images/payment_assessor/proof_of_payment/' . $item->proof_of_payment) }}" alt="Bukti Bayar" class="w-full rounded-xl">
                                </div>
                                <div class="flex justify-end border-t border-gray-200 px-6 py-4">
                                    <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
