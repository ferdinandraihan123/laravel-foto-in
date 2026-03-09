@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Owner</h1>
        <p class="text-gray-600 mt-1">Selamat datang kembali, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTransaksi }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kasir Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalKasirAktif }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Paket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProduk }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR PRODUK / PAKET FOTOGRAFI (UNTUK OWNER) -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">📸 Daftar Paket Fotografi</h3>
            <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($produkTerbaru ?? [] as $produk)
            @php
            /** @var \App\Models\Product $produk */
            @endphp
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                @if($produk->gambar)
                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_jasa }}" class="w-full h-32 object-cover rounded-lg mb-3">
                @else
                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 mb-3">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $produk->nama_jasa }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $produk->kategori->nama_kategori ?? 'Umum' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($produk->status == 'aktif')
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($produk->status) }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center mt-2">
                    <span class="text-lg font-bold text-blue-700">Rp {{ number_format((float) $produk->harga, 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-500">{{ $produk->durasi }} jam</span>
                </div>
                
                <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                    <span>Stok: {{ $produk->stok ?? 0 }}</span>
                    <span>Terjual: {{ $produk->transaksis_count ?? 0 }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                Belum ada paket fotografi
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Data Sewa & Log Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Data Sewa Terbaru -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Data Sewa Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dataSewa->take(5) as $sewa)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $sewa->nama_pelanggan }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $sewa->product->nama_jasa }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $sewa->tanggal_booking->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm font-semibold text-gray-900">Rp {{ number_format((float) $sewa->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('laporan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua →</a>
            </div>
        </div>
        
        <!-- Log Aktivitas -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Log Aktivitas Terbaru</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($logTerbaru as $log)
                <div class="border-b border-gray-100 pb-2">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <p class="text-sm text-gray-600">{{ $log->aktivitas }}</p>
                    @if($log->detail)
                    <p class="text-xs text-gray-400 mt-1">{{ $log->detail }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Tombol Cek Log Aktivitas -->
    <div class="text-center">
        <a href="{{ route('log-aktivitas.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Lihat Log Aktivitas Lengkap
        </a>
    </div>
</div>
@endsection