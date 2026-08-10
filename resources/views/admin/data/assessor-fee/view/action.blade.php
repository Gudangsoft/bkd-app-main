<button type="button" wire:click="edit({{ $row->id }})"
    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
    <x-admin.icon name="pencil" class="h-3.5 w-3.5" /> Edit
</button>
