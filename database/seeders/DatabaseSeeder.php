<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('====================================');
        $this->command->info('MULAI SEEDING DATABASE FOTO.IN');
        $this->command->info('====================================');

        // Urutan seeding penting karena relasi foreign key
        $this->call(UserSeeder::class);
        $this->command->info('------------------------------------');

        $this->call(KategoriSeeder::class);
        $this->command->info('------------------------------------');

        $this->call(ProductSeeder::class);
        $this->command->info('------------------------------------');

        $this->call(TransaksiSeeder::class);
        $this->command->info('------------------------------------');

        $this->call(LogAktivitasSeeder::class);
        $this->command->info('------------------------------------');

        $this->command->info('====================================');
        $this->command->info('SEEDING SELESAI!');
        $this->command->info('====================================');
        
        // Tampilkan ringkasan
        $this->command->table(
            ['Tabel', 'Jumlah Data'],
            [
                ['Users', \App\Models\User::count()],
                ['Kategoris', \App\Models\Kategori::count()],
                ['Products', \App\Models\Product::count()],
                ['Transaksis', \App\Models\Transaksi::count()],
                ['Log Aktivitas', \App\Models\LogAktivitas::count()],
            ]
        );
    }
}