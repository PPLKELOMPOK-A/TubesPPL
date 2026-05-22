<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model {
    use HasFactory;

    // 1. Kasih tahu Laravel untuk pakai tabel ini
    protected $table = 'donations';

    // 2. Sesuaikan nama kolom persis seperti yang ada di phpMyAdmin
    protected $fillable = [
        'judul_donasi',
        'kategori_penerima',
        'tanggal_kegiatan',
        'foto_kegiatan',
        'deskripsi',
        'alamat_penyaluran',
        'rating',
        'komentar'
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
