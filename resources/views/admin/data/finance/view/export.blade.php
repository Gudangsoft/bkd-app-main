<table>
    <thead>
        <tr>
            <th>Status Dosen</th>
            <th>Nama Dosen</th>
            <th>Kampus Asal</th>
            <th>Progdi</th>
            <th>Asesor 1</th>
            <th>Asesor 2</th>
            <th>Nominal (RP)</th>
            <th>Tanggal Bayar</th>
            <th>Bukti Bayar</th>
            <th>Status Asesor 1</th>
            <th>Status Asesor 2</th>
            <th>Status Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $item)
            <tr>
                <td>
                    @if ($item->user->assessor_fee == 1)
                            Serdos
                    @elseif ($item->user->assessor_fee == 2)
                            Non Serdos
                    @else
                        ####
                    @endif
                </td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->user->campus_origin ?? '-' }}</td>
                <td>{{ $item->user->progdi }}</td>
                <td>{{ $item->assessorOne->name ?? 'User Not Found' }}</td>
                <td>{{ $item->assessorTwo->name ?? 'User Not Found' }}</td>
                <td>{{ $item->amount }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ asset('storage/images/proof_of_payment/' . $item->proof_of_payment) }}</td>
                <td>
                    @if ($item->status_accessor_one == 1)
                        Asesor belum ditugaskan
                    @elseif($item->status_accessor_one == 2)
                        Belum dinilai asesor
                    @elseif($item->status_accessor_one == 3)
                        Sudah dinilai asesor
                    @endif
                </td>
                <td>
                    @if ($item->status_accessor_two == 1)
                        Asesor belum ditugaskan
                    @elseif($item->status_accessor_two == 2)
                        Belum dinilai asesor
                    @elseif($item->status_accessor_two == 3)
                        Sudah dinilai asesor
                    @endif
                </td>
                <td>
                    @if ($item->status == 1)
                        LUNAS
                    @elseif($item->status == 0)
                        PENDING
                    @else
                        DITOLAK
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
