<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAktivitas;
use App\Models\User;
use Carbon\Carbon;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        // Hapus data existing
        LogAktivitas::truncate();

        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('User tidak ditemukan. Jalankan UserSeeder dulu.');
            return;
        }

        $this->command->info('Membuat 200 log aktivitas dummy...');

        $aktivitasList = [
            'Login ke sistem',
            'Logout dari sistem',
            'Melihat dashboard',
            'Membuat transaksi baru',
            'Memproses pembayaran',
            'Mencetak struk',
            'Membatalkan transaksi',
            'Menambah produk baru',
            'Mengupdate produk',
            'Menghapus produk',
            'Menambah user baru',
            'Mengupdate user',
            'Menonaktifkan user',
            'Mengaktifkan user',
            'Menambah kategori',
            'Mengupdate kategori',
            'Menghapus kategori',
            'Melihat laporan harian',
            'Melihat laporan bulanan',
            'Melihat laporan tahunan',
            'Mengekspor laporan',
            'Melihat log aktivitas',
            'Menghapus log aktivitas',
        ];

        for ($i = 1; $i <= 200; $i++) {
            $user = $users->random();
            $aktivitas = $aktivitasList[array_rand($aktivitasList)];
            
            // Random tanggal dalam 3 bulan terakhir
            $tanggal = Carbon::now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Random IP
            $ip = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
            
            // Detail sesuai aktivitas
            $detail = null;
            if (strpos($aktivitas, 'transaksi') !== false) {
                $detail = 'Nomor transaksi: INV-' . $tanggal->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            } elseif (strpos($aktivitas, 'produk') !== false) {
                $detail = 'Produk: ' . ['Wedding', 'Pre-wedding', 'Graduation'][array_rand(['Wedding', 'Pre-wedding', 'Graduation'])];
            } elseif (strpos($aktivitas, 'user') !== false) {
                $detail = 'User: kasir' . rand(1, 5) . '@fotoin.test';
            }

            LogAktivitas::create([
                'id_user' => $user->id,
                'aktivitas' => $aktivitas,
                'detail' => $detail,
                'ip_address' => $ip,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            if ($i % 50 == 0) {
                $this->command->info("  Progress: $i/200 log");
            }
        }

        $totalLog = LogAktivitas::count();
        $this->command->info("Total log aktivitas: $totalLog");
    }
}