@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.finance.title') Asesor
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Keuangan Asesor</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">@lang('dashboard.finance.titles') Asesor</span>
        </nav>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-accent-600 text-white">
                <x-admin.icon name="wallet" class="h-5 w-5" />
            </div>
            <p class="text-sm text-gray-500">Total Saldo</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($total_saldo) }}</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-3 rounded-xl border border-green-100 bg-green-50 p-4 text-sm text-green-700">
            <strong>{{ session('message') }}</strong>
            <button type="button" @click="show = false" class="text-green-500 hover:text-green-700">
                <x-admin.icon name="x-mark" class="h-4 w-4" />
            </button>
        </div>
    @endif

    <livewire:assessor-finace />

    {{-- Pay assessor slide-over --}}
    <div x-data="{ open: false, assessorId: null }" x-init="window.addEventListener('openModalPay', (e) => { assessorId = e.detail[0].uid; open = true })"
        x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
        <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
            <div x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="w-screen max-w-md">
                <form action="{{ route('payment-assessor.store') }}" method="POST" enctype="multipart/form-data"
                    class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
                    @csrf
                    <input type="hidden" name="assessor_id" :value="assessorId">

                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-accent-700">Pembayaran Asesor</h3>
                        <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                            <x-admin.icon name="x-mark" class="h-5 w-5" />
                        </button>
                    </div>

                    <div class="flex-1 space-y-4 px-6 py-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Pilih Saldo Rekening</label>
                            <select name="rekening_id" multiple required
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                @foreach ($rekening as $item)
                                    <option value="{{ $item->id }}">{{ $item->jenis_rekening }}
                                        {{ $item->nomor_rekening }} a.n {{ $item->nama_nasabah }} | Saldo {{ number_format($item->saldo) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="amount" placeholder="0" required
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Bukti Transfer</label>
                            <input type="file" name="bukti_bayar" accept="image/*" required
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                            <p class="mt-1 text-xs text-red-600">@lang('dashboard.payment.image_info')</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-6 py-4">
                        <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
                        <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
