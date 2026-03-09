@extends('layouts.app')

@section('title', 'Detail Paket')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Paket</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap paket fotografi</p>
    </div>
    
    <!-- Content -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="md:flex">
            <!-- Gambar -->
            <div class="md:w-1/3 bg-gray-100 p-6 flex items-center justify-center">
                @if($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_jasa }}" class="w-full rounded-lg shadow-md">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>
            
            <!-- Info -->
            <div class="md:w-2/3 p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->nama_jasa }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Kategori: {{ $product->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm 
                        @if($product->status == 'aktif')
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
                
                <div class="mt-6 space-y-4">
                    <!-- Harga -->
                    <div class="flex items-center">
                        <div class="w-32 text-gray-500">Harga</div>
                        <div class="text-2xl font-bold text-blue-700">Rp {{ number_format((float) $product->harga, 0, ',', '.') }}</div>
                    </div>
                    
                    <!-- Durasi -->
                    <div class="flex items-center">
                        <div class="w-32 text-gray-500">Durasi</div>
                        <div class="text-gray-700">{{ $product->durasi }} jam</div>
                    </div>
                    
                    <!-- Deskripsi -->
                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ $product->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                    </div>
                    
                    <!-- Info Tambahan -->
                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-2">Informasi Tambahan</h3>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="py-1 text-gray-500 w-32">ID Paket</td>
                                <td class="text-gray-700">: {{ $product->id_jasa }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-500">Dibuat</td>
                                <td class="text-gray-700">: {{ $product->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-500">Diupdate</td>
                                <td class="text-gray-700">: {{ $product->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="mt-8 flex items-center space-x-4">
                    @if(auth()->user()->isKasir())
                    <a href="{{ route('transaksis.create', ['id_jasa' => $product->id_jasa]) }}" 
                       class="bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition shadow-md">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Booking Sekarang
                    </a>
                    @endif
                    
                    <a href="{{ route('products.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection