<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuktiDonasiController extends Controller
{
    private function getData()
    {
        return collect([
            (object)[
                "id" => 1,
                "judul" => "Hari Anak Nasional - Panti Bunda Kasih",
                "kategori" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "foto" => "donasi1.jpg"
            ],
            (object)[
                "id" => 2,
                "judul" => "Program Makan Sehat - Yayasan Peduli Sesama",
                "kategori" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "foto" => "donasi2.jpg"
            ],
            (object)[
                "id" => 3,
                "judul" => "Donasi Kasih Natal - Gereja Santo Paulus",
                "kategori" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "foto" => "donasi3.jpg"
            ],
            (object)[
                "id" => 4,
                "judul" => "Jumat Berkah - Masjid Agung",
                "kategori" => "Kegiatan keagamaan",
                "tanggal" => "Kamis, 30 Mei 2025",
                "foto" => "donasi4.jpg"
            ],
            (object)[
                "id" => 5,
                "judul" => "Hari Anak Nasional - Yayasan Sejahtera",
                "kategori" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "foto" => "donasi5.jpg"
            ],
        ]);
    }

    // ✅ HALAMAN LIST
    public function index(Request $request)
    {
        $donasiList = $this->getData();

        $search = $request->get('search');

        if ($search) {
            $donasiList = $donasiList->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->judul), strtolower($search)) ||
                       str_contains(strtolower($item->kategori), strtolower($search)) ||
                       str_contains(strtolower($item->tanggal), strtolower($search));
            })->values();
        }

        return view('bukti-donasi', [
            'donasi' => $donasiList,
            'search' => $search
        ]);
    }

public function bukti($id)
{
    $donasi = (object)[
        "id" => $id,
        "judul" => "Hari Anak Nasional - Panti Bunda Kasih",
        "deskripsi" => "Penyaluran donasi dilakukan kepada anak-anak panti asuhan",
        "tanggal" => "19 April 2024",
        "tujuan" => "Gerakan Peduli Anak",
        "jenis" => "Bahan Makanan (Beras, Minyak, Telur, dll)",
        "catatan" => "Donasi berupa bahan pangan",
        "status" => "Selesai",
        "galeri" => [
            "donasi1.jpg",
            "donasi2.jpg",
            "donasi3.jpg",
            "donasi4.jpg"
        ]
    ];

    return view('bukti-donasi-bukti', compact('donasi'));
}

public function show($id)
{
    $donasi = [
        1 => [
            'judul' => 'Hari Anak Nasional - Panti Bunda Kasih',
            'deskripsi' => 'Penyaluran donasi dilakukan kepada anak-anak panti asuhan',
            'foto_utama' => 'https://via.placeholder.com/800x400',
            'galeri' => []
        ]
    ];

    $data = $donasi[$id] ?? abort(404);

    return view('show', compact('data'));
}

}