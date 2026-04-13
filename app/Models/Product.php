<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_jasa';

    protected $fillable = [
        'id_kategori',
        'nama_jasa',
        'deskripsi',
        'harga',
        'durasi',
        'gambar',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'durasi' => 'integer'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_jasa');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * FORMAT HARGA
     */
    public function getHargaFormattedAttribute()
    {
        $harga = (float) $this->harga;
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }

    /**
     * CEK APAKAH PRODUK SEDANG ADA TRANSAKSI AKTIF (PENDING ATAU PROSES)
     * Method ini untuk keperluan ADMIN saja
     */
    public function isInProgress()
    {
        return $this->transaksis()
            ->whereIn('status', ['pending', 'proses'])
            ->exists();
    }

    /**
     * CEK APAKAH PRODUK TERSEDIA UNTUK DIPESAN DI KASIR
     * Hanya cek status aktif, TIDAK cek transaksi
     */
    public function isAvailable()
    {
        return $this->status === 'aktif';
    }

    /**
     * CEK KETERSEDIAAN PRODUK BERDASARKAN TANGGAL (UNTUK VALIDASI DOUBLE BOOKING)
     */
    public function isAvailableForDate($tanggal)
    {
        $existingTransaction = $this->transaksis()
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'proses', 'confirmed'])
            ->exists();

        return !$existingTransaction;
    }

    /**
     * CEK APAKAH PRODUK SEDANG DIPESAN (ADA TRANSAKSI AKTIF)
     */
    public function hasActiveTransaction()
    {
        return $this->isInProgress();
    }

    /**
     * GET STATUS TEXT (Aktif / Nonaktif)
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    /**
     * GET STATUS BADGE CLASS
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'aktif'
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    /**
     * GET STATUS COLOR
     */
    public function getStatusColorAttribute()
    {
        return $this->status === 'aktif' ? 'text-green-600' : 'text-red-600';
    }
}
