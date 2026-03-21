@extends('layouts.app')

@section('title', 'Daftar Paket Fotografi')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Paket Fotografi</h1>
        <p class="text-gray-600 mt-1">Daftar paket fotografi yang tersedia</p>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="GET" action="{{ route('owner.produk.index') }}" class="flex flex-wrap gap-4 items-center">

        <!-- Search -->
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        <!-- Filter Kategori -->
        <div class="w-48">
            <select name="kategori" class="w-full rounded-lg border-gray-300">

                <option value="">Semua Kategori</option>

                @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }}
                </option>
                @endforeach

            </select>
        </div>

        <!-- Filter Button -->
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Filter
        </button>

        <!-- Reset Button -->
        @if(request('search') || request('kategori'))
        <a href="{{ route('owner.produk.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700">
            Reset
        </a>
        @endif

    </form>


    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            No
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Gambar
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Nama Paket
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Kategori
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Harga
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Durasi
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($products as $product)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-sm">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4">

                            @if($product->gambar)
                            <img src="{{ asset('storage/'.$product->gambar) }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg"></div>
                            @endif

                        </td>

                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $product->nama_jasa }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $product->kategori->nama_kategori ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-blue-700 font-semibold">
                            Rp {{ number_format($product->harga,0,',','.') }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $product->durasi }} Jam
                        </td>

                        <td class="px-6 py-4">

                            <span class="px-2 py-1 rounded-full text-xs
                            {{ $product->status=='aktif'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">

                                {{ $product->status }}

                            </span>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>

@endsection
