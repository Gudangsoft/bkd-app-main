@php
    $isActive = $row->status == 1;
    $tint = $isActive ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600';
@endphp

@if (auth()->user()->hasAnyRole(['admin', 'asesor']))
    <button type="button" wire:click="updateStatus({{ $row->id }})"
        class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }}">
        {{ $isActive ? 'Diterima' : 'Pending' }}
    </button>
@else
    <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }}">
        {{ $isActive ? 'Active' : 'Non Active' }}
    </span>
@endif
