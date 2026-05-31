<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonasiMakanan;
use App\Models\ReturDonasi;
use Illuminate\Support\Facades\Auth;

class RiwayatDonationController extends Controller
{
    public function index(Request $request)
    {
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