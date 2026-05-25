<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
<<<<<<< HEAD
use App\Models\Donation;
<<<<<<< HEAD
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
=======
use App\Models\User; // <-- Tambahan: Import model User
use Carbon\Carbon;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> 053b9a674eb83b28c88b26ae2a80351b5925932f

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('donations')->insert([
            [
<<<<<<< HEAD
                'name' => 'Donatur Dummy',
                'password' => bcrypt('12345678'),
                'role' => 'user'
            ]
        );

        // 2. Data Nasi Kotak (Persis Figma)
        Donation::create([
            'user_id' => $user->id, // <-- Sekarang otomatis mengambil ID user yang valid
            'judul' => 'Nasi Kotak + Lauk',
            'kategori' => 'Restoran Sederhana', 
            'quantity' => 50,
            'status' => 'menunggu',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'deskripsi' => 'Nasi kotak lengkap ayam bakar.',
            'alamat' => 'Jl. Merdeka No 1',
            'expired_at' => Carbon::now()->addHours(8),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 3. Data Roti & Pastry (Persis Figma)
        Donation::create([
            'user_id' => $user->id, // <-- Menggunakan ID user yang sama
            'judul' => 'Roti & Pastry',
            'kategori' => 'Bakery Ananda',
            'quantity' => 50,
            'status' => 'menunggu',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'deskripsi' => 'Roti manis sisa display hari ini.',
            'alamat' => 'Jl. Sudirman 45',
            'expired_at' => Carbon::now()->addDays(1), 
            'created_at' => Carbon::now()->subMinutes(35), 
            'updated_at' => Carbon::now()->subMinutes(35),
        ]);

        // 4. Data Mie Ayam & Bakso (Persis Figma)
        Donation::create([
            'user_id' => $user->id, // <-- Menggunakan ID user yang sama
            'judul' => 'Mie Ayam & Bakso',
            'kategori' => 'Warung Pak Budi',
            'quantity' => 50,
            'status' => 'menunggu',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'deskripsi' => 'Porsi standar mie ayam.',
            'alamat' => 'Pasar Lama',
            'expired_at' => Carbon::now()->addHours(5),
            'created_at' => Carbon::now()->subHours(2)->subMinutes(17),
            'updated_at' => Carbon::now()->subHours(2)->subMinutes(17),
>>>>>>> 1702c37bbe994028f2c3cee2b01135d95a34769d
=======
                'judul' => 'Program Makan Sehat - Yayasan Peduli',
                'kategori' => 'Organisasi (Yayasan)',
                'tanggal' => '2026-05-30',
                'foto' => 'makan_sehat.jpg',
                'deskripsi' => 'Penyaluran donasi dilakukan kepada anak-anak panti asuhan dalam rangka Hari Anak Nasional',
                'status' => 'Selesai',
                'user_id' => 1,
                'rating' => null,
                'komentar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // tambah data contoh lain jika perlu
>>>>>>> 053b9a674eb83b28c88b26ae2a80351b5925932f
        ]);
    }
}

