@extends('layouts.app')

@section('title', 'Log Aktivitas User')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas: {{ $user->name }}</h1>
        <p class="text-gray-600 mt-1">Semua aktivitas yang dilakukan oleh user ini</p>
    </div>
    
    <!-- Info User -->
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="ml-4">
                <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }} • {{ ucfirst($user->role) }} • {{ ucfirst($user->status) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
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
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $log->aktivitas }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $log->detail ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>
    
    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="{{ route('log-aktivitas.index') }}" 
           class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Semua Log
        </a>
    </div>
</div>
@endsection