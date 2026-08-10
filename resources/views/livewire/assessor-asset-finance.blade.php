<div>
    <section class="scroll-section" id="basic">
        <div class="card mb-5">
            <div class="card-header">
                <input class="form-control" wire:model="search" type="search" placeholder="Cari nama asesor...">
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nama Asesor</th>
                            <th scope="col">Total Saldo</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->getUser->name }}</td>
                                <td>Rp {{ $item->total - $item->saldoTaken }}</td>
                                <td>
                                    <button wire:click='pay({{ $item->user_id }})' class="btn btn-sm btn-primary"><i data-acorn-icon="wallet" class="icon" data-acorn-size="12"></i> Bayar</button>
                                    <a href="{{ route('payment-assessor.show', $item->user_id) }}" class="btn btn-sm btn-light"><i data-acorn-icon="rotate-left" class="icon" data-acorn-size="12"></i> Riwayat Pembayaran</a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-center">
                                {{-- {{ $data->links() }} --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
