@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Kategori</h1>
        <p class="text-gray-600 mt-1">Edit kategori: {{ $kategori->nama_kategori }}</p>
    </div>
    
    <!-- Form -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Nama Kategori -->
                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nama_kategori') border-red-500 @enderror"
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
                        <option value="aktif" {{ old('status', $kategori->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $kategori->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.kategori.index') }}" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
                        Update Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection