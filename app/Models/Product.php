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
     */
    public function isInProgress()
    {
        return $this->transaksis()
            ->whereIn('status', ['pending', 'proses'])
            ->exists();
    }

    /**
     * CEK APAKAH PRODUK TERSEDIA UNTUK DIPESAN
     * Tersedia jika status aktif DAN TIDAK ADA transaksi pending/proses
     */
    public function isAvailable()
    {
        if ($this->status !== 'aktif') {
            return false;
        }
        
        return !$this->isInProgress();
    }

    /**
     * CEK APAKAH PRODUK SEDANG DIPESAN (ADA TRANSAKSI AKTIF)
     */
    public function hasActiveTransaction()
    {
        return $this->isInProgress();
    }

    /**
     * GET STATUS TEXT UNTUK DITAMPILKAN
     * - Proses: ada transaksi pending/proses
     * - Tersedia: aktif & tidak ada transaksi aktif
     * - Tidak Tersedia: status nonaktif
     */
    public function getStatusTextAttribute()
    {
        if ($this->status !== 'aktif') {
            return 'Tidak Tersedia';
        }
        
        if ($this->isInProgress()) {
            return 'Proses';
        }
        
        return 'Tersedia';
    }

    /**
     * GET BADGE COLOR BERDASARKAN STATUS
     * - Proses: biru (bg-blue-100 text-blue-800)
     * - Tersedia: hijau (bg-green-100 text-green-800)
     * - Tidak Tersedia: merah (bg-red-100 text-red-800)
     */
    public function getStatusBadgeClassAttribute()
    {
        if ($this->status !== 'aktif') {
            return 'bg-red-100 text-red-800';
        }
        
        if ($this->isInProgress()) {
            return 'bg-blue-100 text-blue-800';
        }
        
        return 'bg-green-100 text-green-800';
    }
}