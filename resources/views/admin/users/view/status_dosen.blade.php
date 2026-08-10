@if ($row->assessor_fee == 1)
    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Serdos</span>
@elseif ($row->assessor_fee == 2)
    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Non Serdos</span>
@else
    <span class="text-sm text-gray-400">&mdash;</span>
@endif
