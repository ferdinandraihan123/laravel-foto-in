<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('owner.laporan.index');
    }

    public function harian(Request $request)
    {
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : today();
        
        $transaksis = Transaksi::with(['user', 'product'])
            ->whereDate('created_at', $tanggal)
            ->where('status_pembayaran', 'lunas')
            ->get();
        
        $total = 0;
        foreach ($transaksis as $t) {
            $total += (float) $t->total_harga;
        }
        $jumlahTransaksi = $transaksis->count();

        if ($request->has('export')) {
            $pdf = Pdf::loadView('owner.laporan.pdf-harian', [
                'transaksis' => $transaksis, 
                'tanggal' => $tanggal, 
                'total' => $total, 
                'jumlahTransaksi' => $jumlahTransaksi
            ]);
            return $pdf->download('laporan-harian-' . $tanggal->format('Y-m-d') . '.pdf');
        }

        return view('owner.laporan.harian', compact('transaksis', 'tanggal', 'total', 'jumlahTransaksi'));
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        
        $transaksis = Transaksi::with(['user', 'product'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status_pembayaran', 'lunas')
            ->get();
        
        $total = 0;
        foreach ($transaksis as $t) {
            $total += (float) $t->total_harga;
        }
        $jumlahTransaksi = $transaksis->count();

        if ($request->has('export')) {
            $pdf = Pdf::loadView('owner.laporan.pdf-bulanan', [
                'transaksis' => $transaksis,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total' => $total,
                'jumlahTransaksi' => $jumlahTransaksi
            ]);
            return $pdf->download('laporan-bulanan-' . $bulan . '-' . $tahun . '.pdf');
        }

        return view('owner.laporan.bulanan', compact('transaksis', 'bulan', 'tahun', 'total', 'jumlahTransaksi'));
    }

    public function tahunan(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;
        
        $transaksis = Transaksi::with(['user', 'product'])
            ->whereYear('created_at', $tahun)
            ->where('status_pembayaran', 'lunas')
            ->get();
        
        $total = 0;
        foreach ($transaksis as $t) {
            $total += (float) $t->total_harga;
        }
        $jumlahTransaksi = $transaksis->count();

        if ($request->has('export')) {
            $pdf = Pdf::loadView('owner.laporan.pdf-tahunan', [
                'transaksis' => $transaksis,
                'tahun' => $tahun,
                'total' => $total,
                'jumlahTransaksi' => $jumlahTransaksi
            ]);
            return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
        }

        return view('owner.laporan.tahunan', compact('transaksis', 'tahun', 'total', 'jumlahTransaksi'));
    }

    public function kinerjaKasir(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $kasirs = User::where('role', 'kasir')->where('status', 'aktif')->get();
        $dataKasir = [];

        foreach ($kasirs as $kasir) {
            $transaksis = Transaksi::where('id_user', $kasir->id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status_pembayaran', 'lunas')
                ->get();

            $dataKasir[] = [
                'kasir' => $kasir,
                'total_transaksi' => $transaksis->count(),
                'total_pendapatan' => $transaksis->sum('total_harga')
            ];
        }

        usort($dataKasir, function($a, $b) {
            return $b['total_transaksi'] <=> $a['total_transaksi'];
        });

        return view('owner.laporan.kinerja-kasir', compact('dataKasir', 'bulan', 'tahun'));
    }

    public function produkPopuler(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $products = Product::where('status', 'aktif')->get();
        $dataProduk = [];

        foreach ($products as $product) {
            $transaksis = Transaksi::where('id_jasa', $product->id_jasa)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status_pembayaran', 'lunas')
                ->get();

            $dataProduk[] = [
                'produk' => $product,
                'total_terjual' => $transaksis->sum('jumlah'),
                'total_pendapatan' => $transaksis->sum('total_harga')
            ];
        }

        usort($dataProduk, function($a, $b) {
            return $b['total_terjual'] <=> $a['total_terjual'];
        });

        return view('owner.laporan.produk-populer', compact('dataProduk', 'bulan', 'tahun'));
    }
}