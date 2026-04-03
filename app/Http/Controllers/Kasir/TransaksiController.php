<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Product;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Transaksi::with(['product', 'user'])
            ->where('id_user', $user->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_unik', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->latest()->paginate(15);

        return view('kasir.transaksi.index', compact('transaksis'));
    }

    /**
     * JADWAL BOOKING (berdasarkan tanggal_booking)
     */
    public function jadwal(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil semua jadwal dulu (tanpa filter tanggal)
        $query = Transaksi::with(['product', 'user'])
            ->where('id_user', $user->id)
            ->whereIn('status', ['pending', 'proses'])
            ->orderBy('tanggal_booking', 'asc')
            ->orderBy('jam_booking', 'asc');

        // Filter hanya jika ada request tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        $jadwalHariIni = $query->get();

        // Untuk value input tanggal (biar tetap keisi)
        $tanggalFilter = $request->tanggal ?: '';

        return view('kasir.transaksi.jadwal', compact('jadwalHariIni', 'tanggalFilter'));
    }

    public function create(Request $request)
    {
        $products = Product::where('status', 'aktif')->get();
        $selectedId = $request->id_jasa;
        $selectedProduct = $selectedId ? Product::find($selectedId) : null;
        $durasi = $selectedProduct ? (int) $selectedProduct->durasi : 1;

        // Generate jam mulai yang MUNGKIN (07:00 - 22:00 dikurangi durasi)
        $jamMulaiTersedia = [];
        $maxJamMulai = 22 - $durasi;
        for ($i = 7; $i <= $maxJamMulai; $i++) {
            $jamMulaiTersedia[] = sprintf("%02d:00", $i);
        }

        $tanggalDipilih = old('tanggal_booking', now()->format('Y-m-d'));

        // Ambil SEMUA jam mulai yang sudah dibooking
        $semuaJamMulaiTerbooking = Transaksi::where('tanggal_booking', $tanggalDipilih)
            ->whereIn('status', ['pending', 'proses'])
            ->pluck('jam_booking')
            ->filter()
            ->toArray();

        // Buat daftar semua jam yang TERBLOKIR (termasuk jam dalam rentang durasi)
        $jamTerblokir = [];
        foreach ($semuaJamMulaiTerbooking as $jamMulai) {
            $jamMulaiInt = (int) substr($jamMulai, 0, 2);
            for ($i = 0; $i < $durasi; $i++) {
                $jamBlokir = sprintf("%02d:00", $jamMulaiInt + $i);
                $jamTerblokir[] = $jamBlokir;
            }
        }
        $jamTerblokir = array_unique($jamTerblokir);

        // Filter jam mulai yang MASIH TERSEDIA (semua slot durasi kosong)
        $jamMulaiFilter = [];
        foreach ($jamMulaiTersedia as $jamMulai) {
            $jamMulaiInt = (int) substr($jamMulai, 0, 2);
            $semuaSlotKosong = true;

            for ($i = 0; $i < $durasi; $i++) {
                $jamSlot = sprintf("%02d:00", $jamMulaiInt + $i);
                if (in_array($jamSlot, $jamTerblokir)) {
                    $semuaSlotKosong = false;
                    break;
                }
            }

            if ($semuaSlotKosong) {
                $jamMulaiFilter[] = $jamMulai;
            }
        }

        return view('kasir.transaksi.create', compact('products', 'selectedId', 'jamMulaiFilter', 'durasi', 'tanggalDipilih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan'  => 'required|string|max:255',
            'no_hp_pelanggan' => 'required|string|max:20',
            'id_jasa'         => 'required|exists:products,id_jasa',
            'jumlah'          => 'required|integer|min:1',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai'       => 'required|date_format:H:i',
            'catatan'         => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->id_jasa);
        $durasi = (int) $product->durasi;

        $jamMulaiInt = (int) substr($request->jam_mulai, 0, 2);
        $jamYangDibooking = [];
        for ($i = 0; $i < $durasi; $i++) {
            $jam = $jamMulaiInt + $i;
            if ($jam > 22) {
                return back()
                    ->withInput()
                    ->withErrors(['jam_mulai' => 'Jam mulai terlalu sore, melebihi jam operasional 22:00']);
            }
            $jamYangDibooking[] = sprintf("%02d:00", $jam);
        }

        // CEK APAKAH ADA SLOT YANG SUDAH DIBOOKING
        $sudahDibooking = Transaksi::where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('jam_booking', $jamYangDibooking)
            ->whereIn('status', ['pending', 'proses'])
            ->exists();

        if ($sudahDibooking) {
            $jamTampil = implode(', ', $jamYangDibooking);
            return back()
                ->withInput()
                ->withErrors(['jam_mulai' => "Jam {$jamTampil} ada yang sudah dibooking. Pilih jam mulai lain."]);
        }

        // Cek apakah produk tersedia
        if (!$product->isAvailable()) {
            return back()->with('error', 'Produk tidak tersedia atau sedang dipesan oleh pelanggan lain.');
        }

        $hargaSatuan = (float) $product->harga;
        $jumlah      = (int) $request->jumlah;
        $totalHarga  = $hargaSatuan * $jumlah;

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::create([
            'id_user'           => $user->id,
            'nomor_unik'        => Transaksi::generateNomorUnik(),
            'nama_pelanggan'    => $request->nama_pelanggan,
            'no_hp_pelanggan'   => $request->no_hp_pelanggan,
            'id_jasa'           => $request->id_jasa,
            'jumlah'            => $jumlah,
            'harga_satuan'      => $hargaSatuan,
            'total_harga'       => $totalHarga,
            'tanggal_booking'   => $request->tanggal_booking,
            'jam_booking'       => $request->jam_mulai,
            'status'            => 'pending',
            'status_pembayaran' => 'belum',
            'catatan'           => $request->catatan
        ]);

        LogAktivitas::catat(
            'Membuat transaksi baru',
            "Nomor: {$transaksi->nomor_unik}, Pelanggan: {$request->nama_pelanggan}, Jam Mulai: {$request->jam_mulai}, Durasi: {$durasi} jam"
        );

        return redirect()->route('kasir.transaksi.bayar', $transaksi->id_transaksi)
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::with(['product', 'product.kategori'])
            ->where('id_user', $user->id)
            ->findOrFail($id);

        return view('kasir.transaksi.show', compact('transaksi'));
    }

    public function checkJam(Request $request)
    {
        $tanggal = $request->tanggal;
        $idJasa = $request->id_jasa;

        $product = Product::find($idJasa);
        $durasi = $product ? (int) $product->durasi : 1;

        // Ambil SEMUA jam mulai yang sudah dibooking
        $semuaJamMulaiTerbooking = Transaksi::where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'proses'])
            ->pluck('jam_booking')
            ->filter()
            ->toArray();

        // Buat daftar semua jam yang TERBLOKIR (termasuk jam dalam rentang durasi)
        $jamTerblokir = [];
        foreach ($semuaJamMulaiTerbooking as $jamMulai) {
            $jamMulaiInt = (int) substr($jamMulai, 0, 2);
            for ($i = 0; $i < $durasi; $i++) {
                $jamBlokir = sprintf("%02d:00", $jamMulaiInt + $i);
                $jamTerblokir[] = $jamBlokir;
            }
        }
        $jamTerblokir = array_unique($jamTerblokir);

        // Generate semua jam mulai yang mungkin
        $maxJamMulai = 22 - $durasi;
        $jamMulaiTersedia = [];

        for ($i = 7; $i <= $maxJamMulai; $i++) {
            $jamMulai = sprintf("%02d:00", $i);
            $semuaSlotKosong = true;

            for ($j = 0; $j < $durasi; $j++) {
                $jamSlot = sprintf("%02d:00", $i + $j);
                if (in_array($jamSlot, $jamTerblokir)) {
                    $semuaSlotKosong = false;
                    break;
                }
            }

            if ($semuaSlotKosong) {
                $jamMulaiTersedia[] = $jamMulai;
            }
        }

        return response()->json([
            'jam_mulai_tersedia' => $jamMulaiTersedia,
            'durasi' => $durasi
        ]);
    }

    public function bayar($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::where('id_user', $user->id)->findOrFail($id);

        if ($transaksi->status_pembayaran === 'lunas') {
            return redirect()->route('kasir.transaksi.show', $transaksi->id_transaksi)
                ->with('info', 'Transaksi ini sudah lunas');
        }

        return view('kasir.transaksi.bayar', compact('transaksi'));
    }

    public function prosesBayar(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::where('id_user', $user->id)->findOrFail($id);

        $request->validate([
            'uang_bayar' => 'required|numeric|min:' . (float) $transaksi->total_harga
        ]);

        $uangBayar   = (float) $request->uang_bayar;
        $totalHarga  = (float) $transaksi->total_harga;
        $uangKembali = $uangBayar - $totalHarga;

        $transaksi->update([
            'uang_bayar'        => $uangBayar,
            'uang_kembali'      => $uangKembali,
            'status_pembayaran' => 'lunas',
            'status'            => 'pending',
        ]);

        LogAktivitas::catat(
            'Memproses pembayaran',
            "Nomor: {$transaksi->nomor_unik}"
        );

        return redirect()->route('kasir.transaksi.struk', $transaksi->id_transaksi)
            ->with('success', 'Pembayaran berhasil.');
    }

    public function struk($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::with(['user', 'product'])
            ->where('id_user', $user->id)
            ->findOrFail($id);

        return view('kasir.transaksi.struk', compact('transaksi'));
    }

    public function downloadStruk($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::with(['user', 'product', 'product.kategori'])
            ->where('id_user', $user->id)
            ->findOrFail($id);

        $pdf = Pdf::loadView('kasir.transaksi.struk-pdf', compact('transaksi'));

        return $pdf->download('struk-' . $transaksi->nomor_unik . '.pdf');
    }

    public function batal($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaksi = Transaksi::where('id_user', $user->id)->findOrFail($id);

        if ($transaksi->status_pembayaran === 'lunas') {
            return back()->with('error', 'Transaksi yang sudah lunas tidak bisa dibatalkan');
        }

        $transaksi->update(['status' => 'batal']);

        LogAktivitas::catat('Membatalkan transaksi', "Nomor: {$transaksi->nomor_unik}");

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Transaksi dibatalkan');
    }

    /*
    =========================================
    FITUR LAPORAN
    =========================================
    */

    public function laporan(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Transaksi::with(['product', 'user'])
            ->where('id_user', $user->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_unik', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $transaksis = $query->latest()->get();

        return view('kasir.laporan.index', compact('transaksis'));
    }

    public function cetakLaporan(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Transaksi::with(['product', 'user'])
            ->where('id_user', $user->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_unik', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $transaksis = $query->latest()->get();

        $pdf = Pdf::loadView('kasir.laporan.pdf', compact('transaksis'));

        return $pdf->download('laporan-transaksi.pdf');
    }
}
