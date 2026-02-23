@extends('layouts.app')

@section('title', 'Kelola Kasir')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kasir</h1>
            <p class="text-gray-600 mt-1">Daftar semua user dan kasir</p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition shadow-md flex items-center">
            Tambah User
        </a>
    </div>
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($user->role == 'admin')
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($user->status == 'aktif')
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->no_hp ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('users.edit', $user) }}" 
                               class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            
                            <form action="{{ route('users.toggleStatus', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="text-{{ $user->status == 'aktif' ? 'yellow' : 'green' }}-600 hover:text-{{ $user->status == 'aktif' ? 'yellow' : 'green' }}-800 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linecap="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ $user->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            
                            @if($user->id != auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection