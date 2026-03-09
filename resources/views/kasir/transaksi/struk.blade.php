@extends('layouts.app')

@section('title', 'Struk Transaksi')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-blue-100">
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-8 py-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">FOTO.IN</h1>
                    <p class="text-blue-100 mt-1">Capture Your Story</p>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Struk Transaksi</p>
                    <p class="text-xl font-mono font-bold mt-1">{{ $transaksi->nomor_unik }}</p>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-2 gap-6 mb-8 pb-6 border-b border-gray-200">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Pelanggan</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-500 w-32">Nama</td>
                            <td class="text-gray-900 font-medium">: {{ $transaksi->nama_pelanggan }}</td>
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
                            <td class="py-1 text-gray-500 w-32">Tanggal</td>
                            <td class="text-gray-700">: {{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Kasir</td>
                            <td class="text-gray-700">: {{ $transaksi->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Booking</td>
                            <td class="text-gray-700">: {{ $transaksi->tanggal_booking ? $transaksi->tanggal_booking->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <h3 class="font-semibold text-gray-700 mb-3">Detail Paket</h3>
            <div class="bg-gray-50 rounded-xl p-5 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900 text-lg">{{ $transaksi->product->nama_jasa }}</p>
                        <p class="text-sm text-gray-500 mt-1">Kategori: {{ $transaksi->product->kategori->nama_kategori ?? 'Umum' }}</p>
                        @if($transaksi->catatan)
                            <p class="text-sm text-gray-500 mt-2 bg-white p-2 rounded">Catatan: {{ $transaksi->catatan }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $transaksi->jumlah }} x Rp {{ number_format((float) $transaksi->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <h3 class="font-semibold text-gray-700 mb-3">Rincian Pembayaran</h3>
            <div class="bg-white rounded-xl p-5 border-2 border-blue-100 mb-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Total Harga</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Uang Bayar</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format((float) $transaksi->uang_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base border-t border-gray-200 pt-3">
                        <span class="text-gray-600">Uang Kembali</span>
                        <span class="font-semibold text-green-600 text-lg">Rp {{ number_format((float) $transaksi->uang_kembali, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-4 pt-3 border-t-2 border-blue-200">
                        <span>Status Pembayaran</span>
                        <span class="text-green-600 bg-green-100 px-4 py-1 rounded-full text-sm font-semibold">
                            {{ strtoupper($transaksi->status_pembayaran) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-200 mb-6">
                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                    Informasi Pengambilan Foto
                </h4>
                <p class="text-sm text-blue-700 mb-2">
                    Foto dapat diambil setelah 3 hari kerja dengan menunjukkan <span class="font-bold">KODE UNIK</span> berikut:
                </p>
                <div class="bg-white p-4 rounded-lg text-center">
                    <p class="text-3xl font-mono font-bold text-blue-800 tracking-wider">
                        {{ $transaksi->nomor_unik }}
                    </p>
                </div>
            </div>
            
            <div class="text-center text-sm text-gray-500 pt-6 border-t border-gray-200">
                <p>Terima kasih telah menggunakan layanan <span class="font-semibold">Foto.in</span></p>
                <p class="mt-4 text-xs text-gray-400">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
            
            <div class="flex justify-center space-x-4 mt-8">
                <a href="{{ route('kasir.transaksi.downloadStruk', $transaksi->id_transaksi) }}" 
                   class="bg-blue-700 text-white px-8 py-4 rounded-full hover:bg-blue-600 transition shadow-lg flex items-center space-x-2 text-lg font-semibold">
                    <span>Download PDF</span>
                </a>
                <a href="{{ route('kasir.transaksi.index') }}" 
                   class="bg-gray-200 text-gray-800 px-8 py-4 rounded-full hover:bg-gray-300 transition flex items-center space-x-2 text-lg font-semibold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection