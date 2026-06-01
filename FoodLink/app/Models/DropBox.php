<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'lokasi', 'mitra', 'kapasitas', 'status', 
        'lat', 'lng', 'keterangan_status', 'history', 'active_task'
    ];

    // Otomatis mengubah JSON dari database menjadi Array di PHP
    protected $casts = [
        'history' => 'array',
        'active_task' => 'array',
    ];
}