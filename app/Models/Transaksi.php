<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_user',
        'nomor_unik',
        'nama_pelanggan',
        'no_hp_pelanggan',
        'id_jasa',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'uang_bayar',
        'uang_kembali',
        'tanggal_booking',
        'status',
        'status_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'uang_bayar' => 'decimal:2',
        'uang_kembali' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_jasa');
    }

    /**
     * Generate nomor unik
     */
    public static function generateNomorUnik()
    {
        $tanggal = now()->format('Ymd');
        $random = rand(100, 999);
        $last = self::whereDate('created_at', today())->count();
        $urutan = str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        
        return "INV-{$tanggal}-{$urutan}-{$random}";
    }

    /**
     * METHOD INI JANGAN DIPAKE - LANGSUNG DI CONTROLLER AJA
     * Biar nggak ribet casting
     */
    // public function hitungUangKembali()
    // {
    //     $uangKembali = (float) $this->uang_bayar - (float) $this->total_harga;
    //     return $uangKembali;
    // }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }
}