<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiBukti extends Model
{
    use HasFactory;

    protected $table = 'donations';

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
        'user_id',
        'rating',
        'komentar',
        'comment',
    ];
}