<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Product;
use App\Models\User;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request; // <-- PENTING: Tambah ini!

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role
     * (Admin, Kasir, atau Owner)
     */
    public function index(Request $request) // <-- Tambah Request $request
    {
        $user = auth()->user();

        // Catat log akses dashboard
        LogAktivitas::catat('Mengakses dashboard', 'Role: ' . $user->role);

        // Arahkan ke dashboard sesuai role
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'kasir') {
            return $this->kasirDashboard($request); // <-- Kirim $request ke kasirDashboard
        } elseif ($user->role === 'owner') {
            return $this->ownerDashboard();
        }

        return redirect('/');
    }

    // ============================================================
    // DASHBOARD ADMIN
    // ============================================================

    /**
     * Dashboard untuk Admin
     * Fitur: Kelola user, kategori, produk, lihat riwayat transaksi
     */
    private function adminDashboard()
    {
        $data = [
            // Statistik Utama
            'totalTransaksi' => Transaksi::count(),
            'totalPendapatan' => Transaksi::where('status_pembayaran', 'lunas')->sum('total_harga'),
            'totalKasirAktif' => User::where('role', 'kasir')->where('status', 'aktif')->count(),
            'totalKasirNonaktif' => User::where('role', 'kasir')->where('status', 'nonaktif')->count(),
            'totalProduk' => Product::where('status', 'aktif')->count(),
            'totalKategori' => Kategori::count(),

            // Statistik Hari Ini
            'transaksiHariIni' => Transaksi::whereDate('created_at', today())->count(),
            'pendapatanHariIni' => Transaksi::whereDate('created_at', today())
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),

            // Transaksi Terbaru (10 data)
            'transaksiTerbaru' => Transaksi::with(['user', 'product'])
                ->latest()
                ->take(10)
                ->get(),

            // Statistik Bulanan untuk grafik
            'statistikBulanan' => Transaksi::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total, SUM(total_harga) as pendapatan')
                ->whereMonth('created_at', now()->month)
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get(),

            // Produk dengan transaksi terbanyak
            'produkPopuler' => Product::withCount('transaksis')
                ->where('status', 'aktif')
                ->orderBy('transaksis_count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('dashboard.admin', $data);
    }

    // ============================================================
    // DASHBOARD KASIR (DENGAN FILTER TANGGAL)
    // ============================================================

    /**
     * Dashboard untuk Kasir
     * Fitur: Lihat produk, transaksi, cetak struk, jadwal booking (bisa filter tanggal)
     */
    private function kasirDashboard(Request $request) // <-- Terima Request
    {
        // AMBIL TANGGAL DARI REQUEST (untuk filter jadwal)
        $tanggalFilter = $request->get('tanggal', today()->format('Y-m-d'));
        $tanggal = Carbon::parse($tanggalFilter);

        // Produk terbaru untuk ditampilkan di dashboard (6 produk)
        $produkTerbaru = Product::with('kategori')
            ->where('status', 'aktif')
            ->latest()
            ->take(6)
            ->get();

        // Produk populer berdasarkan transaksi
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
            // Statistik Kasir
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

            // Data Produk
            'produkTerbaru' => $produkTerbaru,
            'produkPopuler' => $produkPopuler,

            // Jadwal Booking BERDASARKAN FILTER TANGGAL (bukan fix today())
            'jadwalHariIni' => Transaksi::with(['product', 'product.kategori'])
                ->where('id_user', auth()->id())
                ->whereDate('tanggal_booking', $tanggal) // <-- PAKAI VARIABEL $tanggal
                ->whereIn('status', ['pending', 'proses', 'selesai'])
                ->orderBy('tanggal_booking')
                ->get(),

            // Riwayat Transaksi Terbaru (10 data)
            'riwayatTransaksi' => Transaksi::with(['product', 'product.kategori'])
                ->where('id_user', auth()->id())
                ->latest()
                ->take(10)
                ->get(),

            // Transaksi yang perlu diproses (pending)
            'transaksiPending' => Transaksi::where('id_user', auth()->id())
                ->where('status', 'pending')
                ->where('status_pembayaran', 'belum')
                ->count(),

            // Tambahin variabel untuk keperluan view
            'tanggalFilter' => $tanggalFilter,
        ];

        return view('dashboard.kasir', $data);
    }

    // ============================================================
    // DASHBOARD OWNER
    // ============================================================

    /**
     * Dashboard untuk Owner
     * Fitur: Lihat laporan, data sewa, log aktivitas, grafik
     */
    private function ownerDashboard()
    {
        // Produk terbaru untuk ditampilkan di dashboard (6 produk)
        $produkTerbaru = Product::with('kategori')
            ->withCount('transaksis')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Data untuk grafik 6 bulan terakhir
        $pendapatanBulanan = Transaksi::selectRaw('MONTH(created_at) as bulan, YEAR(created_at) as tahun, SUM(total_harga) as total')
            ->where('status_pembayaran', 'lunas')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        // Top 5 produk terlaris
        $produkTerlaris = Product::withCount('transaksis')
            ->where('status', 'aktif')
            ->orderBy('transaksis_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 kasir dengan transaksi terbanyak
        $kasirTerbaik = User::where('role', 'kasir')
            ->where('status', 'aktif')
            ->withCount('transaksis')
            ->orderBy('transaksis_count', 'desc')
            ->take(5)
            ->get();

        $data = [
            // Statistik Utama
            'totalTransaksi' => Transaksi::count(),
            'totalPendapatan' => Transaksi::where('status_pembayaran', 'lunas')->sum('total_harga'),
            'totalKasirAktif' => User::where('role', 'kasir')->where('status', 'aktif')->count(),
            'totalKasirNonaktif' => User::where('role', 'kasir')->where('status', 'nonaktif')->count(),
            'totalProduk' => Product::where('status', 'aktif')->count(),
            'totalKategori' => Kategori::count(),

            // Statistik Hari Ini
            'transaksiHariIni' => Transaksi::whereDate('created_at', today())->count(),
            'pendapatanHariIni' => Transaksi::whereDate('created_at', today())
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),

            // Data Produk (BARU)
            'produkTerbaru' => $produkTerbaru,

            // Data Sewa (transaksi) dengan pagination
            'dataSewa' => Transaksi::with(['user', 'product', 'product.kategori'])
                ->latest()
                ->paginate(15),

            // Statistik Tambahan
            'produkTerlaris' => $produkTerlaris,
            'kasirTerbaik' => $kasirTerbaik,

            // Log Aktivitas Terbaru
            'logTerbaru' => LogAktivitas::with('user')
                ->latest()
                ->take(20)
                ->get(),

            // Statistik per Kategori
            'statistikKategori' => Kategori::withCount('products')
                ->withSum('products', 'harga')
                ->get(),
        ];

        return view('dashboard.owner', $data);
    }
    /**
     * Refresh data dashboard (untuk polling)
     */
    public function refresh(Request $request)
    {
        $user = auth()->user();

        if ($user->isKasir()) {
            $tanggalFilter = $request->get('tanggal', today()->format('Y-m-d'));
            $tanggal = Carbon::parse($tanggalFilter);

            $data = [
                'transaksiHariIni' => Transaksi::where('id_user', auth()->id())
                    ->whereDate('created_at', today())
                    ->count(),
                'bookingHariIni' => Transaksi::where('id_user', auth()->id())
                    ->whereDate('tanggal_booking', today())
                    ->count(),
                'jadwalHariIni' => Transaksi::with('product')
                    ->where('id_user', auth()->id())
                    ->whereDate('tanggal_booking', $tanggal)
                    ->whereIn('status', ['pending', 'proses'])
                    ->get()
            ];

            return response()->json($data);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}