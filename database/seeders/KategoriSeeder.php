<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        // Matikan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus produk dulu (karena foreign key ke kategori)
        Product::truncate();
        
        // Hapus kategori
        Kategori::truncate();
        
        // Hidupkan lagi
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $kategoris = [
            [
                'nama_kategori' => 'Wedding',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Pre-wedding',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Graduation',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Family',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Portrait',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Event',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Product Photography',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Newborn',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Engagement',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Maternity',
                'status' => 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }

        $this->command->info('Kategori berhasil dibuat: ' . count($kategoris) . ' kategori');
    }
}