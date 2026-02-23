@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Transaksi</h1>
            <p class="text-gray-600 mt-1">Riwayat semua transaksi</p>
        </div>
        @if(auth()->user()->isKasir() || auth()->user()->isAdmin())
        <a href="{{ route('transaksis.create') }}" 
           class="bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition shadow-md flex items-center">
            Transaksi Baru
        </a>
        @endif
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <form action="{{ route('transaksis.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari nomor/pelanggan..." 
                       value="{{ request('search') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="w-48">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="w-40">
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksis as $trx)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $trx->nomor_unik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->nama_pelanggan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->product->nama_jasa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format((float) $trx->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($trx->status == 'selesai')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                            @elseif($trx->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                            @elseif($trx->status == 'proses')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Proses</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Batal</span>
                            @endif
                            <br>
                            <span class="text-xs mt-1 block">
                                @if($trx->status_pembayaran == 'lunas')
                                    <span class="text-green-600">✓ Lunas</span>
                                @else
                                    <span class="text-yellow-600">⏳ Belum</span>
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('transaksis.show', $trx->id_transaksi) }}" 
                               class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transaksis->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection