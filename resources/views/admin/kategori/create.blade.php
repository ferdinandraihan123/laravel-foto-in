@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Kategori Baru</h1>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan kategori paket fotografi</p>
    </div>
    
    <!-- Form -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Nama Kategori -->
                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama_kategori') border-red-500 @enderror"
                           placeholder="Contoh: Wedding, Prewedding, Graduation"
                           required>
                    @error('nama_kategori')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Status aktif berarti kategori dapat digunakan untuk produk</p>
                </div>
                
                <!-- Preview Info -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi
                    </h4>
                    <p class="text-sm text-blue-700">
                        Kategori yang sudah dibuat tidak dapat dihapus jika masih memiliki produk terkait.
                    </p>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.kategori.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
                        Simpan Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection