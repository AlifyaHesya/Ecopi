<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_donasi_id',    // ← PERBAIKAN: bukan 'pengajuan_donasi_id'
        'user_id',
        'rating',
        'komentar',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanDonasi::class, 'pengajuan_donasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}