@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Owner</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>
        </p>
    </div>

    <!-- STATISTIK -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-blue-600 text-white rounded-xl p-6 shadow">
            <p class="text-sm">Total Transaksi</p>
            <p class="text-3xl font-bold">{{ $totalTransaksi }}</p>
        </div>

        <div class="bg-green-600 text-white rounded-xl p-6 shadow">
            <p class="text-sm">Total Pendapatan</p>
            <p class="text-3xl font-bold">
                Rp {{ number_format($totalPendapatan,0,',','.') }}
            </p>
        </div>

        <div class="bg-yellow-500 text-white rounded-xl p-6 shadow">
            <p class="text-sm">Kasir Aktif</p>
            <p class="text-3xl font-bold">{{ $totalKasirAktif }}</p>
        </div>

        <div class="bg-purple-600 text-white rounded-xl p-6 shadow">
            <p class="text-sm">Total Paket</p>
            <p class="text-3xl font-bold">{{ $totalProduk }}</p>
        </div>

    </div>


    <!-- TABEL PAKET -->
    <div class="bg-white rounded-xl shadow-md border mb-8">

        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-lg font-semibold">Paket Fotografi Tersedia</h2>
            <a href="{{ route('owner.produk.index') }}" class="text-blue-600 text-sm">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($produkTerbaru as $produk)

                    <tr>
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>

                        <td class="px-6 py-4 font-medium">
                            {{ $produk->nama_jasa }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $produk->kategori->nama_kategori ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            Rp {{ number_format($produk->harga,0,',','.') }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $produk->durasi }} jam
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                            {{ $produk->status == 'aktif'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">
                                {{ $produk->status }}
                            </span>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>
    </div>


    <!-- TABEL DATA KASIR -->
    <div class="bg-white rounded-xl shadow-md border mb-8">

        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Data Kasir</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>

                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($kasirs as $kasir)

                    <tr>

                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $kasir->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $kasir->email }}
                        </td>

                        <td class="px-6 py-4">

                            <span class="px-2 py-1 text-xs rounded-full
                            {{ $kasir->status == 'aktif'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">

                                {{ $kasir->status }}

                            </span>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
