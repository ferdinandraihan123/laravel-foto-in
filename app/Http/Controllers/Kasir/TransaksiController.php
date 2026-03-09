<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Product;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{

    public function index(Request $request)
    {
        $query = Transaksi::with(['product','user'])
            ->where('id_user', auth()->id());

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


    public function create()
    {
        $products = Product::where('status', 'aktif')->get();
        return view('kasir.transaksi.create', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp_pelanggan' => 'required|string|max:20',
            'id_jasa' => 'required|exists:products,id_jasa',
            'jumlah' => 'required|integer|min:1',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_booking' => 'nullable|date_format:H:i',
            'catatan' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->id_jasa);

        if (!$product->isAvailable()) {
            return back()->with('error', 'Produk tidak tersedia untuk dipesan.');
        }

        if ($product->isInProgress()) {
            return back()->with('error', 'Produk sedang dalam proses pengerjaan.');
        }

        $tanggalBooking = $request->tanggal_booking;

        if ($request->filled('jam_booking')) {
            $tanggalBooking = $request->tanggal_booking . ' ' . $request->jam_booking . ':00';
        }

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
            'tanggal_booking' => $tanggalBooking,
            'status' => 'pending',
            'status_pembayaran' => 'belum',
            'catatan' => $request->catatan
        ]);

        LogAktivitas::catat(
            'Membuat transaksi baru',
            "Nomor: {$transaksi->nomor_unik}, Pelanggan: {$request->nama_pelanggan}"
        );

        return redirect()->route('kasir.transaksi.show', $transaksi->id_transaksi)
            ->with('success', 'Transaksi berhasil dibuat.');
    }


    public function show($id)
    {
        $transaksi = Transaksi::with(['product', 'product.kategori'])
            ->where('id_user', auth()->id())
            ->findOrFail($id);

        return view('kasir.transaksi.show', compact('transaksi'));
    }


    public function bayar($id)
    {
        $transaksi = Transaksi::where('id_user', auth()->id())->findOrFail($id);

        if ($transaksi->status_pembayaran === 'lunas') {
            return redirect()->route('kasir.transaksi.show', $transaksi->id_transaksi)
                ->with('info', 'Transaksi ini sudah lunas');
        }

        return view('kasir.transaksi.bayar', compact('transaksi'));
    }


    public function prosesBayar(Request $request, $id)
    {
        $transaksi = Transaksi::where('id_user', auth()->id())->findOrFail($id);

        $request->validate([
            'uang_bayar' => 'required|numeric|min:' . (float) $transaksi->total_harga
        ]);

        $uangBayar = (float) $request->uang_bayar;
        $totalHarga = (float) $transaksi->total_harga;
        $uangKembali = $uangBayar - $totalHarga;

        $transaksi->update([
            'uang_bayar' => $uangBayar,
            'uang_kembali' => $uangKembali,
            'status_pembayaran' => 'lunas',
            'status' => 'selesai'
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
        $transaksi = Transaksi::with(['user', 'product'])
            ->where('id_user', auth()->id())
            ->findOrFail($id);

        return view('kasir.transaksi.struk', compact('transaksi'));
    }


    public function downloadStruk($id)
    {
        $transaksi = Transaksi::with(['user', 'product', 'product.kategori'])
            ->where('id_user', auth()->id())
            ->findOrFail($id);

        $pdf = Pdf::loadView('kasir.transaksi.struk-pdf', compact('transaksi'));

        return $pdf->download('struk-' . $transaksi->nomor_unik . '.pdf');
    }


    public function batal($id)
    {
        $transaksi = Transaksi::where('id_user', auth()->id())->findOrFail($id);

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
        $query = Transaksi::with(['product','user'])
            ->where('id_user', auth()->id());

        if ($request->search) {
            $query->where(function($q) use ($request){
                $q->where('nomor_unik','like','%'.$request->search.'%')
                ->orWhere('nama_pelanggan','like','%'.$request->search.'%');
            });
        }

        if ($request->tanggal) {
            $query->whereDate('created_at',$request->tanggal);
        }

        $transaksis = $query->latest()->get();

        return view('kasir.laporan.index',compact('transaksis'));
    }


    public function cetakLaporan(Request $request)
    {
        $query = Transaksi::with(['product','user'])
            ->where('id_user', auth()->id());

        if ($request->search) {
            $query->where(function($q) use ($request){
                $q->where('nomor_unik','like','%'.$request->search.'%')
                ->orWhere('nama_pelanggan','like','%'.$request->search.'%');
            });
        }

        if ($request->tanggal) {
            $query->whereDate('created_at',$request->tanggal);
        }

        $transaksis = $query->latest()->get();

        $pdf = Pdf::loadView('kasir.laporan.pdf',compact('transaksis'));

        return $pdf->download('laporan-transaksi.pdf');
    }
}