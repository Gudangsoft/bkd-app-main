{{-- Edit user --}}
<div x-data="{ open: false }" x-init="window.addEventListener('editOpenModal', () => open = true)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md">
            <form action="{{ route('users.update', $uid ?? 0) }}" method="POST" enctype="multipart/form-data"
                class="flex h-full flex-col overflow-y-auto bg-white shadow-xl" x-data="userRoleFields('{{ $role ?? '' }}')">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-accent-700">@lang('dashboard.user.edit') {{ $name ?? '' }}</h3>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                    </button>
                </div>

                <div class="flex-1 space-y-4 px-6 py-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">NIDN</label>
                        <input type="text" name="nidn" value="{{ $nidn ?? '' }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ $name ?? '' }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                        <input type="hidden" name="uid" value="{{ $uid ?? '' }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ $email ?? '' }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Prodi</label>
                        <input type="text" name="progdi" value="{{ $progdi ?? '' }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Asal Kampus</label>
                        <input type="text" name="campus_origin" value="{{ $campus_origin ?? '' }}"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" x-model="role" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                            <option value="guest">Guest</option>
                            <option value="asesor">Asesor</option>
                            <option value="dosen">Dosen</option>
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div x-show="role === 'asesor'" x-cloak>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Status Asesor</label>
                        <select name="status"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                            <option value="user">Pilih Status</option>
                            <option value="internal" {{ ($status ?? '') == 'internal' ? 'selected' : '' }}>Internal</option>
                            <option value="external" {{ ($status ?? '') == 'external' ? 'selected' : '' }}>Eksternal</option>
                            <option value="external_dif" {{ ($status ?? '') == 'external_dif' ? 'selected' : '' }}>Eksternal (di luar kepanitiaan)</option>
                        </select>
                    </div>
                    <div x-show="role !== 'asesor'" x-cloak>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Status Dosen</label>
                        <select name="assessor_fee"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                            <option value="3">Pilih Status</option>
                            <option value="1" {{ ($assessor_fee ?? '') == '1' ? 'selected' : '' }}>Serdos</option>
                            <option value="2" {{ ($assessor_fee ?? '') == '2' ? 'selected' : '' }}>Non Serdos</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-6 py-4">
                    <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
                    <button type="submit" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete single confirmation --}}
<div x-data="{ open: false }" x-init="window.addEventListener('deleteConfirmModal', () => open = true); window.addEventListener('closeDeleteConfirmModal', () => open = false)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="open" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900">@lang('dashboard.user.delete_confirm')</h3>
            <div class="mt-6 flex justify-center gap-2">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="button" wire:click="delete" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- Delete selected (bulk) confirmation --}}
<div x-data="{ open: false }" x-init="window.addEventListener('deleteSelectedConfirmModal', () => open = true); window.addEventListener('closeDeleteSelectedConfirmModal', () => open = false)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="open" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900">@lang('dashboard.user.delete_selected_confirm')</h3>
            <div class="mt-6 flex justify-center gap-2">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="button" wire:click="deleteSelected" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- Toggle active/non-active confirmation --}}
<div x-data="{ open: false }" x-init="window.addEventListener('updateStatusConfirmModal', () => open = true); window.addEventListener('closeUpdateStatusConfirmModal', () => open = false)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="open" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900">@lang('dashboard.user.update_status')</h3>
            <div class="mt-6 flex justify-center gap-2">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="button" wire:click="updateStatus" class="rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-700">@lang('dashboard.yes')</button>
            </div>
        </div>
    </div>
</div>

{{-- Reset password result --}}
<div x-data="{ open: false }" x-init="window.addEventListener('resetPasswordOpenModal', () => open = true)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="open" x-transition class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900">@lang('dashboard.user.reset_password_success')</h3>
            <p class="mt-2 text-sm text-gray-600">Password : <code class="rounded bg-gray-100 px-1.5 py-0.5">bkdstekomoke</code></p>
            <div class="mt-6 flex justify-center">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>
</div>
