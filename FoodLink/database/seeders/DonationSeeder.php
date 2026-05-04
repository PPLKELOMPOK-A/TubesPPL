<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Pastikan user ada
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456')
            ]);
        }

        Donation::insert([
            [
                'judul' => 'Nasi Kotak + Lauk',
                'kategori' => 'Restoran Sederhana',
                'tanggal' => now(),
                'deskripsi' => 'Sisa acara syukuran',
                'alamat' => 'Jakarta',
                'status' => 'menunggu',
                'quantity' => 50,
                'user_id' => $user->id, // 🔥 dinamis, tidak hardcode
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Roti & Pastry',
                'kategori' => 'Bakery Ananda',
                'tanggal' => now(),
                'deskripsi' => 'Donasi roti',
                'alamat' => 'Bandung',
                'status' => 'menunggu',
                'quantity' => 30,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Mie Ayam & Bakso',
                'kategori' => 'Warung Pak Budi',
                'tanggal' => now(),
                'deskripsi' => 'Makanan siap saji',
                'alamat' => 'Surabaya',
                'status' => 'menunggu',
                'quantity' => 40,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}