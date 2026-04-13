@extends('layouts.app')

@section('title', 'Daftar Paket Fotografi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Paket Fotografi</h1>
        <p class="text-gray-600 mt-1">Pilih paket yang tersedia untuk dipesan</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <form action="{{ route('kasir.produk.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari paket..." value="{{ request('search') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <div class="w-48">
                <select name="kategori"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id_kategori }}" {{ request('kategori')==$kategori->id_kategori ?
                        'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>

            @if(request()->anyFilled(['search','kategori']))
            <div>
                <a href="{{ route('kasir.produk.index') }}"
                    class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block">
                    Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div
            class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition {{ $product->status !== 'aktif' ? 'opacity-60 bg-gray-50' : '' }}">

            @if($product->gambar)
            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_jasa }}"
                class="w-full h-48 object-cover {{ $product->status !== 'aktif' ? 'grayscale' : '' }}">
            @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            @endif

            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $product->nama_jasa }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $product->kategori->nama_kategori ?? 'Umum' }}
                        </p>
                    </div>

                    <span
                        class="px-2 py-1 rounded-full text-sm font-medium {{ $product->status === 'aktif' ? ' ' : 'bg-red-100 text-red-800' }}">
                        {{ $product->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <p class="text-gray-600 mt-3 text-sm line-clamp-2">
                    {{ $product->deskripsi ?: 'Tidak ada deskripsi' }}
                </p>

                <div class="flex justify-between items-center mt-4">
                    <div>
                        <span
                            class="text-2xl font-bold {{ $product->status === 'aktif' ? 'text-blue-700' : 'text-gray-400' }}">
                            {{ $product->harga_formatted }}
                        </span>
                        <span class="text-sm text-gray-500 ml-2">
                            {{ $product->durasi }} jam
                        </span>
                    </div>
                </div>

                <div class="flex space-x-2 mt-4">
                    <a href="{{ route('kasir.produk.show', $product->id_jasa) }}"
                        class="flex-1 bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition">
                        Detail
                    </a>

                    @if($product->status === 'aktif')
                    <a href="{{ route('kasir.transaksi.create', ['id_jasa' => $product->id_jasa]) }}"
                        class="flex-1 bg-blue-700 text-white text-center py-2 rounded-lg hover:bg-blue-600 transition">
                        Pesan
                    </a>
                    @else
                    <button disabled
                        class="flex-1 bg-gray-300 text-gray-500 text-center py-2 rounded-lg cursor-not-allowed">
                        Tidak Tersedia
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>
@endsection