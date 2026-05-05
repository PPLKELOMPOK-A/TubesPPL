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
<<<<<<< HEAD
        // 🔹 DATA LAMA
        'judul',
        'kategori',
        'tanggal',
        'foto',
        'deskripsi',
        'alamat',
        'quantity',
        'food_type',
        'estimated_time',
        'user_id',

        // 🔥 TAMBAHAN UNTUK VALIDASI
        'status',
        'validated_by'
=======
        'judul_donasi', 
        'kategori_penerima', 
        'tanggal_kegiatan', 
        'foto_kegiatan', 
        'deskripsi', 
        'alamat_penyaluran'
>>>>>>> 4a2a289fba580d83b2e5be2144b22ed64b8402ff
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔥 HELPER VALIDASI (BIAR CLEAN)
    |--------------------------------------------------------------------------
    */

    // Scope: data menunggu
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    // Scope: disetujui
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    // Scope: ditolak
    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 RELASI (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}