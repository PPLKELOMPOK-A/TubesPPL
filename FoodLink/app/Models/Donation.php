<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Donation extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'judul',
//         'kategori',
//         'tanggal',
//         'foto',
//         'deskripsi',
//         'alamat',
//         'status',
//         'quantity',
//         'food_type',
//         'estimated_time',
//         'user_id' // 🔥 WAJIB kalau kamu pakai where user_id
//     ];
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model {
    use HasFactory;

    // 1. Kasih tahu Laravel untuk pakai tabel ini
    protected $table = 'kegiatan_donasis';

    // 2. Sesuaikan nama kolom persis seperti yang ada di phpMyAdmin
    protected $fillable = [
        'judul_donasi', 
        'kategori_penerima', 
        'tanggal_kegiatan', 
        'foto_kegiatan', 
        'deskripsi', 
        'alamat_penyaluran'
    ];
}