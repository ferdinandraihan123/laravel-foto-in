<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        // Hapus data existing
        Transaksi::truncate();

        // Ambil semua kasir aktif
        $kasirs = User::where('role', 'kasir')->where('status', 'aktif')->get();
        
        // Ambil semua produk aktif
        $products = Product::where('status', 'aktif')->get();

        if ($kasirs->isEmpty() || $products->isEmpty()) {
            $this->command->error('Kasir atau produk tidak ditemukan. Jalankan UserSeeder dan ProductSeeder dulu.');
            return;
        }

        $this->command->info('Membuat 100 transaksi dummy...');

        // Array untuk menyimpan nomor unik yang sudah digunakan
        $usedNomorUnik = [];

        // Buat 100 transaksi untuk 3 bulan terakhir
        for ($i = 1; $i <= 100; $i++) {
            // Pilih random kasir
            $kasir = $kasirs->random();
            
            // Pilih random produk
            $product = $products->random();
            
            // Random jumlah (1-3)
            $jumlah = rand(1, 3);
            
            // Hitung total
            $totalHarga = $product->harga * $jumlah;
            
            // Random status pembayaran (90% lunas, 10% belum)
            $statusPembayaran = rand(1, 100) <= 90 ? 'lunas' : 'belum';
            
            // Random status transaksi
            if ($statusPembayaran == 'lunas') {
                $status = 'selesai';
                $uangBayar = $totalHarga + rand(0, 200000);
                $uangKembali = $uangBayar - $totalHarga;
            } else {
                $status = 'pending';
                $uangBayar = 0;
                $uangKembali = 0;
            }
            
            // Random tanggal dalam 3 bulan terakhir
            $tanggal = Carbon::now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Random tanggal booking (bisa sebelum atau sesudah transaksi)
            $tanggalBooking = $tanggal->copy()->addDays(rand(-5, 30));
            
            // Nama pelanggan random
            $namaPelanggan = [
                'Budi Santoso', 'Siti Aminah', 'Ahmad Fauzi', 'Dewi Lestari', 'Rudi Hermawan',
                'Maya Sari', 'Joko Widodo', 'Rina Wijaya', 'Andi Saputra', 'Linda Kusuma',
                'Bayu Prasetyo', 'Ratna Dewi', 'Dedi Kurniawan', 'Sri Wahyuni', 'Hendra Gunawan',
                'Nina Agustina', 'Agus Salim', 'Rina Melati', 'Bambang Sutrisno', 'Wulan Sari'
            ];
            
            // Generate nomor unik yang benar-benar unik
            do {
                $nomorUnik = 'INV-' . $tanggal->format('Ymd') . '-' . 
                             str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . '-' . 
                             rand(100, 999);
            } while (in_array($nomorUnik, $usedNomorUnik));
            
            $usedNomorUnik[] = $nomorUnik;

            Transaksi::create([
                'id_user' => $kasir->id,
                'nomor_unik' => $nomorUnik,
                'nama_pelanggan' => $namaPelanggan[array_rand($namaPelanggan)],
                'no_hp_pelanggan' => '0812' . rand(10000000, 99999999),
                'id_jasa' => $product->id_jasa,
                'jumlah' => $jumlah,
                'harga_satuan' => $product->harga,
                'total_harga' => $totalHarga,
                'uang_bayar' => $uangBayar,
                'uang_kembali' => $uangKembali,
                'tanggal_booking' => $tanggalBooking,
                'status' => $status,
                'status_pembayaran' => $statusPembayaran,
                'catatan' => rand(0, 1) ? 'Tolong foto di taman' : null,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            // Progress bar sederhana
            if ($i % 10 == 0) {
                $this->command->info("  Progress: $i/100 transaksi");
            }
        }

        // Buat 10 transaksi untuk hari ini (testing)
        $this->command->info('Membuat 10 transaksi hari ini...');
        
        for ($i = 1; $i <= 10; $i++) {
            $kasir = $kasirs->random();
            $product = $products->random();
            $jumlah = rand(1, 2);
            $totalHarga = $product->harga * $jumlah;
            
            // Generate nomor unik untuk hari ini
            do {
                $nomorUnik = 'INV-' . now()->format('Ymd') . '-' . 
                             str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . '-' . 
                             rand(100, 999);
            } while (in_array($nomorUnik, $usedNomorUnik));
            
            $usedNomorUnik[] = $nomorUnik;
            
            Transaksi::create([
                'id_user' => $kasir->id,
                'nomor_unik' => $nomorUnik,
                'nama_pelanggan' => 'Pelanggan Hari Ini ' . $i,
                'no_hp_pelanggan' => '0813' . rand(10000000, 99999999),
                'id_jasa' => $product->id_jasa,
                'jumlah' => $jumlah,
                'harga_satuan' => $product->harga,
                'total_harga' => $totalHarga,
                'uang_bayar' => $totalHarga,
                'uang_kembali' => 0,
                'tanggal_booking' => now()->addDays(rand(0, 7)),
                'status' => 'selesai',
                'status_pembayaran' => 'lunas',
                'created_at' => now()->subHours(rand(1, 8)),
                'updated_at' => now()->subHours(rand(1, 8)),
            ]);
        }

        $totalTransaksi = Transaksi::count();
        $this->command->info("Total transaksi: $totalTransaksi");
    }
}