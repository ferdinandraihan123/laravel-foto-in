@extends('layouts.app')

@section('title', 'Struk Transaksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-wide">FOTO.IN</h1>
                    <p class="text-sm text-blue-100">Capture Your Story</p>
                </div>
                <div class="text-right">
                    <p class="text-sm">Struk Transaksi</p>
                    <p class="text-lg font-semibold">{{ $transaksi->nomor_unik }}</p>
                </div>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="p-8">

            {{-- INFO --}}
            <div class="grid grid-cols-2 gap-8 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Pelanggan</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-500 w-28">Nama</td>
                            <td class="text-gray-800">: {{ $transaksi->nama_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">No. HP</td>
                            <td class="text-gray-700">: {{ $transaksi->no_hp_pelanggan }}</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Transaksi</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-500 w-28">Booking</td>
                            <td class="text-gray-700">: {{ $transaksi->tanggal_booking ? $transaksi->tanggal_booking->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Kasir</td>
                            <td class="text-gray-700">: {{ $transaksi->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Tanggal</td>
                            <td class="text-gray-700">: {{ $transaksi->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-6">

            {{-- DETAIL PAKET --}}
            <h3 class="font-semibold text-gray-700 mb-3">Detail Paket</h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $transaksi->product->nama_jasa }}</p>
                        <p class="text-sm text-gray-500">
                            Kategori: {{ $transaksi->product->kategori->nama_kategori ?? '-' }}
                        </p>

                        @if($transaksi->catatan)
                        <p class="text-sm text-gray-500 mt-2">
                            {{ $transaksi->catatan }}
                        </p>
                        @endif
                    </div>

                    <div class="text-right text-sm text-gray-500">
                        {{ $transaksi->jumlah }} x Rp {{ number_format((float) $transaksi->harga_satuan, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- PEMBAYARAN --}}
            <h3 class="font-semibold text-gray-700 mb-3">Rincian Pembayaran</h3>
            <div class="border-t pt-4 mb-6 space-y-2 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-semibold">Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Uang Bayar</span>
                    <span class="font-semibold">Rp {{ number_format((float) $transaksi->uang_bayar, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Uang Kembali</span>
                    <span class="font-semibold text-green-600">
                        Rp {{ number_format((float) $transaksi->uang_kembali, 0, ',', '.') }}
                    </span>
                </div>

            </div>

            {{-- STATUS --}}
            <div class="flex justify-between items-center mb-6">
                <span class="text-gray-700 font-medium">Status Pembayaran</span>
                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ ucfirst($transaksi->status_pembayaran) }}
                </span>
            </div>

            {{-- INFO PENGAMBILAN --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
                <h3 class="font-semibold text-xl text-blue-700 mb-3">
                    Informasi Pengambilan Foto 
                </h3>
                <p class="text-sm text-blue-700 mb-3">
                    Foto dapat diambil setelah 3 hari kerja dengan menunjukkan <b>KODE UNIK</b> berikut:
                </p>

                <div class="bg-white rounded-lg py-3 text-center">
                    <p class="text-xl font-bold text-blue-700 tracking-wide text-4xl">
                        {{ $transaksi->nomor_unik }}
                    </p>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="text-center text-sm text-gray-500 border-t pt-4">
                Terima kasih telah menggunakan layanan <b>Foto.in</b>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-center gap-3 mt-6">
                <a href="{{ route('kasir.produk.index') }}" class="px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                    Kembali
                </a>

                <a href="{{ route('kasir.transaksi.downloadStruk', $transaksi->id_transaksi) }}" class="px-5 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                    Download PDF
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
