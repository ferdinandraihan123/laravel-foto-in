@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Transaksi</h1>
        <p class="text-gray-600 mt-1">Pilih jenis laporan yang ingin dilihat</p>
    </div>
    
    <!-- Card Menu -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Laporan Harian -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="ml-4 text-xl font-semibold text-gray-900">Harian</h3>
            </div>
            <p class="text-gray-500 mb-4">Lihat laporan transaksi per hari</p>
            <form action="{{ route('laporan.harian') }}" method="GET" class="space-y-3">
                <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" 
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <button type="submit" class="w-full bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Tampilkan
                </button>
            </form>
        </div>
        
        <!-- Laporan Bulanan -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="ml-4 text-xl font-semibold text-gray-900">Bulanan</h3>
            </div>
            <p class="text-gray-500 mb-4">Lihat laporan transaksi per bulan</p>
            <form action="{{ route('laporan.bulanan') }}" method="GET" class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <select name="bulan" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @for($i = now()->year; $i >= now()->year - 2; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    Tampilkan
                </button>
            </form>
        </div>
        
        <!-- Laporan Tahunan -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="ml-4 text-xl font-semibold text-gray-900">Tahunan</h3>
            </div>
            <p class="text-gray-500 mb-4">Lihat laporan transaksi per tahun</p>
            <form action="{{ route('laporan.tahunan') }}" method="GET" class="space-y-3">
                <select name="tahun" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @for($i = now()->year; $i >= now()->year - 3; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>
    
    <!-- Menu Tambahan untuk Owner/Admin -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Kinerja Kasir</h3>
            <p class="text-gray-500 mb-4">Lihat performa dan transaksi per kasir</p>
            <a href="{{ route('laporan.kinerja-kasir') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                Lihat Laporan
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Produk Populer</h3>
            <p class="text-gray-500 mb-4">Lihat paket fotografi paling laris</p>
            <a href="{{ route('laporan.produk-populer') }}" class="inline-block bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                Lihat Laporan
            </a>
        </div>
    </div>
</div>
@endsection