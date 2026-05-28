<?php
// app/Models/DonasiMakanan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiMakanan extends Model
{
    use HasFactory;

    protected $table = 'donasi_makanans';

    protected $fillable = [
        'nama_donatur',
        'no_telp',
        'email',
        'kategori_penerima',
        'kategori_wilayah',
        'lokasi_dropbox',
        'kategori_makanan',
        'waktu_layak',
        'deskripsi',
        'foto_makanan',
        'status'
    ];

    // --- TAMBAHKAN RELASI INI ---
    // Menghubungkan DonasiMakanan dengan KegiatanDonasi berdasarkan kesamaan kategori_penerima
    public function kegiatanDonasi()
    {
        return $this->belongsTo(KegiatanDonasi::class, 'kategori_penerima', 'kategori_penerima');
    }
}