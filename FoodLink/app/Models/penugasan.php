<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $fillable = [
        'id_penugasan',
        'id_donasi',
        'nama_donatur',
        'relawan',
        'lokasi_pengambilan',
        'lokasi_pengantaran',
        'tanggal_penugasan'
    ];
}