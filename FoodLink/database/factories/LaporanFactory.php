<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Laporan; // Pastikan ini mengarah ke model yang benar

class LaporanFactory extends Factory
{
    protected $model = Laporan::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            // HAPUS baris 'kegiatan_donasi_id' di bawah ini
            // 'kegiatan_donasi_id' => \App\Models\KegiatanDonasi::factory(), 
            
            'nama_donatur' => $this->faker->name(),
            'no_telp' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'kategori_penerima' => 'Panti Asuhan',
            'kategori_wilayah' => 'Jakarta Selatan',
            'lokasi_dropbox' => 'Dropbox Pusat',
            'kategori_makanan' => 'Makanan Siap Saji',
            'waktu_layak' => '12 Jam',
            'status' => $this->faker->randomElement(['selesai', 'diretur']),
            'foto_makanan' => 'default.jpg',
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}