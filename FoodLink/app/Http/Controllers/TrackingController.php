<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation; // Sesuaikan dengan nama Model kamu

class TrackingController extends Controller
{
    public function index()
    {
        // 1. Ambil data yang sudah di ACC (Asumsi kolom 'status_acc' = 'disetujui')
        $query = Donation::where('status_acc', 'disetujui');

        // 2. Hitung Statistik (Gunakan clone agar query utama tidak terpengaruh)
        $total = $query->count();
        $terkirim = (clone $query)->where('status_pengiriman', 'terkirim')->count();
        $dalamPerjalanan = (clone $query)->where('status_pengiriman', 'dalam_perjalanan')->count();

        // 3. Ambil data untuk list card (Pagination 2 per halaman seperti contoh)
        $donations = $query->paginate(2);

        // 4. Kirim data ke View
        return view('tracking.index', compact('donations', 'total', 'terkirim', 'dalamPerjalanan'));
    }
}