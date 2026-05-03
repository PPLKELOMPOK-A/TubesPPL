<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Mengambil Statistik Ringkasan
        $stats = [
            'total_donations' => Donation::count(),
            'total_weight' => Donation::sum('weight') ?? 0, // Menggunakan 0 jika data kosong
            'active_volunteers' => DB::table('volunteers')->where('status', 'active')->count(),
            'completed_donations' => Donation::where('status', 'completed')->count(),
        ];

        // 2. Mengambil Data Tren Donasi (6 Bulan Terakhir)
        $monthlyData = Donation::select(
                DB::raw('count(id) as total'),
                DB::raw("DATE_FORMAT(created_at, '%M') as month")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('created_at', 'ASC')
            ->get();

        // 3. Mengirim data ke view report.blade.php
        return view('report', compact('stats', 'monthlyData'));
    }
}