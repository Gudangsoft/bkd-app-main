<style>
    .invoice {
        width: 100%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .invoice-header,
    .invoice-footer {
        background-color: #f5f5f5;
        padding: 10px;
    }

    .invoice-body {
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<div class="container" id="contacts">
    <div class="row g-0">
        <div class="invoice">
            <table>
                <tr>
                    <td>
                        <h2>Invoice @lang('dashboard.payment.title')</h2>
                        <p>Date : <span id="invoiceDate">{{ $data['date'] }}</span></p>
                    </td>
                    <td>
                        <h3>Bill To:</h3>
                        <p><b>{{ $data['name'] }}</b></p>
                        <p>{{ $data['progdi'] }}</p>
                    </td>
                </tr>
            </table>
            <div class="invoice-body">
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Nama Asesor</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Asesor 1</td>
                            <td>{{ $data['assessor_one'] }}</td>
                            <td>{{ number_format($data['amount_one']) }}</td>
                        </tr>
                        <tr>
                            <td>Asesor 2</td>
                            <td>{{ $data['assessor_two'] }}</td>
                            <td>{{ number_format($data['amount_two']) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Total</td>
                            <td>{{ number_format($data['amount']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="invoice-footer pt-3">
                <p>Terima kasih telah membayar lunas</p>
            </div>

            {{-- <table>
                <tr>
                    <td style="text-align:right">
                        TTD
                        <br>
                        <br>
                        <br>
                        ADMIN BKD
                    </td>
                </tr>
            </table> --}}
        </div>
    </div>
</div>
