{{-- Proof of payment viewer --}}
<div x-data="{ open: false }" x-init="window.addEventListener('proofOfPaymentModal', () => open = true)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="open" x-transition class="flex max-h-[90vh] w-full max-w-lg flex-col rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">@lang('dashboard.payment.proof_of_payment')</h3>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <x-admin.icon name="x-mark" class="h-5 w-5" />
                </button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4">
                @if ($image)
                    <a href="{{ $image }}" target="_blank">
                        <img src="{{ $image }}" alt="{{ $name }}" class="w-full rounded-xl">
                    </a>
                @endif
                <p class="mt-3 text-sm text-gray-600">{{ $description }}</p>
            </div>
            <div class="flex justify-end border-t border-gray-200 px-6 py-4">
                <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit payment --}}
<div x-data="{ open: false }" x-init="window.addEventListener('editOpenModal', () => open = true)" x-show="open" x-cloak class="relative z-40" role="dialog" aria-modal="true">
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>
    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md">
            <form action="{{ route('payments.update', $selected_id ?? 0) }}" method="POST" enctype="multipart/form-data"
                class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-accent-700">@lang('dashboard.payment.create')</h3>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                    </button>
                </div>

                <div class="flex-1 space-y-4 px-6 py-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Nama Dosen</label>
                        <select class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm" disabled>
                            <option selected>{{ $name ?? '' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.assessor_one')</label>
                        <select class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm" disabled>
                            @foreach ($assessor as $item)
                                <option {{ $item->id == $assessor_one ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.assessor_two')</label>
                        <select class="block w-full rounded-lg border-gray-300 bg-gray-50 text-sm" disabled>
                            @foreach ($assessor as $item)
                                <option {{ $item->id == $assessor_two ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.amount')</label>
                        <input type="number" name="amount" value="{{ $amount ?? '' }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.rekening_payment')</label>
                        <select name="rekening_id" class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                            @foreach ($rekenings as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $rekening_id ? 'selected' : '' }}>
                                    {{ $item->jenis_bank }} {{ $item->nomor_rekening }} a.n {{ $item->nama_nasabah }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-red-600">@lang('dashboard.payment.payment_info')</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.proof_of_payment')</label>
                        <input type="file" name="image" accept="image/*"
                            class="block w-full rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">@lang('dashboard.payment.description') (opsional)</label>
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
