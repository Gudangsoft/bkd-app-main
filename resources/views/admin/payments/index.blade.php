@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.payment.title')
@endsection

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">@lang('dashboard.payment.titles')</h1>
            <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
                <a href="/" class="hover:text-gray-700">Home</a>
                <span class="px-1">/</span>
                <span class="text-gray-700">@lang('dashboard.payment.titles')</span>
            </nav>
        </div>

        <div class="flex items-center gap-2" x-data="{ createOpen: false }">
            @role('admin|dosen')
                <button type="button" @click="createOpen = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    @role('admin')
                        <x-admin.icon name="wallet" class="h-4 w-4" />
                        <span>@lang('dashboard.payment.create')</span>
                    @endrole
                    @role('dosen')
                        <span>Konfirmasi Administrasi Asesor</span>
                    @endrole
                </button>
            @endrole
            @role('admin')
                <a href="/payments-trashed"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Data Terhapus
                </a>
            @endrole

            {{-- Create payment slide-over --}}
            <div x-show="createOpen" x-cloak class="relative z-40" role="dialog" aria-modal="true">
                <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="createOpen = false"></div>

                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="createOpen" x-transition:enter="transform transition ease-in-out duration-300"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-300"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                        class="w-screen max-w-md">
                        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data"
                            class="flex h-full flex-col overflow-y-auto bg-white shadow-xl"
                            x-data="paymentFeeCalculator({{ auth()->user()->hasRole('admin') ? 'null' : auth()->user()->id }})">
                            @csrf

                            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-accent-700">
                                    @role('admin')
                                        @lang('dashboard.payment.create')
                                    @endrole
                                    @role('dosen')
                                        Konfirmasi Administrasi Asesor
                                    @endrole
                                </h3>
                                <button type="button" @click="createOpen = false" class="text-gray-400 hover:text-gray-600">
                                    <x-admin.icon name="x-mark" class="h-5 w-5" />
                                </button>
                            </div>

                            <div class="flex-1 space-y-4 px-6 py-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Nama Dosen</label>
                                    @if (auth()->user()->hasRole('admin'))
                                        <select name="user_list[]" multiple @change="onDosenChange($event.target)"
                                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="user_list[]" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm" disabled>
                                            <option selected>{{ auth()->user()->name }}</option>
                                        </select>
                                        <input type="hidden" name="user_list[]" value="{{ auth()->user()->id }}">
                                    @endif
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.assessor_one')</label>
                                    <select name="assessor_one[]" multiple @change="onAssessorOneChange($event.target)"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        @foreach ($assessor as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                ({{ $item->status == 'external_dif' ? 'external non kepanitiaan' : $item->status }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.assessor_two')</label>
                                    <select name="assessor_two[]" multiple @change="onAssessorTwoChange($event.target)"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        @foreach ($assessor as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                ({{ $item->status == 'external_dif' ? 'external non kepanitiaan' : $item->status }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <dl class="grid grid-cols-2 gap-2 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm">
                                    <dt class="text-gray-500">Status Serdos</dt>
                                    <dd class="text-right font-medium text-gray-900" x-text="serdosStatus"></dd>
                                    <dt class="text-gray-500">Bea Asesor 1</dt>
                                    <dd class="text-right font-medium text-gray-900" x-text="formatRupiah(amountOne) + (assessorOneStatus ? ' (' + assessorOneStatus + ')' : '')"></dd>
                                    <dt class="text-gray-500">Bea Asesor 2</dt>
                                    <dd class="text-right font-medium text-gray-900" x-text="formatRupiah(amountTwo) + (assessorTwoStatus ? ' (' + assessorTwoStatus + ')' : '')"></dd>
                                    <dt class="font-semibold text-gray-700">Jumlah</dt>
                                    <dd class="text-right font-semibold text-accent-700" x-text="formatRupiah(total)"></dd>
                                </dl>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.amount') harus dibayar</label>
                                    <input type="hidden" name="bea_asesor_one" :value="amountOne">
                                    <input type="hidden" name="bea_asesor_two" :value="amountTwo">
                                    <input type="hidden" name="amount" :value="total">
                                    <input readonly type="text" :value="formatRupiah(total)"
                                        class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm">
                                </div>

                                <div x-show="total > 0" x-cloak>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.rekening_payment')</label>
                                    <select name="rekening_id" class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        @foreach ($rekening as $item)
                                            <option value="{{ $item->id }}">{{ $item->jenis_rekening }} {{ $item->nomor_rekening }} a.n {{ $item->nama_nasabah }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-red-600">@lang('dashboard.payment.payment_info')</p>
                                </div>

                                <div x-show="total > 0" x-cloak>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.proof_of_payment')</label>
                                    <input type="file" name="image" accept="image/*"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                    <p class="mt-1 text-xs text-red-600">@lang('dashboard.payment.image_info')</p>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.description') (opsional)</label>
                                    <textarea name="description" rows="3"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500"></textarea>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-6 py-4">
                                <button type="button" @click="createOpen = false"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
                                <button type="submit"
                                    class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

    @role('admin')
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Total Data</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($data['total_data']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Total Pembayaran</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($data['total_amount']) }}</p>
            </div>
        </div>
    @endrole

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        <livewire:payment-table uid='{{ $uid }}' />
    </div>
@endsection
