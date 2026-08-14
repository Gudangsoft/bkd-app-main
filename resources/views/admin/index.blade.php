@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard {{ config('app.name') }}</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Dashboards</span>
        </nav>
    </div>

    @auth
        @if(auth()->user()->hasRole('dosen') && empty(auth()->user()->campus_origin))
            <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">Lengkapi Profil Anda!</p>
                    <p>Anda belum mengisi <strong>Asal Kampus</strong>. Silakan perbarui profil Anda agar data dapat ditampilkan dengan lengkap.
                        <a href="{{ route('users.profile', auth()->user()->id) }}" class="font-semibold underline">Perbarui sekarang &rarr;</a>
                    </p>
                </div>
                <button type="button" @click="show = false" class="text-red-400 hover:text-red-600">
                    <x-admin.icon name="x-mark" class="h-4 w-4" />
                </button>
            </div>
        @endif
    @endauth

    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Statistik</h2>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-accent-600 text-white">
                <x-admin.icon name="user" class="h-5 w-5" />
            </div>
            <p class="text-sm text-gray-500">Peserta BKD</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $count['bkd_user'] }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-accent-600 text-white">
                <x-admin.icon name="document" class="h-5 w-5" />
            </div>
            <p class="text-sm text-gray-500">Lolos BKD</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $count['bkd_success'] }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-accent-600 text-white">
                <x-admin.icon name="cog" class="h-5 w-5" />
            </div>
            <p class="text-sm text-gray-500">Belum Lolos BKD</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $count['bkd_pending'] }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-accent-600 text-white">
                <x-admin.icon name="user" class="h-5 w-5" />
            </div>
            <p class="text-sm text-gray-500">Asesor</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $count['assessor'] }}</p>
        </div>
    </div>

    <h2 class="mb-3 mt-8 text-sm font-semibold uppercase tracking-wide text-gray-400">Daftar Asesor</h2>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        @foreach ([
            'internal' => 'Asesor Internal',
            'external' => 'Asesor Eksternal',
            'external_dif' => 'Asesor Non Kepanitiaan',
        ] as $key => $label)
            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                <th class="w-8 py-1.5 pr-2">No</th>
                                <th class="py-1.5 pr-2">Nama</th>
                                <th class="py-1.5 pr-2">Asal Kampus</th>
                                <th class="py-1.5">Bidang Studi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($assessors[$key] as $i => $item)
                                <tr>
                                    <td class="py-1.5 pr-2 text-gray-500">{{ $i + 1 }}</td>
                                    <td class="py-1.5 pr-2 text-gray-700">{{ $item->name }}</td>
                                    <td class="py-1.5 pr-2 text-gray-500">{{ $item->campus_origin ?? '-' }}</td>
                                    <td class="py-1.5 text-gray-500">{{ $item->progdi ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-2 text-gray-400">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
