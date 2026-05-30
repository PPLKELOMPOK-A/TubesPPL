<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatDonationController extends Controller
{
    public function index(Request $request)

{
    $status = $request->query('status');
    $query = Donation::where('user_id', Auth::id())->orderBy('created_at', 'desc');

    // Logik penapisan mengikut tab yang diklik
    if ($status === 'selesai') {
        $query->where('status', 'selesai');
    } elseif ($status === 'diproses') {
        $query->whereNull('status');
    } elseif ($status === 'ditolak') {
        $query->where('status', 'ditolak');
    } elseif ($status === 'diretur') {
        // AMBIL PERHATIAN DI SINI:
        // Jika tab 'diretur' diklik, tapis donasi yang ID-nya wujud dalam tabel retur_donasis
        $query->whereIn('id', function($q) {
            $q->select('id_donasi')->from('retur_donasis');
        });
    }

    $donations = $query->get();

    // --- Kod pengiraan count anda yang sebelum ini ---
    $totalSemua = Donation::where('user_id', Auth::id())->count();
    $totalSelesai = Donation::where('user_id', Auth::id())->where('status', 'selesai')->count();
    $totalDiproses = Donation::where('user_id', Auth::id())->whereNull('status')->count();
    $totalDitolak = Donation::where('user_id', Auth::id())->where('status', 'ditolak')->count();
    
    $totalDiretur = DB::table('retur_donasis')
        ->whereIn('id_donasi', Donation::where('user_id', Auth::id())->pluck('id'))
        ->count();

    return view('Riwayat Donasi.riwayat-donasi', compact(
        'donations', 'totalSemua', 'totalSelesai', 'totalDiproses', 'totalDitolak', 'totalDiretur'
    ));
}    

    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string'
        ]);

        $donation = Donation::findOrFail($id);
        
        $donation->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil dikirim!');
    }

    public function updateRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:255',
        ]);

        $donasi = Donation::findOrFail($id);

        $donasi->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
    }

    public function showBukti($id)
    {
        $donasi = Donation::findOrFail($id);
        return view('bukti-penyelesaian-donasi.bukti-donasi-bukti', compact('donasi'));
    }
}