@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        <p class="text-gray-600 mt-1">Edit data user: {{ $user->name }}</p>
    </div>
    
    <!-- Form -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                           class="w-full rounded-lg  focus:border-blue-500 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password (opsional) -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" id="password" 
                           class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                
                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="role" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                        <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                           placeholder="081234567890">
                </div>
                
                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" 
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                              placeholder="Jl. Contoh No. 123">{{ old('alamat', $user->alamat) }}</textarea>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection