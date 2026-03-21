@extends('layouts.app')

@section('title','Laporan Transaksi')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Laporan Transaksi</h1>


    <div class="bg-white p-5 rounded-xl shadow mb-6">

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-center justify-between gap-4">

            <div class="flex items-center gap-4">

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="border border-gray-300 rounded-lg px-3 py-2">
                </div>

            </div>

            <div class="flex gap-2">

                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>

                @if(request('dari') || request('sampai'))
                <a href="{{ route('admin.laporan.index') }}" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    Reset
                </a>
                @endif

                <a href="{{ route('admin.laporan.pdf', request()->all()) }}" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition">
                    Cetak PDF
                </a>

            </div>

        </form>

    </div>



    <!-- CARD STATISTIK -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-blue-500 text-white p-6 rounded-xl shadow">
            <p class="text-sm">Total Pendapatan</p>
            <h2 class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalPendapatan,0,',','.') }}
            </h2>
        </div>

        <div class="bg-green-500 text-white p-6 rounded-xl shadow">
            <p class="text-sm">Total Transaksi</p>
            <h2 class="text-2xl font-bold mt-2">
                {{ $totalTransaksi }}
            </h2>
        </div>

        <div class="bg-purple-500 text-white p-6 rounded-xl shadow">
            <p class="text-sm">Pendapatan Hari Ini</p>
            <h2 class="text-2xl font-bold mt-2">
                Rp {{ number_format($pendapatanHariIni,0,',','.') }}
            </h2>
        </div>

        <div class="bg-orange-500 text-white p-6 rounded-xl shadow">
            <p class="text-sm">Paket Terlaris</p>
            <h2 class="text-lg font-bold mt-2">
                {{ $paketTerlaris?->product?->nama_jasa ?? '-' }}
            </h2>
        </div>

    </div>



    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">
            <h2 class="font-semibold">Data Transaksi</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Pelanggan</th>
                        <th class="p-3 text-left">Paket</th>
                        <th class="p-3 text-left">Total</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transaksi as $item)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-3">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                        <td class="p-3">
                            {{ $item->nama_pelanggan }}
                        </td>

                        <td class="p-3">
                            {{ $item->product->nama_jasa ?? '-' }}
                        </td>

                        <td class="p-3 font-semibold text-green-600">
                            Rp {{ number_format($item->total_harga,0,',','.') }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center p-6 text-gray-500">
                            Belum ada data transaksi
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="p-4 mt-3">
            {{ $transaksi->withQueryString()->links() }}
        </div>
        <div class="text-center text-sm text-gray-400 mb-5">
            Halaman {{ $transaksi->currentPage() }} dari {{ $transaksi->lastPage() }}
        </div>

    </div>

</div>

@endsection
