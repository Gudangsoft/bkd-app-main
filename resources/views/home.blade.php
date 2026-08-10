@extends('admin.layouts.app')

@section('title')
    Home - {{ config('app.name') }}
@endsection

@section('content')
    @if ($ad_sliders->isNotEmpty())
        <div x-data="adSlider({{ $ad_sliders->count() }})" class="relative mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-gray-100">
            <div class="relative h-48 w-full sm:h-64 lg:h-80">
                @foreach ($ad_sliders as $i => $ad)
                    <a href="{{ $ad->url ?: '#' }}" @if ($ad->url) target="_blank" @else onclick="return false;" @endif
                        x-show="index === {{ $i }}" x-cloak x-transition.opacity.duration.500ms
                        class="absolute inset-0 block">
                        <img src="{{ $ad->image_path }}" alt="{{ $ad->title }}" class="h-full w-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-4 sm:p-6">
                            <p class="text-base font-semibold text-white sm:text-lg">{{ $ad->title }}</p>
                            @if ($ad->description)
                                <p class="mt-1 max-w-2xl text-sm text-gray-200">{{ \Illuminate\Support\Str::limit($ad->description, 120) }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($ad_sliders->count() > 1)
                <button type="button" @click="prev" aria-label="Previous"
                    class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 text-gray-700 hover:bg-white">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button type="button" @click="next" aria-label="Next"
                    class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 text-gray-700 hover:bg-white">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
                    @foreach ($ad_sliders as $i => $ad)
                        <button type="button" @click="goTo({{ $i }})"
                            class="h-1.5 w-1.5 rounded-full transition-all"
                            :class="index === {{ $i }} ? 'bg-white w-4' : 'bg-white/50'"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Dosen Peserta BKD</h1>
        <nav class="mt-1 text-sm text-gray-500" aria-label="breadcrumb">
            <a href="/" class="hover:text-gray-700">Home</a>
            <span class="px-1">/</span>
            <span class="text-gray-700">Daftar Dosen Peserta BKD</span>
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
        <livewire:data-payment-home-table />
    </div>

    @if ($ad_popup)
        <div x-data="{ open: true }" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60" @click="open = false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div x-show="open" x-transition class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">
                    <div class="relative">
                        <img src="{{ $ad_popup->image_path }}" alt="{{ $ad_popup->title }}" class="max-h-96 w-full object-cover">
                        <button type="button" @click="open = false" class="absolute right-3 top-3 rounded-full bg-white/90 p-1.5 text-gray-700 hover:bg-white">
                            <x-admin.icon name="x-mark" class="h-5 w-5" />
                        </button>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $ad_popup->title }}</h3>
                        @if ($ad_popup->description)
                            <p class="mt-2 text-sm text-gray-600">{{ $ad_popup->description }}</p>
                        @endif
                        @if ($ad_popup->url)
                            <a href="{{ $ad_popup->url }}" target="_blank"
                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">
                                Selengkapnya
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
