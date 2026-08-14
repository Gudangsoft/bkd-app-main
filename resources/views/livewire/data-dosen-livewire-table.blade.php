<div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">
    <div class="mb-4">
        <input wire:model="search" type="search" placeholder="Cari nama dosen..."
            class="block w-full max-w-sm rounded-lg border-gray-300 text-sm focus:border-accent-500 focus:ring-accent-500">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    <th class="py-2 pr-4">Nama Dosen</th>
                    <th class="py-2 pr-4">Asesor 1</th>
                    <th class="py-2 pr-4">Asesor 2</th>
                    <th class="py-2 pr-4">Penilaian Asesor 1</th>
                    <th class="py-2 pr-4">Penilaian Asesor 2</th>
                    <th class="py-2 pr-4">Status Pembayaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($data as $row)
                    <tr>
                        <td class="py-3 pr-4">{{ $row->user->name }}</td>
                        <td class="py-3 pr-4">{{ $row->assessorOne->name }}<br><span class="font-semibold text-accent-700">Rp {{ number_format($row->amount_one) }}</span></td>
                        <td class="py-3 pr-4">{{ $row->assessorTwo->name }}<br><span class="font-semibold text-accent-700">Rp {{ number_format($row->amount_two) }}</span></td>
                        <td class="py-3 pr-4">
                            @if ($row->assignmentLetterStatusOne($row->user_id)['status'] == true)
                                @php
                                    $label1 = $row->status_accessor_one == 3 ? 'Sudah dinilai asesor' : ($row->status_accessor_one == 2 ? 'Belum dinilai asesor' : 'Asesor belum ditugaskan');
                                    $tint1 = $row->status_accessor_one == 3 ? 'bg-green-50 text-green-700' : ($row->status_accessor_one == 2 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600');
                                    $disabled1 = $row->status != 1 || $row->assessor_one_id != auth()->user()->id;
                                @endphp
                                <div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
                                    <button type="button" @click="open = !open" {{ $disabled1 ? 'disabled' : '' }}
                                        class="inline-flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint1 }} disabled:opacity-60">
                                        {{ $label1 }}
                                        @role('asesor')
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endrole
                                    </button>
                                    @role('asesor')
                                        <div x-show="open" x-cloak x-transition class="absolute left-0 z-20 mt-1 w-56 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
                                            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorOne({{ $row->id }}, 3)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Sudah dinilai asesor</a>
                                            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorOne({{ $row->id }}, 2)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Belum dinilai asesor</a>
                                            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorOne({{ $row->id }}, 1)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Asesor belum ditugaskan</a>
                                        </div>
                                    @endrole
                                </div>
                            @else
                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">Menunggu surat tugas</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            @if ($row->assignmentLetterStatusTwo($row->user_id)['status'] == true)
                                @php
                                    $label2 = $row->status_accessor_two == 3 ? 'Sudah dinilai asesor' : ($row->status_accessor_two == 2 ? 'Belum dinilai asesor' : 'Asesor belum ditugaskan');
                                    $tint2 = $row->status_accessor_two == 3 ? 'bg-green-50 text-green-700' : ($row->status_accessor_two == 2 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600');
                                    $disabled2 = $row->status != 1 || $row->assessor_two_id != auth()->user()->id;
                                @endphp
                                <div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
                                    <button type="button" @click="open = !open" {{ $disabled2 ? 'disabled' : '' }}
                                        class="inline-flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint2 }} disabled:opacity-60">
                                        {{ $label2 }}
                                        @role('asesor')
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endrole
                                    </button>
                                    @role('asesor')
                                        <div x-show="open" x-cloak x-transition class="absolute left-0 z-20 mt-1 w-56 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
                                            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorOne({{ $row->id }}, 3)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Sudah dinilai asesor</a>
                                            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorOne({{ $row->id }}, 2)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Belum dinilai asesor</a>
                                            <a href="#" @click.prevent="open = false" wire:click="updateStatusAccessorOne({{ $row->id }}, 1)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Asesor belum ditugaskan</a>
                                        </div>
                                    @endrole
                                </div>
                            @else
                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">Menunggu surat tugas</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            @php
                                $label3 = $row->status == 1 ? 'LUNAS' : ($row->status == 2 ? 'DITOLAK' : 'PENDING');
                                $tint3 = $row->status == 1 ? 'bg-green-50 text-green-700' : ($row->status == 2 ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600');
                            @endphp
                            <div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block">
                                <button type="button" @click="open = !open"
                                    class="inline-flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $tint3 }}">
                                    {{ $label3 }}
                                    @role('admin|finance')
                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    @endrole
                                </button>
                                @role('admin|finance')
                                    <div x-show="open" x-cloak x-transition class="absolute left-0 z-20 mt-1 w-40 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
                                        <a href="#" @click.prevent="open = false" wire:click="updatePaymentStatus({{ $row->id }}, 1)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Lunas</a>
                                        <a href="#" @click.prevent="open = false" wire:click="updatePaymentStatus({{ $row->id }}, 0)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Pending</a>
                                        <a href="#" @click.prevent="open = false" wire:click="updatePaymentStatus({{ $row->id }}, 2)" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Ditolak</a>
                                    </div>
                                @endrole
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $data->links() }}
    </div>
</div>
