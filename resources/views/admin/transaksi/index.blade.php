@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Transaksi</h1>
            <p class="text-gray-600 mt-1">Riwayat semua transaksi</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">
        <form action="{{ route('admin.transaksi.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari nomor/pelanggan..." value="{{ request('search') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none">
                </div>
            </div>

            <div class="w-48">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <div class="w-40">
                <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>

            @if(request()->anyFilled(['search', 'tanggal', 'status']))
            <div>
                <a href="{{ route('admin.transaksi.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block">
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
                            @elseif($trx->status == 'batal')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Batal</span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $trx->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.transaksi.show', $trx->id_transaksi) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-6 border-t border-gray-100">
            <div class="flex justify-center">
                {{ $transaksis->appends(request()->query())->links('pagination::tailwind') }}
            </div>

            <div class="text-center text-sm text-gray-400 mt-3">
                Halaman {{ $transaksis->currentPage() }} dari {{ $transaksis->lastPage() }}
            </div>
        </div>
    </div>
</div>
@endsection
