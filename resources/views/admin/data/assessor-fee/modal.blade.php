{{-- Edit assessor fee --}}
<div x-data="{ open: false }" x-init="window.addEventListener('editOpenModal', () => open = true)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md">
            <form action="{{ route('assessor-fee.update', $selected_id ?? 0) }}" method="POST" enctype="multipart/form-data"
                class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-accent-700">Edit @lang('dashboard.finance.assessor_fee')</h3>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                    </button>
                </div>

                <div class="flex-1 space-y-4 px-6 py-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" value="{{ $name ?? '' }}" disabled
                            class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Internal</label>
                        <input type="number" name="internal" value="{{ $internal ?? '' }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Eksternal</label>
                        <input type="number" name="external" value="{{ $external ?? '' }}" required
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
