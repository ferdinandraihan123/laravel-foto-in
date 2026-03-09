@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Kasir</h1>
        <p class="text-gray-600 mt-1">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('kasir.transaksi.create') }}" 
           class="bg-blue-700 text-white p-6 rounded-xl hover:bg-blue-600 transition shadow-md flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">+ Transaksi Baru</h3>
                <p class="text-blue-100 mt-1">Buat transaksi pemesanan jasa fotografi</p>
            </div>
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linecap="round" d="M12 4v16m8-8H4" />
            </svg>
        </a>
        
        <a href="{{ route('kasir.produk.index') }}" 
           class="bg-green-600 text-white p-6 rounded-xl hover:bg-green-700 transition shadow-md flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">Lihat Paket Foto</h3>
                <p class="text-green-100 mt-1">Lihat semua paket dan harga</p>
            </div>
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linecap="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $transaksiHariIni ?? 0 }}</p>
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
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Booking Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $bookingHariIni ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DAFTAR PRODUK / PAKET FOTOGRAFI -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">📸 Paket Fotografi Tersedia</h3>
                <a href="{{ route('kasir.produk.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</a>
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
                        
                        <h4 class="font-semibold text-gray-900">{{ $produk->nama_jasa }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $produk->kategori->nama_kategori ?? 'Umum' }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-lg font-bold text-blue-700">Rp {{ number_format((float) $produk->harga, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-500">{{ $produk->durasi }} jam</span>
                        </div>
                        <a href="{{ route('kasir.transaksi.create', ['id_jasa' => $produk->id_jasa]) }}" 
                        class="mt-3 block w-full text-center bg-blue-100 text-blue-700 py-2 rounded-lg hover:bg-blue-200 transition text-sm">
                            Pilih Paket
                        </a>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-gray-500">
                        Belum ada paket fotografi tersedia
                    </div>
                @endforelse
            </div>
        </div>
        
        @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
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
                    @foreach($jadwalHariIni as $jadwal)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $jadwal->nomor_unik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->nama_pelanggan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->product->nama_jasa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $jadwal->tanggal_booking ? $jadwal->tanggal_booking->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $jadwal->tanggal_booking ? $jadwal->tanggal_booking->format('H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($jadwal->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                            @elseif($jadwal->status == 'proses')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Proses</span>
                            @elseif($jadwal->status == 'selesai')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">{{ ucfirst($jadwal->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('kasir.transaksi.show', $jadwal->id_transaksi) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-4">
            Tidak ada jadwal booking untuk tanggal {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y') : now()->format('d/m/Y') }}
        </p>
        @endif
    </div>
</div>
@endsection