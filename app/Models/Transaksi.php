<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROSES = 'proses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_BATAL = 'batal';

    // Status Pembayaran Constants
    const PEMBAYARAN_BELUM = 'belum';
    const PEMBAYARAN_DP = 'dp';
    const PEMBAYARAN_LUNAS = 'lunas';

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
        'jam_booking',
        'status',
        'status_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'jam_booking' => 'string',
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
     * GET STATUS BADGE CLASS
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PROSES => 'bg-blue-100 text-blue-800',
            self::STATUS_SELESAI => 'bg-green-100 text-green-800',
            self::STATUS_BATAL => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * GET STATUS TEXT
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROSES => 'Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_BATAL => 'Batal',
            default => ucfirst($this->status),
        };
    }

    /**
     * GET STATUS PEMBAYARAN BADGE CLASS
     */
    public function getStatusPembayaranBadgeClassAttribute()
    {
        return match ($this->status_pembayaran) {
            self::PEMBAYARAN_BELUM => 'bg-red-100 text-red-800',
            self::PEMBAYARAN_DP => 'bg-yellow-100 text-yellow-800',
            self::PEMBAYARAN_LUNAS => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * GET STATUS PEMBAYARAN TEXT
     */
    public function getStatusPembayaranTextAttribute()
    {
        return match ($this->status_pembayaran) {
            self::PEMBAYARAN_BELUM => 'Belum Bayar',
            self::PEMBAYARAN_DP => 'DP',
            self::PEMBAYARAN_LUNAS => 'Lunas',
            default => ucfirst($this->status_pembayaran),
        };
    }

    /**
     * CEK APAKAH TRANSAKSI SEDANG AKTIF (PENDING ATAU PROSES)
     */
    public function isActive()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROSES]);
    }

    /**
     * CEK APAKAH TRANSAKSI BISA DIBATALKAN
     */
    public function canCancel()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROSES]);
    }

    /**
     * CEK APAKAH TRANSAKSI BISA DIPROSES
     */
    public function canProcess()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * CEK APAKAH TRANSAKSI BISA DISELESAIKAN
     */
    public function canComplete()
    {
        return $this->status === self::STATUS_PROSES;
    }

    /**
     * HITUNG UANG KEMBALI
     */
    public function hitungUangKembali()
    {
        if ($this->uang_bayar && $this->total_harga) {
            return (float) $this->uang_bayar - (float) $this->total_harga;
        }
        return 0;
    }

    /**
     * SCOPE
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROSES]);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProses($query)
    {
        return $query->where('status', self::STATUS_PROSES);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    public function scopeBatal($query)
    {
        return $query->where('status', self::STATUS_BATAL);
    }
}
