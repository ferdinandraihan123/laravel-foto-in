<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 10px;
            color: #666;
        }

        .filter-info {
            margin-bottom: 15px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
            font-size: 10px;
        }

        .filter-info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            margin-top: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 4px;
            text-align: right;
        }

        .total-box p {
            margin: 5px 0;
            font-size: 12px;
        }

        .total-box .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI</h1>
        <p>FOTOIN</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i:s') }} | Oleh: {{ auth()->user()->name }}</p>
    </div>

    <div class="filter-info">
        <p><strong>Periode:</strong>
            @if(isset($dari) && $dari && isset($sampai) && $sampai)
            {{ date('d/m/Y', strtotime($dari)) }} - {{ date('d/m/Y', strtotime($sampai)) }}
            @elseif(isset($dari) && $dari)
            Dari {{ date('d/m/Y', strtotime($dari)) }}
            @elseif(isset($sampai) && $sampai)
            Sampai {{ date('d/m/Y', strtotime($sampai)) }}
            @else
            Semua Periode
            @endif
            | Total Data: {{ $transaksi->count() }} transaksi
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="20%">Tanggal</th>
                <th width="25%">Pelanggan</th>
                <th width="30%">Paket</th>
                <th width="10%">Status</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $item)
            <tr>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->nama_pelanggan }}</td>
                <td>{{ $item->product->nama_jasa ?? '-' }}</td>
                <td>
                    @if($item->status == 'selesai')
                    <span style="color: green;">Selesai</span>
                    @elseif($item->status == 'pending')
                    <span style="color: orange;">Pending</span>
                    @elseif($item->status == 'proses')
                    <span style="color: blue;">Proses</span>
                    @else
                    <span style="color: red;">Batal</span>
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($item->total_harga,0,',','.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <p><strong>Ringkasan:</strong></p>
        <p>Total Transaksi: <strong>{{ $transaksi->count() }}</strong> transaksi</p>
        <p class="grand-total">Total Pendapatan: Rp {{ number_format($transaksi->sum('total_harga'),0,',','.') }}</p>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>

</html>