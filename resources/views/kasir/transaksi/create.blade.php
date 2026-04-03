@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Transaksi Baru</h1>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk membuat transaksi baru</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('kasir.transaksi.store') }}" method="POST" id="bookingForm">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pelanggan:
                    </label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                        class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 px-3 py-2 "
                        required>
                    @error('nama_pelanggan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                        No. HP:
                    </label>
                    <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" value="{{ old('no_hp_pelanggan') }}"
                        class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 px-3 py-2"
                        required>
                    @error('no_hp_pelanggan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @php
                $activeId = old('id_jasa', $selectedId ?? null);
                $selectedProduct = $activeId ? $products->firstWhere('id_jasa', $activeId) : null;
                @endphp
                <input type="hidden" name="id_jasa" id="id_jasa" value="{{ $selectedProduct->id_jasa ?? '' }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Paket Dipilih:</label>
                    <div
                        class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                        <div>
                            <p class="font-semibold text-blue-900">{{ $selectedProduct->nama_jasa ?? 'Pilih paket di
                                halaman sebelumnya' }}</p>
                            <p class="text-sm text-blue-600">
                                Rp {{ number_format((float) ($selectedProduct->harga ?? 0), 0, ',', '.') }}
                                &bull; <span id="durasiText">{{ $selectedProduct->durasi ?? 0 }}</span> jam
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="tanggal_booking" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Booking:
                    </label>
                    <input type="date" name="tanggal_booking" id="tanggal_booking"
                        value="{{ old('tanggal_booking', $tanggalDipilih ?? now()->format('Y-m-d')) }}"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 px-3 py-2"
                        required>
                    @error('tanggal_booking')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                        class="w-full rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 border border-gray-300 px-3 py-2"
                        required>
                    @error('jumlah')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai:
                    </label>

                    @php
                    $durasiPaket = $selectedProduct->durasi ?? 1;
                    @endphp

                    <div class="flex flex-wrap gap-2" id="jamContainer">
                        @php
                        // Gabungkan semua kemungkinan jam dengan status tersedia/tidak
                        $semuaJam = [];
                        $maxJam = 22 - $durasiPaket;
                        for ($i = 7; $i <= $maxJam; $i++) { $jamMulai=sprintf("%02d:00", $i);
                            $isTersedia=in_array($jamMulai, $jamMulaiFilter ?? []); $semuaJam[]=[ 'jam'=> $jamMulai,
                            'tersedia' => $isTersedia
                            ];
                            }
                            @endphp

                            @if(count($semuaJam) > 0)
                            @foreach($semuaJam as $item)
                            <label
                                class="relative flex items-center justify-center {{ !$item['tersedia'] ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                <input type="radio" name="jam_mulai" value="{{ $item['jam'] }}" class="hidden" {{
                                    old('jam_mulai')==$item['jam'] ? 'checked' : '' }} onclick="updateSelectedStyle()"
                                    {{ !$item['tersedia'] ? 'disabled' : '' }}>
                                <div class="w-28 py-2 px-2 text-center rounded-lg border transition-all
                                    {{ $item['tersedia'] 
                                        ? 'bg-white border-gray-300 hover:bg-blue-600 hover:text-white hover:border-blue-600 group' 
                                        : 'bg-gray-100 border-gray-200 text-gray-400' }}">
                                    <div
                                        class="font-semibold text-sm {{ $item['tersedia'] ? 'text-gray-700 group-hover:text-white' : 'text-gray-400' }}">
                                        {{ $item['jam'] }}
                                    </div>
                                    <div
                                        class="text-[11px] {{ $item['tersedia'] ? 'text-gray-500 group-hover:text-white' : 'text-gray-400' }}">
                                        s/d {{ date('H:i', strtotime($item['jam']) + ($durasiPaket * 3600)) }}
                                    </div>
                                    @if(!$item['tersedia'])
                                    @endif
                                </div>
                            </label>
                            @endforeach
                            @else
                            <div class="w-full text-center py-3 text-red-500 bg-red-50 rounded-lg text-sm">
                                Tidak ada jam mulai tersedia untuk durasi {{ $durasiPaket }} jam di tanggal ini
                            </div>
                            @endif
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Durasi paket: <strong id="durasiInfo">{{ $durasiPaket }}</strong> jam.
                        Pilih jam mulai, maka otomatis booking <strong id="durasiInfo2">{{ $durasiPaket }}</strong>
                        jam berturut-turut.
                    </p>

                    @error('jam_mulai')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan: (Opsional)
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 border px-3 py-2">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ url()->previous() }}"
                        class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-700 text-white rounded-full hover:bg-blue-600 transition shadow-md">
                        Buat Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    const idJasaInput = document.getElementById('id_jasa');
    const tanggalInput = document.getElementById('tanggal_booking');
    const jamContainer = document.getElementById('jamContainer');
    const durasiInfo = document.getElementById('durasiInfo');
    const durasiInfo2 = document.getElementById('durasiInfo2');
    const durasiText = document.getElementById('durasiText');
    
    function updateSelectedStyle() {
        const allRadios = document.querySelectorAll('input[name="jam_mulai"]');
        allRadios.forEach(radio => {
            if (radio.disabled) return;
            
            const parentDiv = radio.nextElementSibling;
            const childDivs = parentDiv?.querySelectorAll('div');
            const jamDiv = childDivs?.[0];
            const selesaiDiv = childDivs?.[1];
            
            if (radio.checked) {
                parentDiv?.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                parentDiv?.classList.remove('bg-white', 'border-gray-300');
                if (jamDiv) {
                    jamDiv.classList.add('text-white');
                    jamDiv.classList.remove('text-gray-700');
                }
                if (selesaiDiv) {
                    selesaiDiv.classList.add('text-white');
                    selesaiDiv.classList.remove('text-gray-500');
                }
            } else {
                parentDiv?.classList.add('bg-white', 'border-gray-300');
                parentDiv?.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                if (jamDiv) {
                    jamDiv.classList.add('text-gray-700');
                    jamDiv.classList.remove('text-white');
                }
                if (selesaiDiv) {
                    selesaiDiv.classList.add('text-gray-500');
                    selesaiDiv.classList.remove('text-white');
                }
            }
        });
    }
    
    function loadAvailableJam() {
        const tanggal = tanggalInput.value;
        const idJasa = idJasaInput.value;
        const durasiPaket = parseInt(durasiInfo.innerText) || 1;
        
        if (!tanggal || !idJasa) return;
        
        fetch(`{{ url('kasir/check-jam') }}?tanggal=${tanggal}&id_jasa=${idJasa}`)
            .then(response => response.json())
            .then(data => {
                const jamTersedia = data.jam_mulai_tersedia;
                const durasi = data.durasi;
                
                durasiInfo.innerText = durasi;
                durasiInfo2.innerText = durasi;
                if (durasiText) durasiText.innerText = durasi;
                
                let html = '';
                const maxJam = 22 - durasi;
                for (let i = 7; i <= maxJam; i++) {
                    const jamMulai = String(i).padStart(2, '0') + ':00';
                    const isTersedia = jamTersedia.includes(jamMulai);
                    const jamInt = i;
                    const jamSelesai = jamInt + durasi;
                    const jamSelesaiFormatted = String(jamSelesai).padStart(2, '0') + ':00';
                    
                    html += `
                        <label class="relative flex items-center justify-center ${!isTersedia ? 'cursor-not-allowed' : 'cursor-pointer'}">
                            <input type="radio" name="jam_mulai" value="${jamMulai}" 
                                class="hidden"
                                onclick="updateSelectedStyle()"
                                ${!isTersedia ? 'disabled' : ''}>
                            <div class="w-28 py-2 px-2 text-center rounded-lg border transition-all
                                ${isTersedia 
                                    ? 'bg-white border-gray-300 hover:bg-blue-600 hover:text-white hover:border-blue-600 group' 
                                    : 'bg-gray-100 border-gray-200 text-gray-400'}">
                                <div class="font-semibold text-sm ${isTersedia ? 'text-gray-700 group-hover:text-white' : 'text-gray-400'}">
                                    ${jamMulai}
                                </div>
                                <div class="text-[11px] ${isTersedia ? 'text-gray-500 group-hover:text-white' : 'text-gray-400'}">
                                    s/d ${jamSelesaiFormatted}
                                </div>
                            </div>
                        </label>
                    `;
                }
                
                if (maxJam >= 7) {
                    jamContainer.innerHTML = html;
                    updateSelectedStyle();
                } else {
                    jamContainer.innerHTML = `
                        <div class="w-full text-center py-4 text-red-500 bg-red-50 rounded-lg">
                            Tidak ada jam mulai tersedia untuk durasi ${durasi} jam di tanggal ini
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    tanggalInput.addEventListener('change', loadAvailableJam);
    idJasaInput.addEventListener('change', loadAvailableJam);
    
    // Load awal
    if (tanggalInput.value && idJasaInput.value) {
        loadAvailableJam();
    }
</script>
@endpush
@endsection