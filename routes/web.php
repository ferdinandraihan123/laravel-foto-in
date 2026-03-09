<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Kasir\TransaksiController;


// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return redirect()->route('login');
});

// ==================== AUTH ROUTES (dari Breeze) ====================
require __DIR__ . '/auth.php';

// ==================== ROUTES YANG MEMERLUKAN LOGIN ====================
Route::middleware(['auth'])->group(function () {

    // ========== DASHBOARD UTAMA ==========
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== ROUTES ADMIN ====================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Manajemen User
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::put('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggleStatus');

        // Manajemen Kategori
        Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class);

        // MANAJEMEN PRODUK
        Route::resource('produk', App\Http\Controllers\Admin\ProductController::class);
        // TAMBAHKAN ROUTE TOGGLE STATUS PRODUK
        Route::put('/produk/{produk}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('produk.toggle-status');

        // Lihat Transaksi
        Route::get('/transaksi', [App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{id}', [App\Http\Controllers\Admin\TransaksiController::class, 'show'])->name('transaksi.show');

        // Update status transaksi
        Route::put('/transaksi/{id}/status', [App\Http\Controllers\Admin\TransaksiController::class, 'updateStatus'])->name('transaksi.update-status');

        // laporan admin
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
    });

    // ==================== ROUTES KASIR ====================
    Route::prefix('kasir')->name('kasir.')->middleware('role:kasir')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Kasir\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/produk', [App\Http\Controllers\Kasir\ProductController::class, 'index'])->name('produk.index');
        Route::get('/produk/{id}', [App\Http\Controllers\Kasir\ProductController::class, 'show'])->name('produk.show');
        Route::resource('transaksi', App\Http\Controllers\Kasir\TransaksiController::class);
        Route::get('/transaksi/{id}/bayar', [App\Http\Controllers\Kasir\TransaksiController::class, 'bayar'])->name('transaksi.bayar');
        Route::post('/transaksi/{id}/proses-bayar', [App\Http\Controllers\Kasir\TransaksiController::class, 'prosesBayar'])->name('transaksi.prosesBayar');
        Route::get('/transaksi/{id}/struk', [App\Http\Controllers\Kasir\TransaksiController::class, 'struk'])->name('transaksi.struk');
        Route::get('/transaksi/{id}/download-struk', [App\Http\Controllers\Kasir\TransaksiController::class, 'downloadStruk'])->name('transaksi.downloadStruk');
        Route::post('/transaksi/{id}/batal', [App\Http\Controllers\Kasir\TransaksiController::class, 'batal'])->name('transaksi.batal');
    });

    // ==================== ROUTES OWNER ====================
    Route::prefix('owner')->name('owner.')->middleware('role:owner')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/produk', [App\Http\Controllers\Owner\ProductController::class, 'index'])->name('produk.index');

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [App\Http\Controllers\Owner\LaporanController::class, 'index'])->name('index');
            Route::get('/harian', [App\Http\Controllers\Owner\LaporanController::class, 'harian'])->name('harian');
            Route::get('/bulanan', [App\Http\Controllers\Owner\LaporanController::class, 'bulanan'])->name('bulanan');
            Route::get('/tahunan', [App\Http\Controllers\Owner\LaporanController::class, 'tahunan'])->name('tahunan');
            Route::get('/kinerja-kasir', [App\Http\Controllers\Owner\LaporanController::class, 'kinerjaKasir'])->name('kinerja-kasir');
            Route::get('/produk-populer', [App\Http\Controllers\Owner\LaporanController::class, 'produkPopuler'])->name('produk-populer');
        });

        Route::prefix('log-aktivitas')->name('log-aktivitas.')->group(function () {
            Route::get('/', [App\Http\Controllers\Owner\LogAktivitasController::class, 'index'])->name('index');
            Route::get('/export', [App\Http\Controllers\Owner\LogAktivitasController::class, 'export'])->name('export');
            Route::get('/{logAktivitas}', [App\Http\Controllers\Owner\LogAktivitasController::class, 'show'])->name('show');
            Route::get('/user/{user}', [App\Http\Controllers\Owner\LogAktivitasController::class, 'userLogs'])->name('user');
        });
    });
});
