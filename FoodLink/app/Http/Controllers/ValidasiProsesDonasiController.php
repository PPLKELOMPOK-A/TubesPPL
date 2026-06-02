<?php

namespace App\Http\Controllers;

use App\Models\DonasiMakanan;
use App\Models\User; // Tambahan untuk memanggil data User (Donatur)
use App\Notifications\SistemNotifikasi; // Tambahan untuk memanggil class Notifikasi
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
            // Ditambahkan 'Pending' kapital agar aman di berbagai jenis database
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
    public function setujui($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);

        // PENYELESAIAN: Ubah status dari database menjadi huruf kecil semua agar aman
        $statusAman = strtolower(trim($donasi->status ?? ''));

        if (!in_array($statusAman, ['menunggu', 'pending'])) {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'disetujui'
        ]);

        // ==========================================
        // FITUR NOTIFIKASI: Kirim ke Donatur
        // ==========================================
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
    public function tolak($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);

        // PENYELESAIAN: Ubah status dari database menjadi huruf kecil semua agar aman
        $statusAman = strtolower(trim($donasi->status ?? ''));

        if (!in_array($statusAman, ['menunggu', 'pending'])) {
            return back()->with('error', 'Donasi sudah diproses');
        }

        $donasi->update([
            'status' => 'ditolak'
        ]);

        // ==========================================
        // FITUR NOTIFIKASI: Kirim ke Donatur
        // ==========================================
        $donatur = User::find($donasi->user_id);
        if ($donatur) {
            $pesan = "Mohon maaf, donasi Anda belum memenuhi kriteria validasi dan terpaksa ditolak.";
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
    // DISETUJUI
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

        return view('admin.validasi_proses_donasi.index', compact('donations', 'stats'));
    }

    // ===============================
    // DITOLAK
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

        return view('admin.validasi_proses_donasi.index', compact('donations', 'stats'));
   }
}