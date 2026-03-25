<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('product', 'user');

        // FILTER TANGGAL
        if ($request->dari) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        // DATA TRANSAKSI
        $transaksi = $query->latest()->paginate(10);

        // STATISTIK
        $totalPendapatan = Transaksi::where('status_pembayaran', 'lunas')->sum('total_harga');
        $totalTransaksi = Transaksi::where('status_pembayaran', 'lunas')->count();

        $pendapatanHariIni = Transaksi::whereDate('created_at', today())
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        // PAKET TERLARIS
        $paketTerlaris = Transaksi::select('id_jasa')
            ->selectRaw('COUNT(*) as total')
            ->where('status_pembayaran', 'lunas')
            ->groupBy('id_jasa')
            ->orderByDesc('total')
            ->with('product')
            ->first();

        return view('owner.laporan.index', [
            'transaksi' => $transaksi,
            'totalPendapatan' => $totalPendapatan,
            'totalTransaksi' => $totalTransaksi,
            'pendapatanHariIni' => $pendapatanHariIni,
            'paketTerlaris' => $paketTerlaris
        ]);
    }

    public function pdf(Request $request)
    {
        $query = Transaksi::with('product', 'user');

        if ($request->dari) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transaksi = $query->latest()->get();

        $pdf = Pdf::loadView('owner.laporan.pdf', [
            'transaksi' => $transaksi,
            'dari' => $request->dari,
            'sampai' => $request->sampai
        ]);

        return $pdf->download('laporan-transaksi.pdf');
    }
}