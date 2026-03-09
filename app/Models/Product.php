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
     * FORMAT HARGA - CASTING KE FLOAT DULU
     */
    public function getHargaFormattedAttribute()
    {
        $harga = (float) $this->harga;
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }

    /**
     * CEK APAKAH PRODUK TERSEDIA UNTUK DIPESAN
     */
    public function isAvailable()
    {
        return $this->status === 'aktif';
    }

    /**
     * CEK APAKAH PRODUK SEDANG DALAM PROSES PENGERJAAN
     */
    public function isInProgress()
    {
        // Cek apakah ada transaksi dengan status 'proses' atau 'pending' untuk produk ini
        return $this->transaksis()
            ->whereIn('status', ['proses', 'pending'])
            ->exists();
    }

    /**
     * CEK APAKAH PRODUK SEDANG DIPESAN (ADA TRANSAKSI AKTIF)
     */
    public function hasActiveTransaction()
    {
        return $this->transaksis()
            ->whereIn('status', ['pending', 'proses'])
            ->exists();
    }

    /**
     * GET STATUS TEXT UNTUK DITAMPILKAN
     */
    public function getStatusTextAttribute()
    {
        if ($this->isInProgress()) {
            return 'Sedang Diproses';
        }
        return $this->status == 'aktif' ? 'Tersedia' : 'Tidak Tersedia';
    }

    /**
     * GET BADGE COLOR BERDASARKAN STATUS
     */
    public function getStatusBadgeClassAttribute()
    {
        if ($this->isInProgress()) {
            return 'bg-yellow-100 text-yellow-800';
        }
        return $this->status == 'aktif' 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }
}