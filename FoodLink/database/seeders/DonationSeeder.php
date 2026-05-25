<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Donation;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        Donation::create([
            'judul_donasi' => 'Program Makan Sehat',
            'kategori_penerima' => 'Organisasi (Yayasan)',
            'tanggal_kegiatan' => Carbon::now()->format('Y-m-d'),
            'foto_kegiatan' => 'makan_sehat.jpg',
            'deskripsi' => 'Penyaluran makanan untuk anak-anak panti asuhan.',
            'alamat_penyaluran' => 'Jl. Merdeka No 1',
            'status' => 'menunggu',
        ]);
    }
}