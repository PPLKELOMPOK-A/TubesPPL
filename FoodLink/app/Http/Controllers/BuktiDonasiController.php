<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiMakanan;
use Illuminate\Support\Facades\Auth;

class BuktiDonasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data dasar: Milik user yang login DAN berstatus selesai/disetujui
        $query = DonasiMakanan::where('user_id', Auth::id())
                              ->whereIn('status', ['selesai', 'disetujui']);
        
        $search = $request->get('search');
        
        // 2. Bungkus query pencarian ke dalam Logical Grouping (Fungsi Khusus Kurung)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kategori_makanan', 'like', '%' . $search . '%')
                  ->orWhere('kategori_penerima', 'like', '%' . $search . '%');
            });
        }
        
        $donasi = $query->latest()->paginate(10);
        
        return view('bukti-donasi', [
            'donasi' => $donasi,
            'search' => $search
        ]);
    }

    // 3. Kita pakai satu fungsi detail saja yang solid & aman
    public function show($id)
    {
        // Memastikan hanya bisa membuka detail jika data itu miliknya sendiri
        $donasi = DonasiMakanan::where('user_id', Auth::id())
                              ->whereIn('status', ['selesai', 'disetujui'])
                              ->findOrFail($id);

        return view('bukti-donasi-bukti', compact('donasi'));
    }
}