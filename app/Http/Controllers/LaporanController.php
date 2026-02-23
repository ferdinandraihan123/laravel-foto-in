<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan
     */
    public function index()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access. Halaman ini hanya untuk Admin dan Owner.');
        }

        return view('laporan.index');
    }

    /**
     * Laporan transaksi harian
     */
    public function harian(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : today();
        
        $transaksis = Transaksi::with(['user', 'product'])
            ->whereDate('created_at', $tanggal)
            ->where('status_pembayaran', 'lunas')
            ->get();
        
        // Hitung total pake loop biasa biar aman
        $total = 0;
        foreach ($transaksis as $t) {
            $total += (float) $t->total_harga;
        }
        $jumlahTransaksi = $transaksis->count();

        if ($request->has('export')) {
            $pdf = Pdf::loadView('laporan.pdf-harian', [
                'transaksis' => $transaksis, 
                'tanggal' => $tanggal, 
                'total' => $total, 
                'jumlahTransaksi' => $jumlahTransaksi
            ]);
            return $pdf->download('laporan-harian-' . $tanggal->format('Y-m-d') . '.pdf');
        }

        return view('laporan.harian', compact('transaksis', 'tanggal', 'total', 'jumlahTransaksi'));
    }

    /**
     * Laporan transaksi bulanan
     */
    public function bulanan(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        
        $transaksis = Transaksi::with(['user', 'product'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status_pembayaran', 'lunas')
            ->get();
        
        // Hitung total
        $total = 0;
        foreach ($transaksis as $t) {
            $total += (float) $t->total_harga;
        }
        $jumlahTransaksi = $transaksis->count();

        if ($request->has('export')) {
            $pdf = Pdf::loadView('laporan.pdf-bulanan', [
                'transaksis' => $transaksis,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total' => $total,
                'jumlahTransaksi' => $jumlahTransaksi
            ]);
            return $pdf->download('laporan-bulanan-' . $bulan . '-' . $tahun . '.pdf');
        }

        return view('laporan.bulanan', compact('transaksis', 'bulan', 'tahun', 'total', 'jumlahTransaksi'));
    }

    /**
     * Laporan transaksi tahunan
     */
    public function tahunan(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $tahun = $request->tahun ?? now()->year;
        
        $transaksis = Transaksi::with(['user', 'product'])
            ->whereYear('created_at', $tahun)
            ->where('status_pembayaran', 'lunas')
            ->get();
        
        // Hitung total
        $total = 0;
        foreach ($transaksis as $t) {
            $total += (float) $t->total_harga;
        }
        $jumlahTransaksi = $transaksis->count();

        if ($request->has('export')) {
            $pdf = Pdf::loadView('laporan.pdf-tahunan', [
                'transaksis' => $transaksis,
                'tahun' => $tahun,
                'total' => $total,
                'jumlahTransaksi' => $jumlahTransaksi
            ]);
            return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
        }

        return view('laporan.tahunan', compact('transaksis', 'tahun', 'total', 'jumlahTransaksi'));
    }
}