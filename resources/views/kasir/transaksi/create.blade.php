@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Transaksi Baru</h1>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk membuat transaksi baru</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('kasir.transaksi.store') }}" method="POST">
            @csrf

            <div class="space-y-6">

                <div>
                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pelanggan:
                    </label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 pl-2 @error('nama_pelanggan') border-red-500 @enderror" required>
                    @error('nama_pelanggan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                        No. HP:
                    </label>
                    <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" value="{{ old('no_hp_pelanggan') }}" class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 pl-2 @error('no_hp_pelanggan') border-red-500 @enderror" required>
                    @error('no_hp_pelanggan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @php
                $activeId = old('id_jasa', $selectedId ?? null);
                $selectedProduct = $activeId
                    ? $products->firstWhere('id_jasa', $activeId)
                    : null;
                @endphp

                <input type="hidden" name="id_jasa" value="{{ $selectedProduct->id_jasa }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Paket Dipilih</label>
                    <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                        <div>
                            <p class="font-semibold text-blue-900">{{ $selectedProduct->nama_jasa }}</p>
                            <p class="text-sm text-blue-600">
                                Rp {{ number_format((float) $selectedProduct->harga, 0, ',', '.') }}
                                &bull; {{ $selectedProduct->durasi }} jam
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah:
                    </label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="1" class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 pl-2 @error('jumlah') border-red-500 @enderror" required>
                    @error('jumlah')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_booking" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Booking:
                    </label>
                    <input type="date" name="tanggal_booking" id="tanggal_booking" value="{{ old('tanggal_booking', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 pl-2 @error('tanggal_booking') border-red-500 @enderror" required>
                    <p class="text-xs text-gray-500 mt-1">Pilih tanggal pelaksanaan sesi foto</p>
                    @error('tanggal_booking')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="catatan" id="catatan" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 pl-2">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ url()->previous() }}" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
                        Buat Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
