<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model {
    use HasFactory;

    // Nama tabel sesuai phpMyAdmin kamu
    protected $table = 'donations'; 

    // Kolom yang ada di gambar phpMyAdmin kamu
    protected $fillable = [
        'judul',
        'kategori',
        'tanggal',
        'foto',
        'deskripsi',
        'alamat',
        'nama_makanan',
        'donatur',
        'porsi',
        'status',
        'quantity',
        'validated_by',
        'user_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | HELPER SCOPES
    |--------------------------------------------------------------------------
    */

    // public function scopeMenunggu($query) { 
    //     return $query->where('status', 'menunggu'); 
    // }

    // public function scopeDisetujui($query) { 
    //     return $query->where('status', 'disetujui'); 
    // }

    // public function scopeDitolak($query) { 
    //     return $query->where('status', 'ditolak'); 
    // }

    // /*
    // |--------------------------------------------------------------------------
    // | RELATIONS
    // |--------------------------------------------------------------------------
    // */

    // public function user() { 
    //     return $this->belongsTo(User::class); 
    // }

    // public function validator() { 
    //     return $this->belongsTo(User::class, 'validated_by'); 
    // }
}