<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {

        $query = Transaksi::with('product');

        // FILTER TANGGAL
        if ($request->dari) {
            $query->whereDate('created_at','>=',$request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('created_at','<=',$request->sampai);
        }

        // DATA TRANSAKSI
        $transaksi = $query->latest()->paginate(10);

        // STATISTIK
        $totalPendapatan = Transaksi::sum('total_harga');
        $totalTransaksi = Transaksi::count();

        $pendapatanHariIni = Transaksi::whereDate('created_at',today())
            ->sum('total_harga');

        // PAKET TERLARIS
        $paketTerlaris = Transaksi::select('id_jasa')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('id_jasa')
            ->orderByDesc('total')
            ->with('product')
            ->first();

        return view('admin.laporan.index',[
            'transaksi' => $transaksi,
            'totalPendapatan' => $totalPendapatan,
            'totalTransaksi' => $totalTransaksi,
            'pendapatanHariIni' => $pendapatanHariIni,
            'paketTerlaris' => $paketTerlaris
        ]);
    }

    public function pdf(Request $request)
    {

        $query = Transaksi::with('product');

        if ($request->dari) {
            $query->whereDate('created_at','>=',$request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('created_at','<=',$request->sampai);
        }

        $transaksi = $query->latest()->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'transaksi' => $transaksi
        ]);

        return $pdf->download('laporan-transaksi.pdf');
    }
}