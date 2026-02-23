<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_user')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->string('nomor_unik')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_hp_pelanggan', 20);
            $table->foreignId('id_jasa')
                  ->constrained('products', 'id_jasa')
                  ->onDelete('restrict');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('uang_bayar', 15, 2)->default(0);
            $table->decimal('uang_kembali', 15, 2)->default(0);
            $table->date('tanggal_booking');
            $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending');
            $table->enum('status_pembayaran', ['belum', 'lunas'])->default('belum');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('nomor_unik');
            $table->index('tanggal_booking');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};