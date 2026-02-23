<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'foto',
        'no_hp',
        'alamat',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah kasir
     */
    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    /**
     * Cek apakah user adalah owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Cek apakah user aktif
     */
    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    /**
     * Relasi ke transaksi
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    /**
     * Relasi ke log aktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_user');
    }
}