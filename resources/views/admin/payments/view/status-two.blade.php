@php
    $label = $row->status_accessor_two == 3 ? 'Memenuhi' : ($row->status_accessor_two == 2 ? 'Penilaian belum bisa dilakukan' : 'Belum ditentukan');
    $tint = $row->status_accessor_two == 3
        ? 'bg-green-50 text-green-700'
        : ($row->status_accessor_two == 2 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600');
@endphp

<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
    <button type="button" @click="open = !open" {{ $row->status == 2 ? 'disabled' : '' }}
        class="inline-flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }} disabled:opacity-60">
        {{ $label }}
        @role('admin|operator')
            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        @endrole
    </button>

    @role('admin|operator')
        <div x-show="open" x-cloak x-transition class="absolute left-0 z-20 mt-1 w-56 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorTwo({{ $row->id }}, 3)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Memenuhi</a>
            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorTwo({{ $row->id }}, 2)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Penilaian belum bisa dilakukan</a>
            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorTwo({{ $row->id }}, 1)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Belum ditentukan</a>
        </div>
    @endrole
</div>
