@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Log Aktivitas</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap log aktivitas</p>
    </div>
    
    <!-- Content -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-8">
            <!-- Info -->
            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">ID Log</div>
                    <div class="col-span-2 text-gray-900">: {{ $log->id_log }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">Waktu</div>
                    <div class="col-span-2 text-gray-900">
                        : {{ $log->created_at->format('d/m/Y H:i:s') }}
                        <br>
                        <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">User</div>
                    <div class="col-span-2 text-gray-900">
                        : {{ $log->user->name ?? 'System' }}
                        @if($log->user)
                            <span class="ml-2 px-2 py-1 rounded-full text-xs 
                                @if($log->user->role == 'admin')
                                @elseif($log->user->role == 'owner')
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($log->user->role) }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">Email User</div>
                    <div class="col-span-2 text-gray-900">: {{ $log->user->email ?? '-' }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">Aktivitas</div>
                    <div class="col-span-2 text-gray-900 font-semibold">: {{ $log->aktivitas }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">Detail</div>
                    <div class="col-span-2 text-gray-700 whitespace-pre-line">: {{ $log->detail ?? '-' }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">IP Address</div>
                    <div class="col-span-2 text-gray-700">: {{ $log->ip_address ?? '-' }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">User Agent</div>
                    <div class="col-span-2 text-gray-700 break-words">: {{ $log->user_agent ?? '-' }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">Dibuat Pada</div>
                    <div class="col-span-2 text-gray-700">: {{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-500 font-medium">Diupdate Pada</div>
                    <div class="col-span-2 text-gray-700">: {{ $log->updated_at->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="flex items-center space-x-4 pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('owner.log-aktivitas.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                    Kembali
                </a>
                
                @if(auth()->user()->isAdmin())
                <form action="{{ route('log-aktivitas.destroy', $log->id_log) }}" method="POST" class="inline" onsubmit="return confirm('Hapus log ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition">
                        Hapus Log
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection