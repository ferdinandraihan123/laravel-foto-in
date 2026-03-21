<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    
    protected $fillable = [
        'id_user',
        'aktivitas',
        'detail',
        'ip_address',
        'user_agent'
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Method untuk mencatat log
     */
    public static function catat($aktivitas, $detail = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return self::create([
            'id_user'    => $user->id,
            'aktivitas'  => $aktivitas,
            'detail'     => $detail,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Scope untuk log hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }
}