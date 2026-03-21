<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Product;
use App\Models\User;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // PRODUK TERBARU
        $produkTerbaru = Product::with('kategori')
            ->withCount('transaksis')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // PENDAPATAN BULANAN
        $pendapatanBulanan = Transaksi::selectRaw('MONTH(created_at) as bulan, YEAR(created_at) as tahun, SUM(total_harga) as total')
            ->where('status_pembayaran', 'lunas')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        // PRODUK TERLARIS
        $produkTerlaris = Product::withCount('transaksis')
            ->where('status', 'aktif')
            ->orderBy('transaksis_count', 'desc')
            ->take(5)
            ->get();

        // KASIR TERBAIK
        $kasirTerbaik = User::where('role', 'kasir')
            ->where('status', 'aktif')
            ->withCount('transaksis')
            ->orderBy('transaksis_count', 'desc')
            ->take(5)
            ->get();

        // SEMUA DATA KASIR (UNTUK TABEL KASIR DI DASHBOARD)
        $kasirs = User::where('role', 'kasir')->get();

        $data = [
            'totalTransaksi' => Transaksi::count(),

            'totalPendapatan' => Transaksi::where('status_pembayaran', 'lunas')
                ->sum('total_harga'),

            'totalKasirAktif' => User::where('role', 'kasir')
                ->where('status', 'aktif')
                ->count(),

            'totalKasirNonaktif' => User::where('role', 'kasir')
                ->where('status', 'nonaktif')
                ->count(),

            'totalProduk' => Product::where('status', 'aktif')->count(),

            'totalKategori' => Kategori::count(),

            'transaksiHariIni' => Transaksi::whereDate('created_at', today())->count(),

            'pendapatanHariIni' => Transaksi::whereDate('created_at', today())
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),

            'produkTerbaru' => $produkTerbaru,

            'dataSewa' => Transaksi::with(['user', 'product', 'product.kategori'])
                ->latest()
                ->paginate(15),

            'pendapatanBulanan' => $pendapatanBulanan,

            'produkTerlaris' => $produkTerlaris,

            'kasirTerbaik' => $kasirTerbaik,

            // INI YANG MEMPERBAIKI ERROR KAMU
            'kasirs' => $kasirs,

            'logTerbaru' => LogAktivitas::with('user')
                ->latest()
                ->take(20)
                ->get(),
        ];

        LogAktivitas::catat('Mengakses dashboard owner');

        return view('owner.dashboard', $data);
    }
}