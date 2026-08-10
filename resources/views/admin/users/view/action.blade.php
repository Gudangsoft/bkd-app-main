<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
    <button type="button" @click="open = !open" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100">
        <x-admin.icon name="dots-vertical" class="h-5 w-5" />
    </button>
    <div x-show="open" x-cloak x-transition class="absolute right-0 z-20 mt-1 w-48 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
        <a href="#" @click.prevent="open = false" wire:click="edit({{ $row->id }})" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">
            <x-admin.icon name="pencil" class="h-4 w-4" /> Edit
        </a>
        @if ($row->is_active && $row->id !== auth()->id() && !$row->hasRole('admin'))
            <a href="#" @click.prevent="open = false" wire:click="loginAs({{ $row->id }})" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">
                <x-admin.icon name="user" class="h-4 w-4" /> Login As
            </a>
        @endif
        <a href="#" @click.prevent="open = false" wire:click="resetPassword({{ $row->id }})" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">
            <x-admin.icon name="key" class="h-4 w-4" /> Reset Password
        </a>
        <a href="#" @click.prevent="open = false" wire:click="deleteConfirm({{ $row->id }})" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">
            <x-admin.icon name="trash" class="h-4 w-4" /> Delete
        </a>
    </div>
</div>
