<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Matikan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus data dari tabel yang memiliki foreign key ke users
        Transaksi::truncate();
        LogAktivitas::truncate();
        
        // Hapus users
        User::truncate();
        
        // Hidupkan lagi foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Buat Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@fotoin.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat Owner
        User::create([
            'name' => 'Pemilik Lontar Fotografi',
            'email' => 'owner@fotoin.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status' => 'aktif',
            'no_hp' => '081298765432',
            'alamat' => 'Jl. Sudirman No. 45, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat 5 Kasir Aktif
        $kasirAktif = [
            [
                'name' => 'Kasir 1 - Budi',
                'email' => 'kasir1@fotoin.test',
                'no_hp' => '081312345678',
                'alamat' => 'Jl. Melati No. 10, Jakarta',
            ],
            [
                'name' => 'Kasir 2 - Siti',
                'email' => 'kasir2@fotoin.test',
                'no_hp' => '081398765432',
                'alamat' => 'Jl. Mawar No. 15, Jakarta',
            ],
            [
                'name' => 'Kasir 3 - Agus',
                'email' => 'kasir3@fotoin.test',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Anggrek No. 20, Jakarta',
            ],
            [
                'name' => 'Kasir 4 - Dewi',
                'email' => 'kasir4@fotoin.test',
                'no_hp' => '081345678912',
                'alamat' => 'Jl. Kenanga No. 25, Jakarta',
            ],
            [
                'name' => 'Kasir 5 - Rudi',
                'email' => 'kasir5@fotoin.test',
                'no_hp' => '081456789123',
                'alamat' => 'Jl. Dahlia No. 30, Jakarta',
            ],
        ];

        foreach ($kasirAktif as $kasir) {
            User::create([
                'name' => $kasir['name'],
                'email' => $kasir['email'],
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'status' => 'aktif',
                'no_hp' => $kasir['no_hp'],
                'alamat' => $kasir['alamat'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buat 2 Kasir Nonaktif
        User::create([
            'name' => 'Kasir Nonaktif - Joko',
            'email' => 'joko@fotoin.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status' => 'nonaktif',
            'no_hp' => '081567891234',
            'alamat' => 'Jl. Flamboyan No. 5, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'Kasir Nonaktif - Maya',
            'email' => 'maya@fotoin.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status' => 'nonaktif',
            'no_hp' => '081678912345',
            'alamat' => 'Jl. Cempaka No. 8, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('User berhasil dibuat: 1 Admin, 1 Owner, 5 Kasir Aktif, 2 Kasir Nonaktif');
    }
}