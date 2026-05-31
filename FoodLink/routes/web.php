<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

// Models
use App\Models\Donation;
use App\Models\Chat;
use App\Models\Komunitas;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; // <-- PASTIKAN INI SUDAH DI-IMPORT
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;
use App\Http\Controllers\RiwayatDonationController;
use App\Http\Controllers\TipsController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('welcome'); });

// ======================
// GUEST ROUTES
// ======================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', function () {
        return view('auth.lupa-password');
    })->name('password.request');

    Route::post('/forgot-password/check', [AuthController::class, 'checkEmail'])->name('password.check');

    Route::get('/edit-password', function () {
        return view('auth.edit-password');
    })->name('profil.edit-password');
});

// ================== AUTH ==================
Route::middleware('auth')->group(function () {

    // ================== USER ==================
    Route::get('/dashboard', function (Request $request) {

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $query = Donation::query();

        if ($request->search) {
            $query->where('judul_donasi', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori) {
            $query->whereIn('kategori_penerima', $request->kategori);
        }

        $donations = $query->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $totalDonations = Donation::where('user_id', Auth::id())->count();
        $sentDonations = Donation::where('user_id', Auth::id())->where('status', 'terkirim')->count();
        $inTransitDonations = Donation::where('user_id', Auth::id())->where('status', 'dalam_perjalanan')->count();

        return view('dashboard', compact(
            'donations',
            'totalDonations',
            'sentDonations',
            'inTransitDonations'
        ));
    })->name('dashboard');

    // ================== USER DONASI ==================
    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
    
    // ===== FITUR TRACKING & BUKTI DONASI =====
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking'); 
    Route::get('/tracking/{id}', function ($id) { 
        return view('tracking.trackingdetail', [
            'donation' => Donation::findOrFail($id)
        ]);
    })->name('tracking.detail');

    Route::get('/donasi/detail/{id}', function ($id) {
        $data = Donation::findOrFail($id);
        return view('detail-donasi-user', compact('data'));
    })->name('user.donasi.detail');

    // ================== BUKTI DONASI ==================
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti-donasi.index');
    Route::get('/bukti-donasi/{id}', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');

    // ================== ADMIN ==================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/donasi/detail/{id}', [AdminController::class, 'detailDonasi'])->name('admin.donasi.detail');
        // PERBAIKAN DASHBOARD ADMIN: Diarahkan ke AdminController agar fitur search, filter, dan variabel $semuaDonasi sinkron
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // VALIDASI DONASI
        Route::prefix('validasi-proses-donasi')->group(function () {
            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])->name('validasi.setujui');
            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])->name('validasi.tolak');
            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])->name('validasi.return');
            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'disetujui'])->name('validasi.disetujui');
            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'ditolak'])->name('validasi.ditolak');
        });

        // RETUR
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');

       // PENUGASAN RELAWAN (Halaman Utama)
    Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');

    // TAMBAH PENUGASAN (Arahkan ke Controller, jangan langsung closure view)
    Route::get('/penugasan/create', [PenugasanController::class, 'create'])->name('penugasan.create');

    // SIMPAN PENUGASAN
    Route::post('/penugasan/store', [PenugasanController::class, 'store'])->name('penugasan.store');

    // EDIT PENUGASAN 
    Route::get('/penugasan/{id}/edit', [PenugasanController::class, 'edit'])->name('penugasan.edit');

    // UPDATE PENUGASAN
    Route::put('/penugasan/{id}', [PenugasanController::class, 'update'])->name('penugasan.update');

    // HAPUS PENUGASAN
    Route::delete('/penugasan/{id}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');
    
        // REPORT
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});