@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Pembayaran</h1>
        <p class="text-gray-600 mt-1">Transaksi: {{ $transaksi->nomor_unik }}</p>
    </div>
    
    <!-- Content -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-8">
            <!-- Info Ringkas -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-100">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-blue-800">Pelanggan: <span class="font-semibold">{{ $transaksi->nama_pelanggan }}</span></p>
                        <p class="text-sm text-blue-800">Paket: <span class="font-semibold">{{ $transaksi->product->nama_jasa }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-800">Total Harus Dibayar:</p>
                        <p class="text-2xl font-bold text-blue-700">Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Pembayaran -->
            <form action="{{ route('kasir.transaksi.prosesBayar', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Uang Bayar -->
                    <div>
                        <label for="uang_bayar" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Uang Bayar <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                            <input type="number" name="uang_bayar" id="uang_bayar" 
                                   class="w-full pl-10 pr-4 py-3 text-lg rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 @error('uang_bayar') border-red-500 @enderror"
                                   placeholder="Masukkan jumlah uang"
                                   min="{{ $transaksi->total_harga }}"
                                   required>
                        </div>
                        @error('uang_bayar')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal: Rp {{ number_format((float) $transaksi->total_harga, 0, ',', '.') }}</p>
                    </div>
                    
                    <!-- Info Kembalian (akan dihitung via JS) -->
                    <div id="kembalian-info" class="bg-gray-50 rounded-lg p-4 hidden">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Uang Kembali:</span>
                            <span class="text-xl font-bold text-green-600" id="kembalian-value">Rp 0</span>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('kasir.transaksi.show', $transaksi->id_transaksi) }}" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition shadow-md">
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const uangBayar = document.getElementById('uang_bayar');
    const totalHarga = {{ $transaksi->total_harga }};
    const kembalianInfo = document.getElementById('kembalian-info');
    const kembalianValue = document.getElementById('kembalian-value');
    
    uangBayar.addEventListener('input', function() {
        let bayar = parseFloat(this.value) || 0;
        let kembalian = bayar - totalHarga;
        
        if (kembalian >= 0) {
            kembalianInfo.classList.remove('hidden');
            kembalianValue.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
        } else {
            kembalianInfo.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection