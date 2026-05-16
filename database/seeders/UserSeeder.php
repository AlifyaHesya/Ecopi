<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Alifya',
            'email' => 'alifyahesya@example.com',
            'password' => bcrypt('282828'),
            'kecamatan' => 'Lowokwaru'
        ]);

        User::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'kecamatan' => 'Klojen'
        ]);
        $user->assignRole('donatur');

    }
}
