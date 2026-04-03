@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detail Transaksi</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap transaksi</p>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $transaksi->nomor_unik }}</h2>
                    <p class="text-sm text-gray-500">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-center">

                   @php
                        $statusClass = match($transaksi->status) {
                            'selesai' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'proses' => 'bg-blue-100 text-blue-800',
                            default => 'bg-red-100 text-red-800',
                        };
                    @endphp

                    <span class="px-3 py-1 rounded-full text-sm {{ $statusClass }}">
                        {{ ucfirst($transaksi->status) }}
                    </span>

                    <div class="text-xs mt-1">
                        @if($transaksi->status_pembayaran == 'lunas')
                        <span class="text-green-600 font-semibold">Lunas</span>
                        @else
                        <span class="text-yellow-600 font-semibold">Belum Bayar</span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Pelanggan</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-500 w-32">Nama</td>
                            <td class="text-gray-900 font-medium">: {{ $transaksi->nama_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">No. HP</td>
                            <td class="text-gray-700">: {{ $transaksi->no_hp_pelanggan }}</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700 mb-3">Informasi Booking</h3>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-500 w-32">Tanggal Foto</td>
                            <td class="text-gray-900 font-medium">: {{ $transaksi->tanggal_booking ? $transaksi->tanggal_booking->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Jam Foto</td>
                            <td class="text-gray-700">: 
                                @php
                                    $durasiPaket = $transaksi->product->durasi ?? 1;
                                    $jamMulai = $transaksi->jam_booking;
                                    if ($jamMulai) {
                                        $jamMulaiInt = (int) substr($jamMulai, 0, 2);
                                        $jamSelesaiInt = $jamMulaiInt + $durasiPaket;
                                        $jamMulaiFormatted = sprintf("%02d.%02d", $jamMulaiInt, 0);
                                        $jamSelesaiFormatted = sprintf("%02d.%02d", $jamSelesaiInt, 0);
                                        echo $jamMulaiFormatted . ' s/d ' . $jamSelesaiFormatted;
                                    } else {
                                        echo '-';
                                    }
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Kasir</td>
                            <td class="text-gray-700">: {{ $transaksi->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h3 class="font-semibold text-gray-700 mb-3">Detail Paket</h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">{{ $transaksi->product->nama_jasa }}</p>
                        <p class="text-sm text-gray-500">Kategori: {{ $transaksi->product->kategori->nama_kategori ?? '-' }}</p>
                        <p class="text-sm text-gray-500">Durasi: {{ $transaksi->product->durasi ?? '-' }} jam</p>
                        @if($transaksi->catatan)
                        <p class="text-sm text-gray-500 mt-2">Catatan: {{ $transaksi->catatan }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $transaksi->jumlah }} x Rp {{ number_format((float) $transaksi->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <h3 class="font-semibold text-gray-700 mb-3">Rincian Pembayaran</h3>
            <div class="border-t border-gray-200 pt-4 mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-semibold">Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Uang Bayar</span>
                    <span class="font-semibold">Rp {{ number_format((float) $transaksi->uang_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Uang Kembali</span>
                    <span class="font-semibold text-green-600">Rp {{ number_format((float) $transaksi->uang_kembali, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-4">Update Status Transaksi</h3>

                <form action="{{ route('admin.transaksi.update-status', $transaksi->id_transaksi) }}" method="POST" class="flex flex-wrap items-center gap-4">
                    @csrf
                    @method('PUT')

                    <select name="status" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="pending" {{ $transaksi->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ $transaksi->status == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ $transaksi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ $transaksi->status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Update Status
                    </button>
                </form>
            </div>

            <div class="mt-6 flex justify-start">
                <a href="{{ route('admin.transaksi.index') }}" class="px-6 py-3 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection