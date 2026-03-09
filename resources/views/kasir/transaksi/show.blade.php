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
                    <span class="px-3 py-1 rounded-full text-sm 
                        @if($transaksi->status == 'selesai') 
                        @elseif($transaksi->status == 'pending')
                        @elseif($transaksi->status == 'proses')
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($transaksi->status) }}
                    </span>
                    <br>
                    <span class="text-xs mt-1 inline-block">
                        @if($transaksi->status_pembayaran == 'lunas')
                            <span class="text-green-600 font-semibold">✓ Lunas</span>
                        @else
                            <span class="text-yellow-600 font-semibold">⏳ Belum Bayar</span>
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
            
            <div class="flex items-center space-x-4 pt-4 border-t border-gray-200">
                @if($transaksi->status_pembayaran == 'belum' && (auth()->user()->isKasir() || auth()->user()->isAdmin()))
                    <a href="{{ route('kasir.transaksi.bayar', $transaksi->id_transaksi) }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition shadow-md">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Proses Pembayaran
                    </a>
                @endif
                
                @if($transaksi->status_pembayaran == 'lunas')
                    <a href="{{ route('kasir.transaksi.struk', $transaksi->id_transaksi) }}" 
                       class="bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition shadow-md">
                        Lihat Struk
                    </a>
                @endif
                
                {{-- @if($transaksi->status == 'pending' && (auth()->user()->isKasir() || auth()->user()->isAdmin()))
                    <form action="{{ route('kasir.transaksi.batal', $transaksi->id_transaksi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                        @csrf
                        @method('POST')
                        <button type="submit" class="px-6 py-3 border border-red-300 text-red-600 rounded-full hover:bg-red-50 transition">
                            Batalkan Transaksi
                        </button>
                    </form>
                @endif --}}
                
                <a href="{{ route('kasir.transaksi.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection