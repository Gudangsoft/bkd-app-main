@php
    $label = $row->status == 1 ? 'LUNAS' : ($row->status == 2 ? 'DITOLAK' : 'PENDING');
    $tint = $row->status == 1
        ? 'bg-green-50 text-green-700'
        : ($row->status == 2 ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600');
@endphp

<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
    <button type="button" @click="open = !open"
        class="inline-flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }}">
        {{ $label }}
        @role('admin|finance')
            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        @endrole
    </button>

    @role('admin|finance')
        <div x-show="open" x-cloak x-transition class="absolute left-0 z-20 mt-1 w-40 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
            <a href="#" @click.prevent="open = false" wire:click="updatePaymentStatus({{ $row->id }}, 1)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Lunas</a>
            <a href="#" @click.prevent="open = false" wire:click="updatePaymentStatus({{ $row->id }}, 0)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Pending</a>
            <a href="#" @click.prevent="open = false" wire:click="updatePaymentStatus({{ $row->id }}, 2)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Ditolak</a>
        </div>
    @endrole
</div>
