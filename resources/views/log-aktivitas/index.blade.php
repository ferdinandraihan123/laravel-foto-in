@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
            <p class="text-gray-600 mt-1">Catatan semua aktivitas dalam sistem</p>
        </div>
        <div class="flex space-x-3">
            <!-- Tombol Export -->
            <a href="{{ route('log-aktivitas.export', request()->query()) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linecap="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </a>
            
            @if(auth()->user()->isAdmin())
            <!-- Hapus Log Lama -->
            <form action="{{ route('log-aktivitas.clean') }}" method="POST" class="inline" onsubmit="return confirm('Hapus log yang lebih dari 30 hari?')">
                @csrf
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Bersihkan Log Lama
                </button>
            </form>
            
            <!-- Hapus Semua Log (Hati-hati) -->
            <form action="{{ route('log-aktivitas.clear-all') }}" method="POST" class="inline" onsubmit="return confirm('YAKIN INGIN MENGHAPUS SEMUA LOG? Tindakan ini tidak bisa dibatalkan!')">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Semua
                </button>
            </form>
            @endif
        </div>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <form action="{{ route('log-aktivitas.index') }}" method="GET" class="flex flex-wrap gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari aktivitas/detail..." 
                       value="{{ request('search') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            
            <!-- Filter User -->
            <div class="w-48">
                <select name="user_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filter Tanggal -->
            <div class="w-40">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            
            <!-- Filter Rentang Tanggal -->
            <div class="flex gap-2">
                <input type="date" name="dari_tanggal" placeholder="Dari" value="{{ request('dari_tanggal') }}"
                       class="w-36 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <span class="text-gray-500 self-center">-</span>
                <input type="date" name="sampai_tanggal" placeholder="Sampai" value="{{ request('sampai_tanggal') }}"
                       class="w-36 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            
            <div>
                <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                            <br>
                            <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $log->user->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($log->user)
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($log->user->role == 'admin')
                                    @elseif($log->user->role == 'owner')
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($log->user->role) }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $log->aktivitas }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $log->detail ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('log-aktivitas.show', $log->id_log) }}" 
                               class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                            
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('log-aktivitas.destroy', $log->id_log) }}" method="POST" class="inline" onsubmit="return confirm('Hapus log ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 inline-flex items-center ml-2">
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
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection