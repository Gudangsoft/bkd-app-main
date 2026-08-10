@extends('admin.layouts.app')

@section('title')
    Ads
@endsection

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Ads</h1>
            <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
                <a href="/" class="hover:text-gray-700">Home</a>
                <span class="px-1">/</span>
                <span class="text-gray-700">Ads</span>
            </nav>
        </div>

        <div x-data="{ createOpen: false }">
            <button type="button" @click="createOpen = true"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <x-admin.icon name="megaphone" class="h-4 w-4" />
                <span>Tambah Ads</span>
            </button>

            <div x-show="createOpen" x-cloak class="relative z-40" role="dialog" aria-modal="true">
                <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="createOpen = false"></div>

                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="createOpen" x-transition:enter="transform transition ease-in-out duration-300"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-300"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                        class="w-screen max-w-md">
                        <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data"
                            class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
                            @csrf

                            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-accent-700">Tambah Ads</h3>
                                <button type="button" @click="createOpen = false" class="text-gray-400 hover:text-gray-600">
                                    <x-admin.icon name="x-mark" class="h-5 w-5" />
                                </button>
                            </div>

                            <div class="flex-1 space-y-4 px-6 py-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" name="title" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Type</label>
                                    <select name="type" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        <option value="home_slider">Home Slider</option>
                                        <option value="home_popup">Home Popup</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Url <span class="text-gray-400">(opsional)</span></label>
                                    <input type="url" name="url" placeholder="https://example.com"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Description <span class="text-gray-400">(opsional)</span></label>
                                    <textarea name="description" rows="3"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500"></textarea>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Image</label>
                                    <input type="file" name="image" accept="image/*" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                    <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau GIF. Maksimal 2MB.</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-6 py-4">
                                <button type="button" @click="createOpen = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
                                <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        @livewire('ad-table')
    </div>
@endsection
