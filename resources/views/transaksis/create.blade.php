@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Transaksi Baru</h1>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk membuat transaksi baru</p>
    </div>
    
    <!-- Form -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('transaksis.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Nama Pelanggan -->
                <div>
                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" 
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama_pelanggan') border-red-500 @enderror"
                           placeholder="Masukkan nama pelanggan"
                           required>
                    @error('nama_pelanggan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- No HP -->
                <div>
                    <label for="no_hp_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">No. HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" value="{{ old('no_hp_pelanggan') }}" 
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('no_hp_pelanggan') border-red-500 @enderror"
                           placeholder="081234567890"
                           required>
                    @error('no_hp_pelanggan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pilih Paket -->
                <div>
                    <label for="id_jasa" class="block text-sm font-medium text-gray-700 mb-2">Pilih Paket <span class="text-red-500">*</span></label>
                    <select name="id_jasa" id="id_jasa" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id_jasa }}" 
                                    data-harga="{{ $product->harga }}"
                                    {{ old('id_jasa') == $product->id_jasa ? 'selected' : '' }}>
                                {{ $product->nama_jasa }} - Rp {{ number_format((float) $product->harga, 0, ',', '.') }} ({{ $product->durasi }} jam)
                            </option>
                        @endforeach
                    </select>
                    @error('id_jasa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jumlah -->
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('jumlah') border-red-500 @enderror"
                           required>
                    @error('jumlah')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- TANGGAL BOOKING - FITUR BARU -->
                <div>
                    <label for="tanggal_booking" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Booking <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_booking" id="tanggal_booking" 
                           value="{{ old('tanggal_booking', now()->format('Y-m-d')) }}" 
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('tanggal_booking') border-red-500 @enderror"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Pilih tanggal pelaksanaan sesi foto</p>
                    @error('tanggal_booking')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pilih Jam (Opsional) -->
                <div>
                    <label for="jam_booking" class="block text-sm font-medium text-gray-700 mb-2">Jam Booking (Opsional)</label>
                    <input type="time" name="jam_booking" id="jam_booking" value="{{ old('jam_booking', '09:00') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <p class="text-xs text-gray-500 mt-1">Isi jika ingin menentukan jam tertentu</p>
                </div>
                
                <!-- Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" rows="3" 
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                              placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>
                
                <!-- Preview Total -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <p class="text-sm text-blue-800">Total Harga akan dihitung otomatis</p>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('transaksis.index') }}" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
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

@push('scripts')
<script>
    // Auto calculate total (optional)
    document.getElementById('id_jasa').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];
        let harga = selected.dataset.harga;
        // You can display harga if needed
    });
</script>
@endpush
@endsection