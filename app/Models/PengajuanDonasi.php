<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDonasi extends Model
{
    use HasFactory;

    protected $table      = 'pengajuan_donasi';
    protected $fillable = [
        'item_id',
        'user_id',
        'alasan_kebutuhan',
        'bukti_identitas',
        'metode_ambil',
        'alamat_kirim',
        'kelurahan_kirim',
        'kecamatan_kirim',
        'kota_kirim',
        'status_pengajuan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }

    // ← foreign key eksplisit 'pengajuan_id' (bukan 'pengajuan_donasi_id')
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}