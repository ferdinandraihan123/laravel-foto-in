@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
            <p class="text-gray-600 mt-1">Catatan semua aktivitas dalam sistem</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('owner.log-aktivitas.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                Export CSV
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->role == 'owner')
            <form action="{{ route('owner.log-aktivitas.clean') }}" method="POST" class="inline" onsubmit="return confirm('Hapus log yang lebih dari 30 hari?')">
                @csrf
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition flex items-center">
                    Bersihkan Log Lama
                </button>
            </form>

            <form action="{{ route('owner.log-aktivitas.clear-all') }}" method="POST" class="inline" onsubmit="return confirm('YAKIN INGIN MENGHAPUS SEMUA LOG? Tindakan ini tidak bisa dibatalkan!')">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center">
                    Hapus Semua
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <form action="{{ route('owner.log-aktivitas.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari aktivitas..." value="{{ request('search') }}" class="w-full pl-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
            </div>

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

            <div class="w-40">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>

            @if(request()->anyFilled(['search', 'user_id', 'tanggal']))
            <div>
                <a href="{{ route('owner.log-aktivitas.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block">
                    Reset
                </a>
            </div>
            @endif
        </form>
    </div>

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
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
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
                                @php
                                    $roleBadgeClass = match($log->user->role) {
                                        'admin' => '',
                                        'owner' => '',
                                        'kasir' => '',
                                        default => ''
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleBadgeClass }}">
                                    {{ ucfirst($log->user->role) }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $log->aktivitas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection