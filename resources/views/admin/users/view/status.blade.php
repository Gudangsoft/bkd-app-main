@php
    $isActive = $row->is_active == 1;
@endphp

<button type="button" wire:click="updateStatusConfirm({{ $row->id }})"
    class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $isActive ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
    {{ $isActive ? 'Active' : 'Non Active' }}
</button>
