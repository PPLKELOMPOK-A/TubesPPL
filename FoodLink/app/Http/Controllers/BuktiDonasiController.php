<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

class BuktiDonasiController extends Controller
{
    // ✅ HALAMAN LIST - Mengambil data dari database
    public function index(Request $request)
    {
        // Ambil data donasi dari database berdasarkan user yang login
        $query = Donation::where('user_id', Auth::id());
        
        $search = $request->get('search');
        
        // Filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%')
                  ->orWhere('tanggal', 'like', '%' . $search . '%');
            });
        }
        
        // Ambil data dan urutkan dari yang terbaru
        $donasi = $query->latest()->get();
        
        return view('bukti-penyelesaian-donasi.bukti-donasi', [
            'donasi' => $donasi,
            'search' => $search
        ]);
    }

    public function showBukti($id)
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

        return view('bukti-penyelesaian-donasi.bukti-donasi-bukti', compact('donasi'));
    }

    public function show($id)
    {
        $donation = Donation::findOrFail($id);
        return view('bukti-penyelesaian-donasi.show', ['data' => $donation]);
    }
}

