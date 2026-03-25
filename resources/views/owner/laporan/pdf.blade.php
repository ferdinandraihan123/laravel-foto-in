<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .date {
            text-align: right;
            margin-bottom: 20px;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Transaksi</h2>
        <p>Lontar Fotografi</p>
    </div>

    <div class="date">
        <p>Periode:
            @if($dari && $sampai)
            {{ date('d/m/Y', strtotime($dari)) }} - {{ date('d/m/Y', strtotime($sampai)) }}
            @elseif($dari)
            Dari {{ date('d/m/Y', strtotime($dari)) }}
            @elseif($sampai)
            Sampai {{ date('d/m/Y', strtotime($sampai)) }}
            @else
            Semua Periode
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $item)
            <tr>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->nama_pelanggan }}</td>
                <td>{{ $item->product->nama_jasa ?? '-' }}</td>
                <td>Rp {{ number_format($item->total_harga,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Pendapatan: Rp {{ number_format($transaksi->sum('total_harga'),0,',','.') }}</p>
        <p>Total Transaksi: {{ $transaksi->count() }} transaksi</p>
    </div>
</body>
</html>
