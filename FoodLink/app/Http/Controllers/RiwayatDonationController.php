<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation; // Pastikan model Donation sudah ada
use Illuminate\Support\Facades\Auth;

class RiwayatDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::where('user_id', Auth::id());

        if ($request->search) {
            $query->where('judul', 'like', '%'.$request->search.'%');
        }

        $donations = $query->latest()->get();
        return view('riwayat-donation', compact('donations'));
    }

    public function storeRating(Request $request)
{
    // 1. Validasi data yang masuk
    $request->validate([
        'donation_id' => 'required',
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'nullable|string'
    ]);

    // 2. Cari data donasinya dan update kolom rating & komentar
    $donation = \App\Models\Donation::findOrFail($request->donation_id);
    
    if($donation) {
        $donation->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);
    }

    // 3. Kembali ke halaman sebelumnya dengan pesan sukses
    return redirect()->back()->with('success', 'Penilaian berhasil dikirim!');
}

public function updateRating(Request $request, $id)
{
    // 1. Validasi input
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'nullable|string|max:255',
    ]);

    // 2. Cari data donasinya
    $donasi = Donation::findOrFail($id);

    // 3. Update datanya
    $donasi->update([
        'rating' => $request->rating,
        'komentar' => $request->komentar,
    ]);

    // 4. Redirect kembali agar tidak loading terus
    return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
}
public function showBukti($id)
{
    $donasi = \App\Models\Donation::findOrFail($id);
    return view('bukti-donasi-bukti', compact('donasi'));
}
}
       