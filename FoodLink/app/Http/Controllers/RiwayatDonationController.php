<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiMakanan; // Mempertahankan model DonasiMakanan yang benar
use Illuminate\Support\Facades\Auth;

class RiwayatDonationController extends Controller
{
    public function index(Request $request)
    {
        $userEmail = Auth::user()->email;

        // 1. Hitung Total untuk Masing-masing Tab Filter
        $totalSemua = DonasiMakanan::where('email', $userEmail)->count();
        $totalSelesai = DonasiMakanan::where('email', $userEmail)->whereIn('status', ['selesai', 'disetujui'])->count();
        $totalDitolak = DonasiMakanan::where('email', $userEmail)->where('status', 'ditolak')->count();
        $totalDiretur = DonasiMakanan::where('email', $userEmail)->where('status', 'diretur')->count();
        // Yang tidak selesai, ditolak, atau diretur masuk ke 'diproses'
        $totalDiproses = DonasiMakanan::where('email', $userEmail)
                            ->whereNotIn('status', ['selesai', 'disetujui', 'ditolak', 'diretur'])
                            ->count();

        // 2. Siapkan Query Utama
        $query = DonasiMakanan::where('email', $userEmail);

        // 3. Filter berdasarkan Tab (Status)
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status == 'selesai') {
                $query->whereIn('status', ['selesai', 'disetujui']);
            } elseif ($status == 'ditolak') {
                $query->where('status', 'ditolak');
            } elseif ($status == 'diretur') {
                $query->where('status', 'diretur');
            } elseif ($status == 'diproses') {
                $query->whereNotIn('status', ['selesai', 'disetujui', 'ditolak', 'diretur']);
            }
        }

        // 4. Filter berdasarkan Search Bar
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kategori_makanan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhereHas('kegiatanDonasi', function($q2) use ($search) {
                      $q2->where('judul_donasi', 'like', '%' . $search . '%');
                  });
            });
        }

        $donations = $query->latest()->get();

        // 5. Kirim semua variabel total ke View
        return view('riwayat-donasi', compact(
            'donations', 'totalSemua', 'totalSelesai', 'totalDiproses', 'totalDitolak', 'totalDiretur'
        ));
    }

    public function updateRating(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:255',
        ]);

        // 2. Cari data donasinya menggunakan model DonasiMakanan
        $donasi = DonasiMakanan::findOrFail($id);

        // 3. Update datanya
        $donasi->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
    }

    public function showBukti($id)
    {
        // Ambil data donasi berdasarkan ID
        $donasi = DonasiMakanan::findOrFail($id);

        // Panggil view 'riwayat-lihat-bukti'
        return view('riwayat-lihat-bukti', compact('donasi'));
    }
}