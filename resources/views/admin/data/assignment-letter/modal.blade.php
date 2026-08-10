{{-- Edit assignment letter --}}
<div x-data="{ open: false }" x-init="window.addEventListener('editOpenModal', () => open = true)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md">
            <form action="{{ route('assignment-letters.update', $selected_id ?? 0) }}" method="POST" enctype="multipart/form-data"
                class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-accent-700">Edit @lang('dashboard.assignment_letters.title')</h3>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                    </button>
                </div>

                <div class="flex-1 space-y-4 px-6 py-4">
                    @role('admin')
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Pilih Asesor</label>
                            <select class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm" disabled>
                                <option>{{ $assessor ?? '' }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Pilih Dosen</label>
                            <select class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm" disabled>
                                <option>{{ $dosen ?? '' }}</option>
                            </select>
                        </div>
                    @endrole
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">File Surat</label>
                        <input type="file" name="file_surat"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Url File Surat</label>
                        <input type="url" name="url" value="{{ $url ?? '' }}" placeholder="https://drive.google.com/xxxxx"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
                        <textarea name="description" rows="3"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">{{ $description ?? '' }}</textarea>
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

{{-- Delete confirmation --}}
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
