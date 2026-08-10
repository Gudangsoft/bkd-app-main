@php
    $tint = $row->status == 'internal'
        ? 'bg-green-50 text-green-700'
        : ($row->status == 'external' ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600');
@endphp

@if ($row->status != 'user')
    <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint }}">{{ ucwords($row->status) }}</span>
@else
    <span class="text-sm text-gray-400">&mdash;</span>
@endif
