<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiMakanan;
use Illuminate\Support\Facades\Auth;

class BuktiDonasiController extends Controller
{
    public function index(Request $request)
    {
        $query = DonasiMakanan::where('user_id', Auth::id())
                              ->whereIn('status', ['selesai', 'disetujui']);
        
        $search = $request->get('search');
        
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

    public function showBukti($id)
    {
        $donasi = DonasiMakanan::where('user_id', Auth::id())->findOrFail($id);
        return view('bukti-donasi-bukti', compact('donasi'));
    }

    public function show($id)
    {
        $donasi = DonasiMakanan::where('user_id', Auth::id())->findOrFail($id);
        return view('bukti-donasi-bukti', compact('donasi'));
    }
}