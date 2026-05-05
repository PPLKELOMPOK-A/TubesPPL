<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Carbon\Carbon; // Ditambahkan untuk fungsi manipulasi tanggal

class ValidasiProsesDonasiController extends Controller
{
    // ===============================
    // MENUNGGU VALIDASI
    // ===============================
    public function index()
    {
        // Menghitung statistik untuk ditampilkan di Card Atas
        $stats = [
            'masuk_hari_ini' => Donation::whereDate('created_at', Carbon::today())->count(),
            'perlu_validasi' => Donation::where('status', 'menunggu')->count(),
            'sudah_diproses' => Donation::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        // Mengambil data dengan pagination (5 data per halaman) agar desain figma berfungsi
        $donations = Donation::where('status', 'menunggu')
            ->latest()
            ->paginate(5);

        return view('validasi_proses_donasi.index', compact('donations', 'stats'));
    }

    // ===============================
    // SETUJUI
    // ===============================
    public function setujui($id)
    {
        $donasi = Donation::findOrFail($id);

        if ($donasi->status !== 'menunggu') {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'disetujui'
        ]);

        return redirect()->route('validasi.disetujui')
            ->with('success', 'Donasi berhasil disetujui');
    }

    // ===============================
    // TOLAK
    // ===============================
    public function tolak($id)
    {
        $donasi = Donation::findOrFail($id);

        if ($donasi->status !== 'menunggu') {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'ditolak'
        ]);

        return redirect()->route('validasi.ditolak')
            ->with('success', 'Donasi berhasil ditolak');
    }

    // ===============================
    // RETURN
    // ===============================
    public function returnDonasi($id)
    {
        $donasi = Donation::findOrFail($id);

        if ($donasi->status === 'menunggu') {
            return back()->with('info', 'Donasi sudah berada di antrian');
        }

        $donasi->update([
            'status' => 'menunggu'
        ]);

        return redirect()->route('validasi.index')
            ->with('info', 'Donasi dikembalikan ke antrian');
    }

    // ===============================
    // DISETUJUI
    // ===============================
    public function disetujui()
    {
        // Menggunakan paginate juga agar seragam dengan halaman index
        $donations = Donation::where('status', 'disetujui')
            ->latest()
            ->paginate(5);

        return view('validasi_proses_donasi.disetujui', compact('donations'));
    }

    // ===============================
    // DITOLAK
    // ===============================
    public function ditolak()
    {
        // Menggunakan paginate juga agar seragam dengan halaman index
        $donations = Donation::where('status', 'ditolak')
            ->latest()
            ->paginate(5);

        return view('validasi_proses_donasi.ditolak', compact('donations'));
    }
}