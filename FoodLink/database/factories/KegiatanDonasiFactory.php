<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class KegiatanDonasiFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Jika butuh user_id, factory akan otomatis membuatkan user dummy
            'user_id' => User::factory(), 
            'judul_donasi' => $this->faker->sentence(3),
            'kategori_penerima' => 'Panti Asuhan',
            'tanggal_kegiatan' => $this->faker->date(),
            'deskripsi' => $this->faker->paragraph(),
            'alamat_penyaluran' => $this->faker->address(),
            'foto_kegiatan' => 'default.jpg',
            // Jika ada kolom status dari migration tanggal 31 Mei
            'status' => 'Aktif', 
        ];
    }
}