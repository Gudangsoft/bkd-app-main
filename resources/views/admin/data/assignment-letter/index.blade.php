@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.assignment_letters.title')
@endsection

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">@lang('dashboard.assignment_letters.title')</h1>
            <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
                <a href="/" class="hover:text-gray-700">Home</a>
                <span class="px-1">/</span>
                <span class="text-gray-700">Data</span>
                <span class="px-1">/</span>
                <span class="text-gray-700">@lang('dashboard.assignment_letters.title')</span>
            </nav>
        </div>

        @if (auth()->user()->hasRole('admin') || (auth()->user()->hasRole('asesor') && !$rekening_check))
            <div x-data="{ createOpen: false }">
                <button type="button" @click="createOpen = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <x-admin.icon name="document" class="h-4 w-4" />
                    <span>@lang('dashboard.assignment_letters.create')</span>
                </button>

                <div x-show="createOpen" x-cloak class="relative z-40" role="dialog" aria-modal="true">
                    <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="createOpen = false"></div>

                    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="createOpen" x-transition:enter="transform transition ease-in-out duration-300"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-300"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="w-screen max-w-md">
                            <form action="{{ route('assignment-letters.store') }}" method="POST" enctype="multipart/form-data"
                                class="flex h-full flex-col overflow-y-auto bg-white shadow-xl" x-data="assignmentLetterForm()">
                                @csrf

                                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                                    <h3 class="text-lg font-semibold text-accent-700">@lang('dashboard.assignment_letters.create')</h3>
                                    <button type="button" @click="createOpen = false" class="text-gray-400 hover:text-gray-600">
                                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                                    </button>
                                </div>

                                <div class="flex-1 space-y-4 px-6 py-4">
                                    @role('admin')
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700">Pilih Asesor</label>
                                            <select name="assessor_id" required @change="onAssessorChange($event.target)"
                                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                                <option value="">-- Pilih Asesor --</option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700">Pilih Dosen <small class="text-gray-400">(hanya dosen dengan status pembayaran sudah lunas)</small></label>
                                            <select name="dosen_id" required
                                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                                <option value="">-- Pilih dosen --</option>
                                                <template x-for="item in dosenOptions" :key="item.id">
                                                    <option :value="item.id" x-text="item.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    @endrole
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Url File Surat</label>
                                        <input type="url" name="url" placeholder="https://drive.google.com/xxxxx"
                                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
                                        <textarea name="description" rows="3"
                                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500"></textarea>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-6 py-4">
                                    <button type="button" @click="createOpen = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
                                    <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
