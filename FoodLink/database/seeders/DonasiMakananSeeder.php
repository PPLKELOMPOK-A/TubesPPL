<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonasiMakananSeeder extends Seeder
{
    public function run()
    {
        $kategori = ['Yayasan & Institusi', 'Individu Berisiko', 'Komunitas Lokal', 'Panti Asuhan'];
        $status = ['selesai', 'selesai', 'selesai', 'selesai', 'pending', 'diretur']; // Perbanyak 'selesai' agar grafik naik

        $data = [];
        
        // Membuat 45 data dummy secara acak untuk 6 bulan terakhir
        for ($i = 0; $i < 45; $i++) {
            // Acak tanggal di 5 bulan ke belakang hingga hari ini
            $tanggal = Carbon::now()->subDays(rand(1, 150));
            
            $data[] = [
                'nama_donatur'      => 'Donatur Dummy ' . rand(1, 20),
                'no_telp'           => '081234567' . rand(100, 999),
                'email'             => 'dummy' . $i . '@gmail.com',
                'kategori_penerima' => $kategori[array_rand($kategori)],
                'kategori_wilayah'  => 'Jakarta Barat',
                'lokasi_dropbox'    => 'Dropbox Pusat',
                'kategori_makanan'  => 'Sembako',
                'waktu_layak'       => '12 Jam',
                'deskripsi'         => 'Data donasi dummy otomatis.',
                'foto_makanan'      => 'dummy_foto.png',
                'status'            => $status[array_rand($status)],
                'created_at'        => $tanggal,
                'updated_at'        => $tanggal,
            ];
        }

        DB::table('donasi_makanans')->insert($data);
    }
}