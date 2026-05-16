<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Hapus HasRoles karena Spatie belum tentu terinstall

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telepon',
        'foto_profil',
        'kecamatan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Accessor agar Auth mengenali kolom 'nama'
    public function getNameAttribute()
    {
        return $this->nama;
    }

    // ── Relasi ──────────────────────────────

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanDonasi::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function chatsAsSender()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function chatsAsReceiver()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }
}