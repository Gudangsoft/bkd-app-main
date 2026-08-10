<div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
    <div class="mb-4">
        <input wire:model.live="search" type="search" placeholder="Cari nama asesor..."
            class="block w-full max-w-sm rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    <th class="py-2 pr-4">Nama Asesor</th>
                    <th class="py-2 pr-4">Serdos</th>
                    <th class="py-2 pr-4">Nominal Serdos</th>
                    <th class="py-2 pr-4">Non Serdos</th>
                    <th class="py-2 pr-4">Nominal Non Serdos</th>
                    <th class="py-2 pr-4">Jumlah Saldo</th>
                    <th class="py-2 pr-4">Total Terbayar</th>
                    <th class="py-2 pr-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($data as $item)
                    <tr>
                        <td class="py-3 pr-4">
                            {{ $item['user']->name }}
                            <span class="ml-1 inline-flex items-center rounded-full bg-accent-50 px-2 py-0.5 text-[11px] font-medium text-accent-700">{{ $item['assessorStatus'] }}</span>
                        </td>
                        <td class="py-3 pr-4">{{ $item['serdosCount'] }}</td>
                        <td class="py-3 pr-4">
                            @if ($item['serdosCount'] > 0)
                                x {{ number_format($item['serdosFee']) }} = {{ number_format($item['serdosTotal']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td class="py-3 pr-4">{{ $item['nonSerdosCount'] }}</td>
                        <td class="py-3 pr-4">
                            @if ($item['nonSerdosCount'] > 0)
                                x {{ number_format($item['nonSerdosFee']) }} = {{ number_format($item['nonSerdosTotal']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td class="py-3 pr-4 font-semibold text-gray-900">Rp {{ number_format($item['saldoRemaining']) }}</td>
                        <td class="py-3 pr-4">{{ number_format($item['saldoTaken']) }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="pay({{ $item['user_id'] }})"
                                    class="inline-flex items-center gap-1 rounded-lg bg-accent-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-accent-700">
                                    <x-admin.icon name="wallet" class="h-3.5 w-3.5" /> Bayar
                                </button>
                                <a href="{{ route('payment-assessor.show', $item['user_id']) }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Riwayat
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
