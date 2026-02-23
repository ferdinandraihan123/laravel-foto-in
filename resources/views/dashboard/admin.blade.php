@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-1">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span></p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-5">
            <p class="text-sm font-medium text-blue-100">Total Transaksi</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalTransaksi }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-lg p-5">
            <p class="text-sm font-medium text-green-100">Total Pendapatan</p>
            <p class="mt-2 text-3xl font-bold">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 text-white rounded-xl shadow-lg p-5">
            <p class="text-sm font-medium text-yellow-100">Kasir Aktif</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalKasirAktif }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl shadow-lg p-5">
            <p class="text-sm font-medium text-purple-100">Total Paket</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalProduk }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksiTerbaru as $trx)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $trx->nomor_unik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->nama_pelanggan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->product->nama_jasa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format((float) $trx->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($trx->status == 'selesai')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                            @elseif($trx->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">{{ $trx->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('transaksis.show', $trx->id_transaksi) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection