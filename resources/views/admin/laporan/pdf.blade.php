<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>

    <style>
        body {
            font-family: Arial;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        h2 {
            text-align: center;
        }

    </style>

</head>
<body>

    <h2>Laporan Transaksi</h2>

    <table>

        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            @foreach($transaksi as $item)

            <tr>
                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                <td>{{ $item->nama_pelanggan }}</td>
                <td>{{ $item->product->nama_jasa ?? '-' }}</td>
                <td>Rp {{ number_format($item->total_harga,0,',','.') }}</td>
            </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>
