@extends('admin.layouts.app')

@section('title')
    {{ auth()->user()->name }}
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ auth()->user()->name }}</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <a href="/dashboard" class="hover:text-gray-700">Dashboard</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Profile</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex flex-col items-center text-center">
                <img src="{{ auth()->user()->imagePath ? auth()->user()->imagePath : asset('assets/img/profile/profile-default.png') }}"
                    class="mb-3 h-24 w-24 rounded-2xl object-cover" alt="{{ auth()->user()->name }}" />
                <p class="text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-sm uppercase text-accent-600">{{ auth()->user()->getRoleNames()[0] ?? 'guest' }} {{ auth()->user()->status }}</p>
            </div>
            <a href="{{ route('rekening-setting.index') }}"
                class="mt-6 flex items-center gap-2 rounded-lg border-t border-gray-100 px-2 py-3 text-sm text-gray-600 hover:bg-gray-50">
                <x-admin.icon name="wallet" class="h-4 w-4" />
                <span>Rekening</span>
            </a>
        </div>

        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-400">Setting Profile</h2>
                <form action="{{ route('profile-update', ['id' => auth()->user()->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">NIDN</label>
                        <input type="number" name="nidn" value="{{ auth()->user()->nidn }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Photo</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Progdi</label>
                        <input type="text" name="progdi" value="{{ auth()->user()->progdi }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Asal Kampus</label>
                        <input type="text" name="campus_origin" value="{{ auth()->user()->campus_origin }}"
                            placeholder="Masukkan nama kampus Anda"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                        <select name="assessor_fee"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                            <option value="3">Pilih status</option>
                            <option value="1" {{ auth()->user()->assessor_fee == 1 ? 'selected' : '' }}>Serdos</option>
                            <option value="2" {{ auth()->user()->assessor_fee == 2 ? 'selected' : '' }}>Non Serdos</option>
                        </select>
                    </div>
                    <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.save')</button>
                </form>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-400">Setting Password</h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile-update-password', ['id' => auth()->user()->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Password saat ini</label>
                        <input type="password" name="password_current"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Password baru</label>
                        <input type="password" name="new_password"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Konfirmasi password baru</label>
                        <input type="password" name="new_password_confirmation"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
