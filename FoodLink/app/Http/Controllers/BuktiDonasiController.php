<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuktiDonasiController extends Controller
{
    public function index(Request $request)
    {
        $donasiList = collect([
            [
                "id" => 1,
                "judul" => "Hari Anak Nasional - Panti Bunda Kasih",
                "organisasi" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "gambar" => "https://via.placeholder.com/72x54?text=Img"
            ],
            [
                "id" => 2,
                "judul" => "Program Makan Sehat - Yayasan Peduli Sesama",
                "organisasi" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "gambar" => "https://via.placeholder.com/72x54?text=Img"
            ],
            [
                "id" => 3,
                "judul" => "Donasi Kasih Natal - Gereja Santo Paulus",
                "organisasi" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "gambar" => "https://via.placeholder.com/72x54?text=Img"
            ],
            [
                "id" => 4,
                "judul" => "Jumat Berkah - Masjid Agung",
                "organisasi" => "Kegiatan keagamaan",
                "tanggal" => "Kamis, 30 Mei 2025",
                "gambar" => "https://via.placeholder.com/72x54?text=Img"
            ],
            [
                "id" => 5,
                "judul" => "Hari Anak Nasional - Yayasan Sejahtera",
                "organisasi" => "Organisasi (Yayasan)",
                "tanggal" => "Kamis, 30 Mei 2025",
                "gambar" => "https://via.placeholder.com/72x54?text=Img"
            ],
        ]);

        $search = $request->get('search');

        if ($search) {
            $donasiList = $donasiList->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['judul']), strtolower($search)) ||
                       str_contains(strtolower($item['organisasi']), strtolower($search)) ||
                       str_contains(strtolower($item['tanggal']), strtolower($search));
            })->values();
        }

        return view('bukti-donasi', compact('donasiList', 'search'));
        return view('detail-bukti-donasi', compact('data'));
    }
}