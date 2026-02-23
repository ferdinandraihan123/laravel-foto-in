<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_jasa');
            $table->foreignId('id_kategori')
                  ->constrained('kategoris', 'id_kategori')
                  ->onDelete('restrict');
            $table->string('nama_jasa');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('durasi')->comment('Durasi dalam jam');
            $table->string('gambar')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};