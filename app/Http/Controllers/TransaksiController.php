<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Product;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Tampilkan list transaksi
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isKasir()) {
            abort(403, 'Unauthorized access.');
        }

        $query = Transaksi::with(['user', 'product']);

        if (auth()->user()->isKasir()) {
            $query->where('id_user', auth()->id());
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_unik', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        $transaksis = $query->latest()->paginate(15);

        return view('transaksis.index', compact('transaksis'));
    }

    /**
     * Form transaksi baru
     */
    public function create()
    {
        if (!auth()->user()->isKasir() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $products = Product::where('status', 'aktif')->with('kategori')->get();

        return view('transaksis.create', compact('products'));
    }

    /**
     * Simpan transaksi baru
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isKasir() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp_pelanggan' => 'required|string|max:20',
            'id_jasa' => 'required|exists:products,id_jasa',
            'jumlah' => 'required|integer|min:1',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'catatan' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->id_jasa);

        // CASTING AMAN
        $hargaSatuan = (float) $product->harga;
        $jumlah = (int) $request->jumlah;
        $totalHarga = $hargaSatuan * $jumlah;

        $transaksi = Transaksi::create([
            'id_user' => auth()->id(),
            'nomor_unik' => Transaksi::generateNomorUnik(),
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp_pelanggan' => $request->no_hp_pelanggan,
            'id_jasa' => $request->id_jasa,
            'jumlah' => $jumlah,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'tanggal_booking' => $request->tanggal_booking,
            'status' => 'pending',
            'status_pembayaran' => 'belum',
            'catatan' => $request->catatan
        ]);

        LogAktivitas::catat(
            'Membuat transaksi baru',
            "Nomor: {$transaksi->nomor_unik}, Pelanggan: {$request->nama_pelanggan}"
        );

        return redirect()->route('transaksis.show', $transaksi->id_transaksi)
            ->with('success', 'Transaksi berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Detail transaksi
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'product', 'product.kategori'])->findOrFail($id);

        if (auth()->user()->isKasir() && $transaksi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('transaksis.show', compact('transaksi'));
    }

    /**
     * Form pembayaran
     */
    public function bayar($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if (auth()->user()->isKasir() && $transaksi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($transaksi->status_pembayaran === 'lunas') {
            return redirect()->route('transaksis.show', $transaksi->id_transaksi)
                ->with('info', 'Transaksi ini sudah lunas.');
        }

        return view('transaksis.bayar', compact('transaksi'));
    }

    /**
     * Proses pembayaran
     */
    public function prosesBayar(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if (auth()->user()->isKasir() && $transaksi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'uang_bayar' => 'required|numeric|min:' . (float) $transaksi->total_harga
        ]);

        // CASTING SEMUA KE FLOAT
        $uangBayar = (float) $request->uang_bayar;
        $totalHarga = (float) $transaksi->total_harga;
        $uangKembali = $uangBayar - $totalHarga;

        // UPDATE MANUAL - JANGAN PAKE PROPERTY OBJECT LANGSUNG
        $transaksi->update([
            'uang_bayar' => $uangBayar,
            'uang_kembali' => $uangKembali,
            'status_pembayaran' => 'lunas',
            'status' => 'selesai'
        ]);

        LogAktivitas::catat(
            'Memproses pembayaran',
            "Nomor: {$transaksi->nomor_unik}, Bayar: Rp " . number_format($uangBayar, 0, ',', '.')
        );

        return redirect()->route('transaksis.struk', $transaksi->id_transaksi)
            ->with('success', 'Pembayaran berhasil. Uang kembali: Rp ' . number_format($uangKembali, 0, ',', '.'));
    }

    /**
     * Cetak struk
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with(['user', 'product'])->findOrFail($id);

        if (auth()->user()->isKasir() && $transaksi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('transaksis.struk', compact('transaksi'));
    }

    /**
     * Download struk PDF
     */
    /**
     * Download struk PDF
     */
    public function downloadStruk($id)
    {
        $transaksi = Transaksi::with(['user', 'product', 'product.kategori'])->findOrFail($id);

        if (auth()->user()->isKasir() && $transaksi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $pdf = Pdf::loadView('transaksis.struk-pdf', compact('transaksi'));

        // Nama file: struk-INV-20250221-001-123.pdf
        return $pdf->download('struk-' . $transaksi->nomor_unik . '.pdf');
    }

    /**
     * Batal transaksi
     */
    public function batal($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if (auth()->user()->isKasir() && $transaksi->id_user !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($transaksi->status_pembayaran === 'lunas') {
            return back()->with('error', 'Transaksi yang sudah lunas tidak bisa dibatalkan.');
        }

        $transaksi->update(['status' => 'batal']);

        LogAktivitas::catat('Membatalkan transaksi', "Nomor: {$transaksi->nomor_unik}");

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi dibatalkan.');
    }
}