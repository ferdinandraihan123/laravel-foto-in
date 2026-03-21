@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Transaksi</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap transaksi</p>
    </div>
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $transaksi->nomor_unik }}</h2>
                    <p class="text-sm text-gray-500">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-xs {{ $transaksi->status_badge_class }}">
                        {{ $transaksi->status_text }}
                    </span>
                    <br>
                    <span class="text-xs mt-1 inline-block">
                        @if($transaksi->status_pembayaran == 'lunas')
                        <span class="text-green-600 font-semibold">Lunas</span>
                        @else
                        <span class="text-yellow-600 font-semibold pl-2">Belum Bayar</span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-8">
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
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Kasir</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-500 w-32">Nama Kasir</td>
                            <td class="text-gray-900 font-medium">: {{ $transaksi->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Tanggal Booking</td>
                            <td class="text-gray-700">: {{ $transaksi->tanggal_booking->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h3 class="font-semibold text-gray-700 mb-3">Detail Paket</h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">{{ $transaksi->product->nama_jasa }}</p>
                        <p class="text-sm text-gray-500">Kategori: {{ $transaksi->product->kategori->nama_kategori ?? '-' }}</p>
                        @if($transaksi->catatan)
                        <p class="text-sm text-gray-500 mt-2">Catatan: {{ $transaksi->catatan }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $transaksi->jumlah }} x Rp {{ number_format((float) $transaksi->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <h3 class="font-semibold text-gray-700 mb-3">Rincian Pembayaran</h3>
            <div class="border-t border-gray-200 pt-4 mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-semibold">Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Uang Bayar</span>
                    <span class="font-semibold">Rp {{ number_format((float) $transaksi->uang_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Uang Kembali</span>
                    <span class="font-semibold text-green-600">Rp {{ number_format((float) $transaksi->uang_kembali, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t">

                <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                    Kembali
                </a>

                @php
                $fromDashboard = str_contains(url()->previous() ?? '', 'dashboard');
                @endphp

                @if(!$fromDashboard)
                <a href="{{ route('kasir.transaksi.bayar', $transaksi->id_transaksi) }}" class="px-5 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                    Proses Pembayaran
                </a>
                @else
                <a href="{{ route('kasir.transaksi.struk', $transaksi->id_transaksi) }}" class="px-5 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                    Lihat Struk
                </a>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
