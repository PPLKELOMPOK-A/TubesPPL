<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('donations')->insert([
            [
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
        ]);
    }
}
