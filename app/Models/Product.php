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
}