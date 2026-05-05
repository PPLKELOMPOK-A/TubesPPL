<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuktiDonasiController extends Controller
{
    public function index()
    {
        $donasi = collect([
            (object)[
                'judul' => 'Hari Anak Nasional - Panti Bunda Kasih',
                'kategori' => 'Organisasi (Yayasan)',
                'tanggal' => 'Kamis, 30 Mei 2025',
                'foto' => 'donasi1.jpg'
            ],
            (object)[
                'judul' => 'Program Makan Sehat - Yayasan Peduli Sesama',
                'kategori' => 'Organisasi (Yayasan)',
                'tanggal' => 'Kamis, 30 Mei 2025',
                'foto' => 'donasi2.jpg'
            ],
            (object)[
                'judul' => 'Donasi Kasih Natal - Gereja Santo Paulus',
                'kategori' => 'Organisasi (Yayasan)',
                'tanggal' => 'Kamis, 30 Mei 2025',
                'foto' => 'donasi3.jpg'
            ],
            (object)[
                'judul' => 'Jumat Berkah - Masjid Agung',
                'kategori' => 'Kegiatan keagamaan',
                'tanggal' => 'Kamis, 30 Mei 2025',
                'foto' => 'donasi4.jpg'
            ],
        ]);

        return view('bukti-donasi.index', compact('donasi'));
    }

    public function show($id)
{
    $data = (object)[
        'judul' => 'Bukti Penyelesaian Donasi',
        'deskripsi' => 'Penyaluran donasi dilakukan kepada anak-anak panti asuhan dalam rangka Hari Anak Nasional',
        'foto_utama' => 'donasi-utama.jpg',
        'galeri' => [
            'donasi1.jpg',
            'donasi2.jpg',
            'donasi3.jpg',
            'donasi4.jpg'
        ]
    ];

    return view('detailbuktipenyelesaiandonasi', compact('data'));
}
}