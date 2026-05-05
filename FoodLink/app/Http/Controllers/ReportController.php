<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Inisiasi Query Dasar
        $query = Laporan::query();

        // JIKA ADA FILTER RENTANG WAKTU
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // --- 1. KALKULASI KPI (Menggunakan clone agar query utama tidak tertimpa) ---
        $totalBerhasil = (clone $query)->where('status', 'selesai')->count();
        $penerimaManfaat = (clone $query)->where('status', 'selesai')->count(); 
        
        $totalDonasi = (clone $query)->count();
        $totalDiretur = (clone $query)->where('status', 'diretur')->count();
        $persentaseRetur = $totalDonasi > 0 ? round(($totalDiretur / $totalDonasi) * 100, 1) : 0;
        
        $penggunaAktif = (clone $query)->distinct('nama_donatur')->count('nama_donatur');

        // --- 2. DATA GRAFIK (6 BULAN) ---
        // Grafik garis biasanya tetap dibuat statis 6 bulan ke belakang dari tanggal hari ini 
        // agar gelombangnya tetap terlihat estetik
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::today()->startOfMonth()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('M');
            $jumlahSelesai = Laporan::where('status', 'selesai')
                                   ->whereMonth('created_at', $bulan->month)
                                   ->whereYear('created_at', $bulan->year)
                                   ->count();
            $chartData[] = $jumlahSelesai;
        }

        // --- 3. DATA GRAFIK SEGMENTASI ---
        $segmentasiRaw = (clone $query)->select('kategori_penerima', DB::raw('count(*) as total'))
                                ->where('status', 'selesai')
                                ->groupBy('kategori_penerima')
                                ->orderByDesc('total')
                                ->get();
                                
        $totalSegmentasi = $segmentasiRaw->sum('total');
        
        $segmentasi = $segmentasiRaw->map(function($item) use ($totalSegmentasi) {
            return [
                'kategori' => $item->kategori_penerima,
                'total' => $item->total,
                'persentase' => $totalSegmentasi > 0 ? round(($item->total / $totalSegmentasi) * 100) : 0
            ];
        });

        // --- 4. LOG PENYALURAN TERBARU ---
        $logPenyaluran = (clone $query)->orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.laporan', compact(
            'totalBerhasil', 
            'penerimaManfaat', 
            'persentaseRetur', 
            'penggunaAktif',
            'chartLabels',
            'chartData',
            'segmentasi',
            'logPenyaluran'
        ));
    }
}