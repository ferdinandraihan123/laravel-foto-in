<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogAktivitasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public route
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - default redirect after login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ============ MANAJEMEN USER (KELOLA KASIR) ============
    // Hanya admin yang bisa akses (pengecekan di controller)
    Route::resource('users', UserController::class);
    Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    
    // ============ MANAJEMEN KATEGORI ============
    // Hanya admin yang bisa akses (pengecekan di controller)
    Route::resource('kategoris', KategoriController::class);
    
    // ============ MANAJEMEN PRODUK/PAKET ============
    // Admin: full akses, Kasir: hanya lihat
    Route::resource('products', ProductController::class);
    
    // ============ MANAJEMEN TRANSAKSI ============
    // Admin dan Kasir yang bisa akses
    Route::resource('transaksis', TransaksiController::class);
    
    // Route khusus untuk transaksi (pembayaran, struk, dll)
    Route::prefix('transaksis')->name('transaksis.')->group(function () {
        // Pembayaran
        Route::get('/{id}/bayar', [TransaksiController::class, 'bayar'])->name('bayar');
        Route::post('/{id}/proses-bayar', [TransaksiController::class, 'prosesBayar'])->name('prosesBayar');
        
        // Struk
        Route::get('/{id}/struk', [TransaksiController::class, 'struk'])->name('struk');
        Route::get('/{id}/download-struk', [TransaksiController::class, 'downloadStruk'])->name('downloadStruk');
        
        // Pembatalan
        Route::post('/{id}/batal', [TransaksiController::class, 'batal'])->name('batal');
    });
    
    // ============ LAPORAN ============
    // Admin dan Owner yang bisa akses
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/harian', [LaporanController::class, 'harian'])->name('harian');
        Route::get('/bulanan', [LaporanController::class, 'bulanan'])->name('bulanan');
        Route::get('/tahunan', [LaporanController::class, 'tahunan'])->name('tahunan');
        Route::get('/kinerja-kasir', [LaporanController::class, 'kinerjaKasir'])->name('kinerja-kasir');
        Route::get('/produk-populer', [LaporanController::class, 'produkPopuler'])->name('produk-populer');
    });
    
    // ============ LOG AKTIVITAS ============
    // Admin dan Owner yang bisa akses
    Route::prefix('log-aktivitas')->name('log-aktivitas.')->group(function () {
        Route::get('/', [LogAktivitasController::class, 'index'])->name('index');
        Route::get('/export', [LogAktivitasController::class, 'export'])->name('export');
        Route::get('/{logAktivitas}', [LogAktivitasController::class, 'show'])->name('show');
        Route::delete('/{logAktivitas}', [LogAktivitasController::class, 'destroy'])->name('destroy');
        Route::post('/clear-all', [LogAktivitasController::class, 'clearAll'])->name('clear-all');
        Route::post('/clean', [LogAktivitasController::class, 'cleanOldLogs'])->name('clean');
        Route::get('/user/{user}', [LogAktivitasController::class, 'userLogs'])->name('user');
    });
});

// Auth routes (dari Breeze)
require __DIR__.'/auth.php';