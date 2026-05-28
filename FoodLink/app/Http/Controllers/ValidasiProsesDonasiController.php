<?php

namespace App\Http\Controllers;

use App\Models\DonasiMakanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ValidasiProsesDonasiController extends Controller
{
    // ===============================
    // MENUNGGU VALIDASI
    // ===============================
    public function index()
    {
        // Menghitung statistik untuk ditampilkan di Card Atas
        $stats = [
            'hari_ini' => DonasiMakanan::whereDate('created_at', Carbon::today())->count(),
            'menunggu' => DonasiMakanan::where('status', 'menunggu')->count(),
            'diproses' => DonasiMakanan::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        // Mengambil data dengan pagination (5 data per halaman)
        $donations = DonasiMakanan::where('status', 'menunggu')
            ->latest()
            ->paginate(5);

        return view('admin.validasi_proses_donasi.index', compact('donations', 'stats'));
    }

    // ===============================
    // SETUJUI
    // ===============================
    public function setujui($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);

        if ($donasi->status !== 'menunggu') {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'disetujui'
        ]);

        return redirect()->route('admin.validasi.disetujui')
            ->with('success', 'Donasi berhasil disetujui');
    }

    // ===============================
    // TOLAK
    // ===============================
    public function tolak($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);

        if ($donasi->status !== 'menunggu') {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'ditolak'
        ]);

        return redirect()->route('admin.validasi.ditolak')
            ->with('success', 'Donasi berhasil ditolak');
    }

    // ===============================
    // RETURN
    // ===============================
    public function returnDonasi($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);

        if ($donasi->status === 'menunggu') {
            return back()->with('info', 'Donasi sudah berada di antrian');
        }

        $donasi->update([
            'status' => 'menunggu'
        ]);

        return redirect()->route('admin.validasi.index')
            ->with('info', 'Donasi dikembalikan ke antrian');
    }

    // ===============================
    // DISETUJUI
    // ===============================
    public function halamanDisetujui()
    {
        // Wajib mengirim $stats agar card atas tidak error
        $stats = [
            'hari_ini' => DonasiMakanan::whereDate('created_at', Carbon::today())->count(),
            'menunggu' => DonasiMakanan::where('status', 'menunggu')->count(),
            'diproses' => DonasiMakanan::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        $donations = DonasiMakanan::where('status', 'disetujui')
            ->latest()
            ->paginate(5);

        // Kita arahkan ke index.blade.php yang sama, karena UI-nya ada di situ
        return view('admin.validasi_proses_donasi.index', compact('donations', 'stats'));
    }

    // ===============================
    // DITOLAK
    // ===============================
    public function halamanDitolak()
    {
        // Wajib mengirim $stats agar card atas tidak error
        $stats = [
            'hari_ini' => DonasiMakanan::whereDate('created_at', Carbon::today())->count(),
            'menunggu' => DonasiMakanan::where('status', 'menunggu')->count(),
            'diproses' => DonasiMakanan::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        $donations = DonasiMakanan::where('status', 'ditolak')
            ->latest()
            ->paginate(5);

        // Kita arahkan ke index.blade.php yang sama, karena UI-nya ada di situ
        return view('admin.validasi_proses_donasi.index', compact('donations', 'stats'));
    }
}