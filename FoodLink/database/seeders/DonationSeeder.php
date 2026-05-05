<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User; // <-- Tambahan: Import model User
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama dengan delete() agar lebih aman dari error constraint
        Donation::query()->delete();

        // 1. PASTIKAN ADA USER: Cari user pertama, atau buat baru kalau tabel users kosong
        $user = User::firstOrCreate(
            ['email' => 'donaturdummy@gmail.com'],
            [
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
        ]);
    }
}