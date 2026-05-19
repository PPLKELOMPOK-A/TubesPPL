<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar di database Anda
    protected $table = 'donasi_makanans'; 
    protected $guarded = ['id'];

    // Relasi ke tabel User (jika dibutuhkan nantinya)
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function donatur()
    {
        return $this->belongsTo(User::class, 'donatur_id');
    }
}