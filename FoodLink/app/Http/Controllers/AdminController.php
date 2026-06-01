<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Ditambahkan untuk menghilangkan garis merah VS Code
use App\Models\Donation; // KORREKSI: Sesuaikan 'Donation' dengan nama Model Donasi di aplikasi kamu (misal: Donasi)

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // 1. Validasi Akses Admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // 2. Query data dari database
        $query = Donation::query(); // Ganti 'Donation' jika nama model kamu berbeda

        // Fitur Pencarian (Search) berdasarkan judul donasi
        if ($request->has('search') && $request->search != '') {
            $query->where('judul_donasi', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter berdasarkan Kategori Penerima
        if ($request->has('kategori')) {
            $query->whereIn('kategori_penerima', $request->kategori);
        }

        // Ambil data terbaru (Urutkan dari yang paling baru dibuat)
        // Di view kamu ada struktur pagination manual, jika ingin aktif otomatis dari Laravel bisa gunakan ->paginate(5);
        $semuaDonasi = $query->latest()->get(); 

        // 3. Melempar data ke view dashboardAdmin
        return view('admin.dashboardAdmin', compact('semuaDonasi'));
    }
}