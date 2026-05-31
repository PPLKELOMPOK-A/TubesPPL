<?php
// app/Models/KegiatanDonasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanDonasi extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_donasis';

    protected $fillable = [
        'judul_donasi',
        'kategori_penerima',
        'tanggal_kegiatan',
        'deskripsi',
        'alamat_penyaluran',
        'foto_kegiatan',
    ];
}