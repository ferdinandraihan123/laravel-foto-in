<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Matikan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus transaksi dulu (karena foreign key ke products)
        Transaksi::truncate();
        
        // Hapus log aktivitas juga (karena mungkin ada relasi)
        \App\Models\LogAktivitas::truncate();
        
        // Baru hapus products
        Product::truncate();
        
        // Hidupkan lagi foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil semua kategori
        $kategoris = Kategori::all()->keyBy('nama_kategori');

        $products = [
            // WEDDING (kategori_id = 1)
            [
                'id_kategori' => $kategoris['Wedding']->id_kategori,
                'nama_jasa' => 'Wedding Basic',
                'deskripsi' => 'Paket foto pernikahan basic. Durasi 4 jam, 1 fotografer, 100 foto edit, cetak 2 lembar 20R, softcopy semua foto.',
                'harga' => 2500000,
                'durasi' => 4,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Wedding']->id_kategori,
                'nama_jasa' => 'Wedding Premium',
                'deskripsi' => 'Paket foto pernikahan premium. Durasi 8 jam, 2 fotografer, 300 foto edit, album mewah 40 halaman, cetak 5 lembar 24R, softcopy semua foto.',
                'harga' => 5000000,
                'durasi' => 8,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Wedding']->id_kategori,
                'nama_jasa' => 'Wedding Exclusive',
                'deskripsi' => 'Paket foto pernikahan exclusive. Durasi 12 jam, 3 fotografer, 500 foto edit, album mewah 60 halaman, cetak 10 lembar 24R, videografer, drone, softcopy semua foto.',
                'harga' => 10000000,
                'durasi' => 12,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // PRE-WEDDING (kategori_id = 2)
            [
                'id_kategori' => $kategoris['Pre-wedding']->id_kategori,
                'nama_jasa' => 'Pre-wedding Indoor',
                'deskripsi' => 'Sesi foto pre-wedding indoor. Durasi 2 jam, 50 foto edit, cetak 2 lembar 16R, softcopy semua foto.',
                'harga' => 1500000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Pre-wedding']->id_kategori,
                'nama_jasa' => 'Pre-wedding Outdoor',
                'deskripsi' => 'Sesi foto pre-wedding outdoor. Durasi 3 jam, 75 foto edit, cetak 3 lembar 20R, softcopy semua foto, include transportasi.',
                'harga' => 2000000,
                'durasi' => 3,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Pre-wedding']->id_kategori,
                'nama_jasa' => 'Pre-wedding Destination',
                'deskripsi' => 'Sesi foto pre-wedding di luar kota. Durasi 2 hari 1 malam, 200 foto edit, album 40 halaman, cetak 5 lembar 24R, softcopy semua foto, include akomodasi.',
                'harga' => 7500000,
                'durasi' => 24,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // GRADUATION (kategori_id = 3)
            [
                'id_kategori' => $kategoris['Graduation']->id_kategori,
                'nama_jasa' => 'Wisuda Basic',
                'deskripsi' => 'Foto wisuda indoor. Durasi 1 jam, 20 foto edit, cetak 2 lembar 16R, softcopy semua foto.',
                'harga' => 500000,
                'durasi' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Graduation']->id_kategori,
                'nama_jasa' => 'Wisuda Premium',
                'deskripsi' => 'Foto wisuda indoor+outdoor. Durasi 2 jam, 40 foto edit, cetak 4 lembar 20R, frame, softcopy semua foto.',
                'harga' => 1000000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Graduation']->id_kategori,
                'nama_jasa' => 'Wisuda Family Package',
                'deskripsi' => 'Foto wisuda dengan keluarga. Durasi 3 jam, 60 foto edit, cetak 6 lembar 20R, 2 frame, softcopy semua foto.',
                'harga' => 1500000,
                'durasi' => 3,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // FAMILY (kategori_id = 4)
            [
                'id_kategori' => $kategoris['Family']->id_kategori,
                'nama_jasa' => 'Family Basic',
                'deskripsi' => 'Sesi foto keluarga. Durasi 2 jam, 30 foto edit, cetak 1 lembar 20R, softcopy semua foto.',
                'harga' => 800000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Family']->id_kategori,
                'nama_jasa' => 'Family Premium',
                'deskripsi' => 'Sesi foto keluarga premium. Durasi 4 jam, 60 foto edit, cetak 3 lembar 20R, 1 album 20 halaman, softcopy semua foto.',
                'harga' => 1500000,
                'durasi' => 4,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Family']->id_kategori,
                'nama_jasa' => 'Family Extended',
                'deskripsi' => 'Sesi foto keluarga besar. Durasi 6 jam, 100 foto edit, cetak 5 lembar 24R, 2 album 30 halaman, softcopy semua foto.',
                'harga' => 2500000,
                'durasi' => 6,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // PORTRAIT (kategori_id = 5)
            [
                'id_kategori' => $kategoris['Portrait']->id_kategori,
                'nama_jasa' => 'Portrait Session',
                'deskripsi' => 'Sesi foto portrait personal. Durasi 1.5 jam, 15 foto edit, softcopy semua foto.',
                'harga' => 600000,
                'durasi' => 1.5,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Portrait']->id_kategori,
                'nama_jasa' => 'Professional Headshot',
                'deskripsi' => 'Foto professional untuk profil perusahaan. Durasi 1 jam, 10 foto edit, softcopy, cetak 2 lembar.',
                'harga' => 500000,
                'durasi' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Portrait']->id_kategori,
                'nama_jasa' => 'Artistic Portrait',
                'deskripsi' => 'Sesi foto portrait artistik dengan konsep. Durasi 2 jam, 20 foto edit, cetak 2 lembar 20R, softcopy.',
                'harga' => 900000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // EVENT (kategori_id = 6)
            [
                'id_kategori' => $kategoris['Event']->id_kategori,
                'nama_jasa' => 'Event Documentation Basic',
                'deskripsi' => 'Dokumentasi acara. Durasi 4 jam, 100 foto edit, softcopy.',
                'harga' => 2000000,
                'durasi' => 4,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Event']->id_kategori,
                'nama_jasa' => 'Event Documentation Premium',
                'deskripsi' => 'Dokumentasi acara premium. Durasi 8 jam, 300 foto edit, cetak 5 lembar 20R, softcopy.',
                'harga' => 3500000,
                'durasi' => 8,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // PRODUCT PHOTOGRAPHY (kategori_id = 7)
            [
                'id_kategori' => $kategoris['Product Photography']->id_kategori,
                'nama_jasa' => 'Product Basic',
                'deskripsi' => 'Foto produk untuk katalog. 20 foto edit, background putih, softcopy.',
                'harga' => 400000,
                'durasi' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Product Photography']->id_kategori,
                'nama_jasa' => 'Product Premium',
                'deskripsi' => 'Foto produk dengan styling. 50 foto edit, background variatif, softcopy.',
                'harga' => 900000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // NEWBORN (kategori_id = 8)
            [
                'id_kategori' => $kategoris['Newborn']->id_kategori,
                'nama_jasa' => 'Newborn Basic',
                'deskripsi' => 'Sesi foto newborn. Durasi 2 jam, 20 foto edit, cetak 2 lembar, softcopy.',
                'harga' => 800000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Newborn']->id_kategori,
                'nama_jasa' => 'Newborn Premium',
                'deskripsi' => 'Sesi foto newborn premium. Durasi 4 jam, 40 foto edit, cetak 4 lembar, album 20 halaman, softcopy.',
                'harga' => 1500000,
                'durasi' => 4,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ENGAGEMENT (kategori_id = 9)
            [
                'id_kategori' => $kategoris['Engagement']->id_kategori,
                'nama_jasa' => 'Engagement Basic',
                'deskripsi' => 'Sesi foto tunangan. Durasi 2 jam, 30 foto edit, cetak 2 lembar, softcopy.',
                'harga' => 1200000,
                'durasi' => 2,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => $kategoris['Engagement']->id_kategori,
                'nama_jasa' => 'Engagement Premium',
                'deskripsi' => 'Sesi foto tunangan premium. Durasi 4 jam, 60 foto edit, cetak 4 lembar, album 30 halaman, softcopy.',
                'harga' => 2000000,
                'durasi' => 4,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MATERNITY (nonaktif)
            [
                'id_kategori' => $kategoris['Maternity']->id_kategori,
                'nama_jasa' => 'Maternity Session',
                'deskripsi' => 'Sesi foto ibu hamil. Durasi 2 jam, 25 foto edit, cetak 2 lembar, softcopy.',
                'harga' => 1000000,
                'durasi' => 2,
                'status' => 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Produk berhasil dibuat: ' . count($products) . ' paket foto');
    }
}