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


    public function getStatusTextAttribute()
    {
        if ($this->status !== 'aktif') {
            return 'Tidak Tersedia';
        }

        if ($this->isInProgress()) {
            $lastTransaction = $this->transaksis()
                ->whereIn('status', ['pending', 'proses', 'selesai'])
                ->latest()
                ->first();

            if ($lastTransaction) {
                return match ($lastTransaction->status) {
                    'pending' => 'Pending',
                    'proses' => 'Proses',
                    'selesai' => 'Selesai',
                    default => 'Tersedia'
                };
            }

            return 'Tersedia';
        }

        return 'Tersedia';
    }

    public function getStatusBadgeClassAttribute()
    {
        if ($this->status !== 'aktif') {
            return 'bg-red-100 text-red-800';
        }

        if ($this->isInProgress()) {
            $lastTransaction = $this->transaksis()
                ->whereIn('status', ['pending', 'proses', 'selesai'])
                ->latest()
                ->first();

            if ($lastTransaction) {
                return match ($lastTransaction->status) {
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'proses' => 'bg-blue-100 text-blue-800',
                    'selesai' => 'bg-green-100 text-green-800',
                    default => 'bg-green-100 text-green-800'
                };
            }

            return 'bg-green-100 text-green-800';
        }

        return 'bg-green-100 text-green-800';
    }


    public function getStatusColorAttribute()
    {
        if ($this->status !== 'aktif') {
            return 'text-red-600';
        }

        if ($this->isInProgress()) {
            $lastTransaction = $this->transaksis()
                ->whereIn('status', ['pending', 'proses', 'selesai'])
                ->latest()
                ->first();

            if ($lastTransaction) {
                return match ($lastTransaction->status) {
                    'pending' => 'text-yellow-600',
                    'proses' => 'text-blue-600',
                    'selesai' => 'text-green-600',
                    default => 'text-green-600'
                };
            }

            return 'text-green-600';
        }

        return 'text-green-600';
    }
}
