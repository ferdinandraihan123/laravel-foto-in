<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Product;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggalFilter = $request->get('tanggal', today()->format('Y-m-d'));
        $tanggal = Carbon::parse($tanggalFilter);
        
        $produkTerbaru = Product::with('kategori')
            ->where('status', 'aktif')
            ->latest()
            ->take(6)
            ->get();
        
        $produkPopuler = Product::withCount([
            'transaksis' => function ($query) {
                $query->where('id_user', auth()->id());
            }
        ])
            ->where('status', 'aktif')
            ->orderBy('transaksis_count', 'desc')
            ->take(5)
            ->get();

        $data = [
            'transaksiHariIni' => Transaksi::where('id_user', auth()->id())
                ->whereDate('created_at', today())
                ->count(),
            
            'pendapatanHariIni' => Transaksi::where('id_user', auth()->id())
                ->whereDate('created_at', today())
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),
            
            'bookingHariIni' => Transaksi::where('id_user', auth()->id())
                ->whereDate('tanggal_booking', today())
                ->count(),
            
            'totalTransaksi' => Transaksi::where('id_user', auth()->id())->count(),
            'totalPendapatan' => Transaksi::where('id_user', auth()->id())
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),
            
            'produkTerbaru' => $produkTerbaru,
            'produkPopuler' => $produkPopuler,
            
            'jadwalHariIni' => Transaksi::with(['product', 'product.kategori'])
                ->where('id_user', auth()->id())
                ->whereDate('tanggal_booking', $tanggal)
                ->whereIn('status', ['pending', 'proses', 'selesai'])
                ->orderBy('tanggal_booking')
                ->get(),
            
            'riwayatTransaksi' => Transaksi::with(['product', 'product.kategori'])
                ->where('id_user', auth()->id())
                ->latest()
                ->take(10)
                ->get(),
            
            'transaksiPending' => Transaksi::where('id_user', auth()->id())
                ->where('status', 'pending')
                ->where('status_pembayaran', 'belum')
                ->count(),
            
            'tanggalFilter' => $tanggalFilter,
        ];

        LogAktivitas::catat('Mengakses dashboard kasir');
        
        return view('kasir.dashboard', $data);
    }
}