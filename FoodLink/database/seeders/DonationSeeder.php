<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan data lama agar tidak duplikat saat running seeder
        Donation::query()->delete();

        // 2. PASTIKAN ADA USER (Biar relasi user_id tidak error)
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Donatur Dummy',
                'email' => 'donaturdummy@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'user'
            ]);
        }

        // 3. Data Nasi Kotak (Sesuai Desain Figma)
        Donation::create([
            'user_id' => $user->id,
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

        // 4. Data Roti & Pastry
        Donation::create([
            'user_id' => $user->id,
            'judul' => 'Roti & Pastry',
            'kategori' => 'Bakery Ananda',
            'quantity' => 30,
            'status' => 'menunggu',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'deskripsi' => 'Roti manis sisa display hari ini.',
            'alamat' => 'Jl. Sudirman 45',
            'expired_at' => Carbon::now()->addDays(1), 
            'created_at' => Carbon::now()->subMinutes(35), 
            'updated_at' => Carbon::now()->subMinutes(35),
        ]);

        // 5. Data Mie Ayam & Bakso
        Donation::create([
            'user_id' => $user->id,
            'judul' => 'Mie Ayam & Bakso',
            'kategori' => 'Warung Pak Budi',
            'quantity' => 40,
            'status' => 'menunggu',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'deskripsi' => 'Porsi standar mie ayam siap saji.',
            'alamat' => 'Pasar Lama',
            'expired_at' => Carbon::now()->addHours(5),
            'created_at' => Carbon::now()->subHours(2)->subMinutes(17),
            'updated_at' => Carbon::now()->subHours(2)->subMinutes(17),
        ]);
    }
}