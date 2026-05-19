<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class ValidasiProsesDonasiController extends Controller
{
    // ===============================
    // HALAMAN MENUNGGU VALIDASI
    // ===============================
    public function index()
    {
        $donations = Donation::where('status', 'menunggu')
            ->latest()
            ->get();

        return view('validasi_proses_donasi.index', compact('donations'));
    }

    // ===============================
    // SETUJUI DONASI
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
    // TOLAK DONASI
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
            ->with('error', 'Donasi ditolak');
    }

    // ===============================
    // RETURN KE ANTRIAN
    // ===============================
    public function returnDonasi($id)
    {
        $donasi = Donation::findOrFail($id);

        $donasi->update([
            'status' => 'menunggu'
        ]);

        return redirect()->route('validasi.index')
            ->with('info', 'Donasi dikembalikan ke antrian');
    }

    // ===============================
    // HALAMAN DISETUJUI
    // ===============================
    public function disetujui()
    {
        $donations = Donation::where('status', 'disetujui')
            ->latest()
            ->get();

        return view('validasi_proses_donasi.disetujui', compact('donations'));
    }

    // ===============================
    // HALAMAN DITOLAK
    // ===============================
    public function ditolak()
    {
        $donations = Donation::where('status', 'ditolak')
            ->latest()
            ->get();

        return view('validasi_proses_donasi.ditolak', compact('donations'));
    }
}