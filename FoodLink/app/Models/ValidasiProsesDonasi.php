<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidasiProsesDonasi extends Model
{
    use HasFactory;

    protected $table = 'validasi_proses_donasi';

    protected $fillable = [
        'donation_id',
        'status',
        'catatan',
        'validated_by'
    ];

    // Relasi ke Donation
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    // Relasi ke User (validator/admin)
    public function user()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}