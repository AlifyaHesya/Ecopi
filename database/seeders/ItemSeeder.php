<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Location;
class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                Item::create([
            'user_id' => 1,
            'location_id' => 1,
            'nama_barang' => 'Sepatu Bekas',
            'deskripsi' => 'Masih bagus',
            'kategori' => 'Fashion',
            'ukuran' => '42',
            'status_barang' => 'available',
        ]);

        Item::create([
            'user_id' => 2,
            'location_id' => 2,
            'nama_barang' => 'Tas Sekolah',
            'deskripsi' => 'Layak pakai',
            'kategori' => 'Aksesoris',
            'status_barang' => 'available',
        ]);
    }
}
