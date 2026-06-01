<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komunitas extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional tapi disarankan)
    protected $table = 'komunitas';

    // Kolom-kolom yang diizinkan untuk diisi data
    protected $fillable = [
        'nama_user',
        'judul',
        'isi',
        'kategori',
    ];
}