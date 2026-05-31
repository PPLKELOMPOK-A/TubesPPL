<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\DonasiMakanan;
use App\Models\ReturDonasi;
=======
use App\Models\Donation; // Pastikan model Donation sudah ada
>>>>>>> 781dfe6035543f17c793d959b622e76a7875e23c
use Illuminate\Support\Facades\Auth;

class RiwayatDonationController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $userId = Auth::id();

        $donasiIds = DonasiMakanan::where('user_id', $userId)->pluck('id');

        $totalSelesai  = DonasiMakanan::where('user_id', $userId)->whereIn('status', ['selesai', 'disetujui'])->count();
        $totalDitolak  = DonasiMakanan::where('user_id', $userId)->where('status', 'ditolak')->count();
        $totalDiretur  = ReturDonasi::whereIn('id_donasi', $donasiIds)->count();
        $totalDiproses = DonasiMakanan::where('user_id', $userId)->whereNotIn('status', ['selesai', 'disetujui', 'ditolak'])->count();
        $totalSemua    = DonasiMakanan::where('user_id', $userId)->count() + $totalDiretur;

        $statusFilter = $request->get('status');
        $search = $request->get('search');

        if ($statusFilter == 'diretur') {
            $query = ReturDonasi::whereIn('id_donasi', $donasiIds);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_makanan', 'like', '%' . $search . '%')
                      ->orWhere('alasan', 'like', '%' . $search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            }

            $donations = $query->latest()->get()->map(function($item) {
                $item->_is_retur = true;
                return $item;
            });

        } else {
            $query = DonasiMakanan::where('user_id', $userId);

            if ($statusFilter == 'selesai') {
                $query->whereIn('status', ['selesai', 'disetujui']);
            } elseif ($statusFilter == 'ditolak') {
                $query->where('status', 'ditolak');
            } elseif ($statusFilter == 'diproses') {
                $query->whereNotIn('status', ['selesai', 'disetujui', 'ditolak']);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('kategori_makanan', 'like', '%' . $search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            }

            $donasi = $query->latest()->get();

            // Kalau tidak ada filter status, gabungkan dengan data retur
            if (!$statusFilter) {
                $returData = ReturDonasi::whereIn('id_donasi', $donasiIds)->latest()->get()->map(function($item) {
                    $item->_is_retur = true;
                    return $item;
                });
                $donations = $donasi->concat($returData);
            } else {
                $donations = $donasi;
            }
        }

        return view('riwayat-donasi', compact(
            'donations', 'totalSemua', 'totalSelesai', 'totalDiproses', 'totalDitolak', 'totalDiretur'
        ));
    }

    public function updateRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:255',
        ]);

        $donasi = DonasiMakanan::where('user_id', Auth::id())->findOrFail($id);
        $donasi->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
    }

    public function showBukti($id)
    {
        $donasiIds = DonasiMakanan::where('user_id', Auth::id())->pluck('id');
        $retur = ReturDonasi::whereIn('id_donasi', $donasiIds)->find($id);

        if ($retur) {
            return view('riwayat-lihat-bukti', ['donasi' => $retur, 'isRetur' => true]);
        }

        $donasi = DonasiMakanan::where('user_id', Auth::id())->findOrFail($id);
        return view('riwayat-lihat-bukti', ['donasi' => $donasi, 'isRetur' => false]);
    }
}
=======
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
       
>>>>>>> 781dfe6035543f17c793d959b622e76a7875e23c
