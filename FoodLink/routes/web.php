<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Donation;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project
|--------------------------------------------------------------------------
*/

// ================== HOME & GUEST ==================
Route::get('/', function () { return view('welcome'); });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ================== AUTH (Harus Login) ==================
Route::middleware('auth')->group(function () {

    // --- DASHBOARD USER BIASA ---
    Route::get('/dashboard', function (Request $request) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        $donations = Donation::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // ================== ADMIN AREA ==================
    Route::prefix('admin')->name('admin.')->group(function () {

        // DASHBOARD ADMIN
        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $semuaDonasi = Donation::orderBy('created_at', 'desc')->get();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('dashboard');

        // ===== CRUD DONASI ADMIN =====
        
        // PERBAIKAN: Nama disamakan jadi 'donasi.create' agar sesuai tombol di Blade
        Route::get('/donasi/tambah', function () { 
            return view('admin.tambah-donasi'); 
        })->name('donasi.create');

        // Proses Simpan Donasi
        Route::post('/donasi/tambah', function (Request $request) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : null;
            Donation::create([
                'judul' => $request->judul,
                'kategori' => $request->kategori,
                'tanggal' => $request->tanggal,
                'foto' => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'user_id' => Auth::id()
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil ditambahkan!');
        })->name('donasi.store');

        // Detail Donasi
        Route::get('/donasi/detail/{id}', function ($id) {
            $data = Donation::findOrFail($id);
            return view('admin.detail-donasi', compact('data'));
        })->name('donasi.detail');

        // Edit Donasi
        Route::get('/donasi/edit/{id}', function ($id) {
            $data = Donation::findOrFail($id);
            return view('admin.edit-donasi', compact('data'));
        })->name('donasi.edit');

        // Hapus Donasi
        Route::post('/donasi/hapus/{id}', function ($id) {
            $donasi = Donation::findOrFail($id);
            if ($donasi->foto) { Storage::disk('public')->delete($donasi->foto); }
            $donasi->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
        })->name('donasi.delete');

        // MODUL ADMIN LAINNYA
        Route::get('/validasi-proses-donasi', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::get('/kerjasama-mitra', [DonationController::class, 'index'])->name('mitra.index');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});