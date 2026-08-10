<div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
    @if ($message_restore != null)
        <div class="mb-4 rounded-xl border border-green-100 bg-green-50 p-4 text-sm text-green-700">
            <strong>{{ $message_restore }}</strong>
        </div>
    @endif
    @if ($message_delete != null)
        <div class="mb-4 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
            <strong>{{ $message_delete }}</strong>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Asesor 1</th>
                    <th class="py-2 pr-4">Asesor 2</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4">Tanggal Bayar</th>
                    <th class="py-2 pr-4">Bukti Bayar</th>
                    <th class="py-2 pr-4">Status Asesor 1</th>
                    <th class="py-2 pr-4">Status Asesor 2</th>
                    <th class="py-2 pr-4">Status Pembayaran</th>
                    <th class="py-2 pr-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($data as $item)
                    @php
                        $tint1 = $item->status_accessor_one == 3 ? 'bg-green-50 text-green-700' : ($item->status_accessor_one == 2 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600');
                        $label1 = $item->status_accessor_one == 3 ? 'Memenuhi' : ($item->status_accessor_one == 2 ? 'Penilaian belum bisa dilakukan' : 'Belum ditentukan');
                        $tint2 = $item->status_accessor_two == 3 ? 'bg-green-50 text-green-700' : ($item->status_accessor_two == 2 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600');
                        $label2 = $item->status_accessor_two == 3 ? 'Memenuhi' : ($item->status_accessor_two == 2 ? 'Penilaian belum bisa dilakukan' : 'Belum ditentukan');
                        $tint3 = $item->status == 1 ? 'bg-green-50 text-green-700' : ($item->status == 2 ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600');
                        $label3 = $item->status == 1 ? 'LUNAS' : ($item->status == 2 ? 'DITOLAK' : 'PENDING');
                    @endphp
                    <tr>
                        <td class="py-3 pr-4">{{ $item->user->name }}</td>
                        <td class="py-3 pr-4">{{ $item->assessorOne->name }}</td>
                        <td class="py-3 pr-4">{{ $item->assessorTwo->name }}</td>
                        <td class="py-3 pr-4">Rp {{ number_format($item->amount) }}</td>
                        <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}</td>
                        <td class="py-3 pr-4">
                            <a href="{{ $item->image }}" target="_blank" class="inline-flex text-gray-400 hover:text-accent-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </a>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint1 }}">{{ $label1 }}</span>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint2 }}">{{ $label2 }}</span>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint3 }}">{{ $label3 }}</span>
                        </td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="restore({{ $item->id }})"
                                    class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-gray-700">Restore</button>
                                <button type="button" wire:click="delete({{ $item->id }})"
                                    class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-500">Delete Permanent</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
