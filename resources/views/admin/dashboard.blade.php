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
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Booking Terbaru:</h3>
            <a href="{{ route('admin.transaksi.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksiTerbaru as $trx)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $trx->nomor_unik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->nama_pelanggan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->product->nama_jasa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $trx->tanggal_booking ? $trx->tanggal_booking->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @php
                            $durasiPaket = $trx->product->durasi ?? 1;
                            $jamMulai = $trx->jam_booking;
                            if ($jamMulai) {
                            $jamMulaiInt = (int) substr($jamMulai, 0, 2);
                            $jamSelesaiInt = $jamMulaiInt + $durasiPaket;
                            $jamMulaiFormatted = sprintf("%02d.%02d", $jamMulaiInt, 0);
                            $jamSelesaiFormatted = sprintf("%02d.%02d", $jamSelesaiInt, 0);
                            echo $jamMulaiFormatted . ' s/d ' . $jamSelesaiFormatted;
                            } else {
                            echo '-';
                            }
                            @endphp
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($trx->status == 'selesai')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                            @elseif($trx->status == 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                            @elseif($trx->status == 'proses')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Proses</span>
                            @elseif($trx->status == 'batal')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Batal</span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $trx->status
                                }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.transaksi.show', $trx->id_transaksi) }}"
                                class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection