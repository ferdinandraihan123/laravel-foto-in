<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\LogAktivitas;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalTransaksi' => Transaksi::count(),
            'totalPendapatan' => Transaksi::where('status_pembayaran', 'lunas')->sum('total_harga'),
            'totalKasirAktif' => User::where('role', 'kasir')->where('status', 'aktif')->count(),
            'totalKasirNonaktif' => User::where('role', 'kasir')->where('status', 'nonaktif')->count(),
            'totalProduk' => Product::where('status', 'aktif')->count(),
            'totalKategori' => Kategori::count(),
            'transaksiHariIni' => Transaksi::whereDate('created_at', today())->count(),
            'pendapatanHariIni' => Transaksi::whereDate('created_at', today())
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),
            'transaksiTerbaru' => Transaksi::with(['user', 'product'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        LogAktivitas::catat('Mengakses dashboard admin');
        
        return view('admin.dashboard', $data);
    }
}