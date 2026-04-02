@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Paket</h1>
        <p class="text-gray-600 mt-1">Edit paket: {{ $produk->nama_jasa }}</p>  
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.produk.update', ['produk' => $produk->id_jasa]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="nama_jasa" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket:</label>
                    <input type="text" name="nama_jasa" id="nama_jasa" value="{{ old('nama_jasa', $produk->nama_jasa) }}"
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 pl-2"required>
                    @error('nama_jasa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori:</label>
                    <select name="id_kategori" id="id_kategori" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 pl-2" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $produk->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi:</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 pl-2">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga:</label>
                    <div class="relative">
                        <span class="absolute pl-2 pt-2 text-gray-500">Rp</span>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga) }}"
                               class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                               required>
                    </div>
                    @error('harga')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="durasi" class="block text-sm font-medium text-gray-700 mb-2">Durasi: (jam)</label>
                    <input type="number" step="0.5" name="durasi" id="durasi" value="{{ old('durasi', $produk->durasi) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                           required>
                    @error('durasi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar Paket</label>
                    
                    @if($produk->gambar) 
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_jasa }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB. Kosongkan jika tidak ingin mengubah</p>
                    @error('gambar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status:</label>
                    <select name="status" id="status" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 pl-2"
                            required>
                        <option value="aktif" {{ old('status', $produk->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>  
                        <option value="nonaktif" {{ old('status', $produk->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.produk.index') }}" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
                        Update Paket
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection