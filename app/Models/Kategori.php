<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';
    
    protected $fillable = [
        'nama_kategori',
        'status'
    ];

    /**
     * Relasi ke products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'id_kategori');
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}