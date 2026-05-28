<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiMakanan;
use Illuminate\Support\Facades\Auth;

class RiwayatDonationController extends Controller
{
    public function index(Request $request)
    {
        // 1. KUNCI DATA: Pastikan hanya mengambil donasi milik user yang sedang login berdasarkan email!
        $query = DonasiMakanan::where('email', Auth::user()->email);

        // 2. LOGIKA SEARCH AMAN: Harus dibungkus agar tidak menabrak kunci email di atas
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kategori_makanan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  // Mencari berdasarkan judul dari relasi tabel kegiatan_donasis
                  ->orWhereHas('kegiatanDonasi', function($q2) use ($search) {
                      $q2->where('judul_donasi', 'like', '%' . $search . '%');
                  });
            });
        }

        $donations = $query->latest()->get();

        // 3. PASTIKAN RETURN KE VIEW RIWAYAT, BUKAN DASHBOARD
        return view('riwayat-donation', compact('donations'));
    }
    public function showBukti($id)
    {
    // Ambil data donasi berdasarkan ID (Sesuaikan nama modelnya, misal: Donasi)
    $donasi = \App\Models\DonasiMakanan::findOrFail($id);

    // Kembalikan ke view detail bukti yang sudah kita buat sebelumnya
    // Sesuaikan 'nama_folder.detail-bukti' dengan lokasi file blade Anda
    return view('riwayat-donasi.detail-bukti', compact('donasi'));
    }
}