@php
    $isActive = $row->status == 1;
    $tint = $isActive ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700';
@endphp

@if (auth()->user()->hasRole('admin'))
    <button type="button" wire:click="updateStatus({{ $row->id }})"
        class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }}">
        {{ $isActive ? 'Active' : 'Non Active' }}
    </button>
@else
    <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }}">
        {{ $isActive ? 'Active' : 'Non Active' }}
    </span>
@endif
