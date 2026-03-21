<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk - {{ $transaksi->nomor_unik }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #1f2937;
            margin: 0;
            padding: 25px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .header small {
            opacity: 0.8;
        }

        .section {
            padding: 20px;
        }

        .grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
        }

        .box {
            width: 100%;
        }

        .title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
        }

        .muted {
            color: #000000;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 15px 0;
        }

        .product {
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
        }

        .payment {
            background: #eff6ff;
            padding: 15px;
            border-radius: 8px;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid #d1d5db;
            margin-top: 8px;
            padding-top: 8px;
        }

        .status {
            text-align: right;
            margin-top: 10px;
        }

        .badge {
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .kode {
            background: #f1f5f9;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-top: 15px;
        }

        .kode span {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1d4ed8;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="header">
        <h1>FOTO.IN</h1>
        <small>{{ $transaksi->nomor_unik }}</small>
    </div>

    <div class="section">

        <div class="grid">
            <div class="box">
                <div class="title">Pelanggan</div>
                <div class="row"><span class="muted">Nama: </span><span>{{ $transaksi->nama_pelanggan }}</span></div>
                <div class="row"><span class="muted">No HP: </span><span>{{ $transaksi->no_hp_pelanggan }}</span></div>
            </div>

            <div class="box">
                <div class="title">Transaksi</div>
                <div class="row"><span class="muted">Tanggal: </span><span>{{ $transaksi->created_at->format('d/m/Y') }}</span></div>
                <div class="row"><span class="muted">Kasir: </span><span>{{ $transaksi->user->name }}</span></div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="product">
            <div class="row">
                <div>
                    <div style="font-weight: bold;">{{ $transaksi->product->nama_jasa }}</div>
                    <div class="muted" style="font-size: 12px;">
                        {{ $transaksi->jumlah }} x Rp {{ number_format($transaksi->harga_satuan,0,',','.') }}
                    </div>
                </div>
                <div>
                    Rp {{ number_format($transaksi->total_harga,0,',','.') }}
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="payment">
            <div class="row"><span>Total: </span><span>Rp {{ number_format($transaksi->total_harga,0,',','.') }}</span></div>
            <div class="row"><span>Bayar: </span><span>Rp {{ number_format($transaksi->uang_bayar,0,',','.') }}</span></div>
            <div class="row total"><span>Kembali: </span><span>Rp {{ number_format($transaksi->uang_kembali,0,',','.') }}</span></div>

            <div class="status">
                <span class="badge">{{ strtoupper($transaksi->status_pembayaran) }}</span>
            </div>
        </div>

        <div class="kode">
            <div class="muted">Kode Pengambilan</div>
            <span>{{ $transaksi->nomor_unik }}</span>
        </div>

        <div class="footer">
            Terima kasih telah menggunakan layanan Foto.in
        </div>

    </div>

</div>

</body>
</html>