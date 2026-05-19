<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiPenyelesaianDonasi extends Model
{
    protected $table = 'bukti_penyelesaian_donasis'; // nama tabel

    protected $fillable = [
        'judul',
        'kategori',
        'tanggal',
        'foto_utama',
        'deskripsi',
        'tujuan',
        'jenis_makanan',
        'status'
    ];
}