<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model {
    use HasFactory;

    protected $table = 'kegiatan_donasis';

    // Kolom yang ada di gambar phpMyAdmin kamu
    protected $fillable = [
    'judul_donasi',
    'kategori_penerima',
    'tanggal_kegiatan',
    'foto_kegiatan',
    'deskripsi',
    'alamat_penyaluran',
    'status'
];

    /*
    |--------------------------------------------------------------------------
    | HELPER VALIDASI / SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
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

