<?php

namespace App\Http\Controllers;

use App\Models\DonasiMakanan;
use App\Models\User; 
use App\Notifications\SistemNotifikasi; 
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
            'menunggu' => DonasiMakanan::whereIn('status', ['menunggu', 'pending', 'Pending'])->count(),
            'diproses' => DonasiMakanan::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        // Mengambil data dengan pagination (5 data per halaman)
        $donations = DonasiMakanan::whereIn('status', ['menunggu', 'pending', 'Pending'])
            ->latest()
            ->paginate(5);

        return view('admin.validasi_proses_donasi.index', compact('donations', 'stats'));
    }

    // ===============================
    // SETUJUI
    // ===============================
    // Perbaikan: Tambahkan Request agar sesuai format POST standar Laravel
    public function setujui(Request $request, $id) 
    {
        $donasi = DonasiMakanan::findOrFail($id);

        $statusAman = strtolower(trim($donasi->status ?? ''));

        if (!in_array($statusAman, ['menunggu', 'pending'])) {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'disetujui'
        ]);

        // FITUR NOTIFIKASI
        $donatur = User::find($donasi->user_id);
        if ($donatur) {
            $pesan = "Terima kasih! Donasi makanan Anda telah divalidasi dan siap dijemput.";
            $donatur->notify(new SistemNotifikasi("Donasi Disetujui", $pesan, "validasi"));
        }

        return redirect()->route('admin.validasi.disetujui')
            ->with('success', 'Donasi berhasil disetujui');
    }

    // ===============================
    // TOLAK
    // ===============================
    // Perbaikan Penting: Menerima Request untuk menangkap alasan dari Modal Pop-Up
    public function tolak(Request $request, $id)
    {
        // Validasi input dari textarea Modal
        $request->validate([
            'keterangan_tolak' => 'required|string'
        ]);

        $donasi = DonasiMakanan::findOrFail($id);

        $statusAman = strtolower(trim($donasi->status ?? ''));

        if (!in_array($statusAman, ['menunggu', 'pending'])) {
            return back()->with('error', 'Donasi sudah diproses');
        }

        // Simpan status ditolak beserta alasannya
        // Pastikan kolom database bernama 'keterangan_tolak' atau sesuaikan dengan skema tabelmu.
        // Jika nama kolommu 'alasan_penolakan', ganti kodenya jadi: 'alasan_penolakan' => $request->keterangan_tolak
        $donasi->update([
            'status' => 'ditolak',
            'keterangan_tolak' => $request->keterangan_tolak 
        ]);

        // FITUR NOTIFIKASI
        $donatur = User::find($donasi->user_id);
        if ($donatur) {
            $pesan = "Mohon maaf, donasi Anda belum memenuhi kriteria validasi dan terpaksa ditolak. Alasan: " . $request->keterangan_tolak;
            $donatur->notify(new SistemNotifikasi("Donasi Ditolak", $pesan, "validasi"));
        }

        return redirect()->route('admin.validasi.ditolak')
            ->with('success', 'Donasi berhasil ditolak');
    }

    // ===============================
    // RETURN (Kembalikan ke antrian)
    // ===============================
    public function returnDonasi($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);

        $statusAman = strtolower(trim($donasi->status ?? ''));

        if (in_array($statusAman, ['menunggu', 'pending'])) {
            return back()->with('info', 'Donasi sudah berada di antrian');
        }

        $donasi->update([
            'status' => 'menunggu'
        ]);

        return redirect()->route('admin.validasi.index')
            ->with('info', 'Donasi dikembalikan ke antrian');
    }

    // ===============================
    // DISETUJUI (Menampilkan Halaman Disetujui)
    // ===============================
    public function halamanDisetujui()
    {
        $stats = [
            'hari_ini' => DonasiMakanan::whereDate('created_at', Carbon::today())->count(),
            'menunggu' => DonasiMakanan::whereIn('status', ['menunggu', 'pending', 'Pending'])->count(),
            'diproses' => DonasiMakanan::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        $donations = DonasiMakanan::where('status', 'disetujui')
            ->latest()
            ->paginate(5);

        // Perbaikan: View diarahkan ke file disetujui.blade.php
        return view('admin.validasi_proses_donasi.disetujui', compact('donations', 'stats'));
    }

    // ===============================
    // DITOLAK (Menampilkan Halaman Ditolak)
    // ===============================
    public function halamanDitolak()
    {
        $stats = [
            'hari_ini' => DonasiMakanan::whereDate('created_at', Carbon::today())->count(),
            'menunggu' => DonasiMakanan::whereIn('status', ['menunggu', 'pending', 'Pending'])->count(),
            'diproses' => DonasiMakanan::whereIn('status', ['disetujui', 'ditolak'])->count(),
        ];

        $donations = DonasiMakanan::where('status', 'ditolak')
            ->latest()
            ->paginate(5);

        // Perbaikan: View diarahkan ke file ditolak.blade.php
        return view('admin.validasi_proses_donasi.ditolak', compact('donations', 'stats'));
    }
}