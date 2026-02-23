<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi - {{ $transaksi->nomor_unik }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1e3a8a;
        }
        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-size: 32px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .kode-unik {
            background-color: #fef3c7;
            border-left: 5px solid #f59e0b;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .kode-unik p {
            margin: 5px 0;
        }
        .kode-unik .kode {
            font-size: 24px;
            font-family: monospace;
            font-weight: bold;
            color: #b45309;
            letter-spacing: 2px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 5px 0;
        }
        .info-table td:first-child {
            width: 100px;
            color: #666;
        }
        .produk-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .produk-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #1e3a8a;
        }
        .produk-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .produk-nama {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .produk-kategori {
            color: #666;
            font-size: 11px;
        }
        .pembayaran {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .pembayaran-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .pembayaran-row.total {
            border-top: 2px solid #1e3a8a;
            margin-top: 8px;
            padding-top: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-badge {
            background-color: #10b981;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .info-pengambilan {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-pengambilan h4 {
            color: #1e3a8a;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .info-pengambilan .kode-besar {
            font-size: 28px;
            font-family: monospace;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 3px;
            background: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px dashed #1e3a8a;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .ttd {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-col {
            text-align: center;
            width: 45%;
        }
        .ttd-line {
            margin-top: 40px;
            padding-top: 5px;
            border-top: 1px solid #333;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>FOTO.IN</h1>
        <p>Capture Your Story</p>
        <p style="font-size: 10px;">Jl. Fotografi No. 123, Jakarta | Telp: 0812-3456-7890</p>
    </div>

    <!-- Kode Unik (PENTING UNTUK AMBIL FOTO) -->
    <div class="kode-unik">
        <p style="font-weight: bold; margin: 0 0 5px 0;">🔑 KODE PENGAMBILAN FOTO:</p>
        <p style="margin: 0;">Simpan kode ini untuk mengambil hasil foto Anda</p>
        <p class="kode">{{ $transaksi->nomor_unik }}</p>
    </div>

    <!-- Info Grid -->
    <div class="info-grid">
        <div>
            <h3 style="margin: 0 0 10px 0; color: #1e3a8a;">Informasi Pelanggan</h3>
            <table class="info-table">
                <tr>
                    <td>Nama</td>
                    <td>: <strong>{{ $transaksi->nama_pelanggan }}</strong></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>: {{ $transaksi->no_hp_pelanggan }}</td>
                </tr>
            </table>
        </div>
        <div>
            <h3 style="margin: 0 0 10px 0; color: #1e3a8a;">Informasi Transaksi</h3>
            <table class="info-table">
                <tr>
                    <td>No. Transaksi</td>
                    <td>: <strong>{{ $transaksi->nomor_unik }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: {{ $transaksi->user->name }}</td>
                </tr>
                <tr>
                    <td>Tanggal Booking</td>
                    <td>: {{ $transaksi->tanggal_booking ? $transaksi->tanggal_booking->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="produk-box">
        <h3>📸 Detail Paket Foto</h3>
        <div class="produk-detail">
            <div>
                <span class="produk-nama">{{ $transaksi->product->nama_jasa }}</span>
                <br>
                <span class="produk-kategori">Kategori: {{ $transaksi->product->kategori->nama_kategori ?? 'Umum' }}</span>
                @if($transaksi->catatan)
                    <br>
                    <span style="color: #666; font-size: 10px;">Catatan: {{ $transaksi->catatan }}</span>
                @endif
            </div>
            <div style="text-align: right;">
                <span>{{ $transaksi->jumlah }} x Rp {{ number_format((float) $transaksi->harga_satuan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Rincian Pembayaran -->
    <div class="pembayaran">
        <h3 style="margin: 0 0 15px 0; color: #1e3a8a;">💰 Rincian Pembayaran</h3>
        <div class="pembayaran-row">
            <span>Total Harga</span>
            <span>Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</span>
        </div>
        <div class="pembayaran-row">
            <span>Uang Bayar</span>
            <span>Rp {{ number_format((float) $transaksi->uang_bayar, 0, ',', '.') }}</span>
        </div>
        <div class="pembayaran-row" style="font-weight: bold; color: #059669;">
            <span>Uang Kembali</span>
            <span>Rp {{ number_format((float) $transaksi->uang_kembali, 0, ',', '.') }}</span>
        </div>
        <div class="pembayaran-row total">
            <span>Status Pembayaran</span>
            <span><span class="status-badge">{{ strtoupper($transaksi->status_pembayaran) }}</span></span>
        </div>
    </div>

    <!-- Informasi Pengambilan Foto -->
    <div class="info-pengambilan">
        <h4>📷 INFORMASI PENGAMBILAN FOTO</h4>
        <p>Foto dapat diambil setelah 3 hari kerja di studio kami.</p>
        <p style="font-weight: bold; margin: 10px 0 5px 0;">Tunjukkan kode unik berikut:</p>
        <div class="kode-besar">{{ $transaksi->nomor_unik }}</div>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd">
        <div class="ttd-col">
            <p>Hormat Kami,</p>
            <div class="ttd-line"></div>
            <p>({{ $transaksi->user->name }})</p>
        </div>
        <div class="ttd-col">
            <p>Pelanggan,</p>
            <div class="ttd-line"></div>
            <p>({{ $transaksi->nama_pelanggan }})</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih telah menggunakan layanan Foto.in</p>
        <p>Struk ini adalah bukti transaksi yang sah</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>