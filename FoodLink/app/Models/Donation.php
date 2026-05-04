<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
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
    'status',
    'validated_by',
    'latitude',  // Tambahkan ini
    'longitude', // Tambahkan ini
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
    public function courier()
{
    return $this->belongsTo(Courier::class);
}
}