@extends('layouts.app')

@section('title', 'Daftar Paket Fotografi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Paket Fotografi</h1>
        <p class="text-gray-600 mt-1">Pilih paket yang tersedia untuk dipesan</p>
    </div>
    
    <!-- Grid Produk -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition">
            @if($product->gambar)
                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_jasa }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
            
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $product->nama_jasa }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $product->kategori->nama_kategori ?? 'Umum' }}</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                        {{ $product->status }}
                    </span>
                </div>
                
                <p class="text-gray-600 mt-3 text-sm line-clamp-2">{{ $product->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                
                <div class="flex justify-between items-center mt-4">
                    <div>
                        <span class="text-2xl font-bold text-blue-700">Rp {{ number_format((float) $product->harga, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $product->durasi }} jam</span>
                    </div>
                </div>
                
                <div class="flex space-x-2 mt-4">
                    <a href="{{ route('products.show', $product) }}" 
                       class="flex-1 bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition">
                        Detail
                    </a>
                    <a href="{{ route('transaksis.create', ['id_jasa' => $product->id_jasa]) }}" 
                       class="flex-1 bg-blue-700 text-white text-center py-2 rounded-lg hover:bg-blue-600 transition">
                        Pesan
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection