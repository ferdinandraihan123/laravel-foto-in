@extends('layouts.app')

@section('title', 'Daftar Paket Fotografi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Paket Fotografi</h1>
            <p class="text-gray-600 mt-1">Kelola semua paket fotografi yang tersedia</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition shadow-md flex items-center">
            Tambah Paket
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari paket..." value="{{ request('search') }}"class="w-full pl-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
                </div>
            </div>
            
            <div class="w-48">
                <select name="kategori" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-40">
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            
            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>
            
            @if(request()->anyFilled(['search', 'kategori', 'status']))
            <div>
                <a href="{{ route('admin.produk.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block">
                    Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $index => $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_jasa }}" class="w-12 h-12 object-cover rounded-lg shadow-sm">
                            @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $product->nama_jasa }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->kategori->nama_kategori ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format((float) $product->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->durasi }} jam
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-normal rounded-full 
                                @if($product->status == 'aktif')
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.produk.show', $product) }}" class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                Detail
                            </a>

                            <a href="{{ route('admin.produk.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900 inline-flex items-center">
                                Edit
                            </a>

                            <form action="{{ route('admin.produk.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-6 border-t border-gray-100">
            <div class="flex justify-center">
                {{ $products->appends(request()->query())->links('pagination::tailwind') }}
            </div>

            <!-- Info kecil di bawah -->
            <div class="text-center text-sm text-gray-400 mt-3">
                Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
            </div>
        </div>
    </div>
</div>
@endsection