@extends('layouts.app')

@section('title', 'Jadwal Booking')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Jadwal Booking</h1>
        <p class="text-gray-600 mt-1">Daftar jadwal pelaksanaan sesi foto</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">

        <form action="{{ route('kasir.transaksi.jadwal') }}" method="GET" class="flex flex-wrap gap-4 items-end">

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Tanggal</label>
                <div class="relative">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menampilkan semua jadwal</p>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition mb-5">
                    Filter
                </button>
            </div>

            @if(request()->filled('tanggal'))
            <div>
                <a href="{{ route('kasir.transaksi.jadwal') }}"
                    class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block mb-5">
                    Reset
                </a>
            </div>
            @endif

        </form>

        <hr class="my-6">

        {{-- JADWAL --}}
        @if(isset($jadwalHariIni) && count($jadwalHariIni) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                            Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwalHariIni as $jadwal)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $jadwal->nomor_unik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->nama_pelanggan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->product->nama_jasa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $jadwal->tanggal_booking ? $jadwal->tanggal_booking->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @php
                            $durasiPaket = $jadwal->product->durasi ?? 1;
                            $jamMulai = $jadwal->jam_booking;
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
                        <td class="px-6 py-4 text-sm">
                            @if($jadwal->status == 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                            @elseif($jadwal->status == 'proses')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Proses</span>
                            @elseif($jadwal->status == 'selesai')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                            @else
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">{{
                                ucfirst($jadwal->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('kasir.transaksi.show', $jadwal->id_transaksi) }}"
                                class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- SUMMARY --}}
        <div class="mt-4 text-sm text-gray-500">
            Total: {{ count($jadwalHariIni) }} jadwal
        </div>
        @else
        <p class="text-gray-500 text-center py-4">
            @if(request()->filled('tanggal'))
            Tidak ada jadwal booking untuk tanggal {{ \Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y') }}
            @else
            Belum ada jadwal booking
            @endif
        </p>
        @endif
    </div>
</div>
@endsection