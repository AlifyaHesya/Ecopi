<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_id',
        'nama_barang',
        'deskripsi',
        'kategori',
        'variasi',
        'ukuran',
        'status_barang',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function firstImage()
    {
        return $this->hasOne(ItemImage::class)->oldestOfMany();
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanDonasi::class);
    }
        public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}