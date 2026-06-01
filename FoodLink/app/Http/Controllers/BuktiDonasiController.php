<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiBukti;
use Illuminate\Support\Facades\Auth;

class BuktiDonasiController extends Controller
{
    public function index(Request $request)
    {
        $query = DonasiBukti::where('user_id', Auth::id())
                            ->where('status', 'selesai');
        
        $search = $request->get('search');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%')
                  ->orWhere('tanggal', 'like', '%' . $search . '%');
            });
        }
        
        $donasi = $query->latest()->get();
        
        return view('bukti-donasi', [
            'donasi' => $donasi,
            'search' => $search
        ]);
    }

    public function showBukti($id)
    {
        $donasi = DonasiBukti::findOrFail($id);
        return view('bukti-donasi-bukti', compact('donasi'));
    }

    public function show($id)
    {
        $donation = DonasiBukti::findOrFail($id);
        return view('show', ['data' => $donation]);
    }
}