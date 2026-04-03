<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});
require __DIR__ . '/auth.php';


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Manajemen User
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::put('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggleStatus');

        // Manajemen Kategori
        Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class);

        // Manajemen Produk
        Route::resource('produk', App\Http\Controllers\Admin\ProductController::class);
        Route::put('/produk/{produk}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('produk.toggle-status');

        // Transaksi Admin
        Route::get('/transaksi', [App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{id}', [App\Http\Controllers\Admin\TransaksiController::class, 'show'])->name('transaksi.show');
        Route::put('/transaksi/{id}/update-status', [App\Http\Controllers\Admin\TransaksiController::class, 'updateStatus'])->name('transaksi.update-status');

        // Laporan Admin
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
    });

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
        Route::get('/check-jam', [App\Http\Controllers\Kasir\TransaksiController::class, 'checkJam'])->name('transaksi.checkJam');
        Route::get('/jadwal', [App\Http\Controllers\Kasir\TransaksiController::class, 'jadwal'])->name('transaksi.jadwal');  // <-- TAMBAHKAN INI
    });

    Route::prefix('owner')->name('owner.')->middleware('role:admin,owner')->group(function () {

        // Dashboard Owner
        Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');

        // Produk Owner
        Route::resource('produk', App\Http\Controllers\Owner\ProductController::class);

        // KATEGORI (READ ONLY)
        Route::get('/kategori', [App\Http\Controllers\Owner\KategoriController::class, 'index'])->name('kategori.index');
        // Laporan Owner
        Route::get('/laporan', [App\Http\Controllers\Owner\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [App\Http\Controllers\Owner\LaporanController::class, 'pdf'])->name('laporan.pdf');

        // USERS OWNER
        Route::get('/users', [App\Http\Controllers\Owner\UserController::class, 'index'])->name('users.index');

        // Log Aktivitas Owner
        Route::prefix('log-aktivitas')->name('log-aktivitas.')->group(function () {
            Route::get('/', [App\Http\Controllers\Owner\LogAktivitasController::class, 'index'])->name('index');
            Route::get('/export', [App\Http\Controllers\Owner\LogAktivitasController::class, 'export'])->name('export');
            Route::post('/clean', [App\Http\Controllers\Owner\LogAktivitasController::class, 'clean'])->name('clean');
            Route::post('/clear-all', [App\Http\Controllers\Owner\LogAktivitasController::class, 'clearAll'])->name('clear-all');
            Route::get('/{logAktivitas}', [App\Http\Controllers\Owner\LogAktivitasController::class, 'show'])->name('show');
            Route::get('/user/{user}', [App\Http\Controllers\Owner\LogAktivitasController::class, 'userLogs'])->name('user');
        });
    });
});
