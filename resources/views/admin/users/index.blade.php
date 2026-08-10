@extends('admin.layouts.app')

@section('title')
    @lang('dashboard.user.titles')
@endsection

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">@lang('dashboard.user.titles')</h1>
            <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
                <a href="/" class="hover:text-gray-700">Home</a>
                <span class="px-1">/</span>
                <span class="text-gray-700">@lang('dashboard.user.titles')</span>
            </nav>
        </div>

        <div x-data="{ createOpen: {{ $errors->any() ? 'true' : 'false' }} }">
            <button type="button" @click="createOpen = true"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <x-admin.icon name="user" class="h-4 w-4" />
                <span>@lang('dashboard.user.create')</span>
            </button>

            <div x-show="createOpen" x-cloak class="relative z-40" role="dialog" aria-modal="true">
                <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="createOpen = false"></div>

                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="createOpen" x-transition:enter="transform transition ease-in-out duration-300"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-300"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                        class="w-screen max-w-md">
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data"
                            class="flex h-full flex-col overflow-y-auto bg-white shadow-xl" x-data="userRoleFields()">
                            @csrf

                            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-accent-700">@lang('dashboard.user.create')</h3>
                                <button type="button" @click="createOpen = false" class="text-gray-400 hover:text-gray-600">
                                    <x-admin.icon name="x-mark" class="h-5 w-5" />
                                </button>
                            </div>

                            <div class="flex-1 space-y-4 px-6 py-4">
                                @if ($errors->any())
                                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                        <ul class="list-inside list-disc space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">NIDN</label>
                                    <input type="number" name="nidn" value="{{ old('nidn') }}" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Elon Musk" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="elon@gmail.com" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Asal PT/Prodi</label>
                                    <input type="text" name="progdi" value="{{ old('progdi') }}"
                                        placeholder="UNIVERSITAS SAINS DAN TEKNOLOGI KOMPUTER / Teknik Informatika" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Role</label>
                                    <select name="role" x-model="role" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        <option value="guest">Pilih Role</option>
                                        <option value="asesor">Asesor</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="operator">Operator</option>
                                        <option value="admin">Admin</option>
                                        <option value="guest">Guest</option>
                                    </select>
                                </div>
                                <div x-show="role === 'asesor'" x-cloak>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Status Asesor</label>
                                    <select name="status"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        <option value="user">Pilih status</option>
                                        <option value="internal">Internal</option>
                                        <option value="external">Eksternal</option>
                                        <option value="external_dif">Eksternal (di luar kepanitiaan)</option>
                                    </select>
                                </div>
                                <div x-show="role !== 'asesor'" x-cloak>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Status Dosen</label>
                                    <select name="assessor_fee"
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                                        <option value="">Pilih status</option>
                                        <option value="1">Serdos</option>
                                        <option value="2">Non Serdos</option>
                                        <option value="3">User Normal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Image</label>
                                    <input type="file" name="image" accept="image/*" required
                                        class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
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
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
        <livewire:user-table />
    </div>
@endsection
