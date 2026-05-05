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
| Web Routes
|--------------------------------------------------------------------------
*/

// ================== HOME ==================
Route::get('/', function () {
    return view('welcome');
});

// ================== GUEST ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
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

    Route::get('/donasi/detail/{id}', function ($id) {
        $data = Donation::findOrFail($id);
        return view('detail-donasi-user', compact('data'));
    })->name('user.donasi.detail');

    // ================== TRACKING ==================
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');
    Route::get('/tracking-detail', fn() => view('trackingdetail'))->name('tracking.detail');

    // ================== BUKTI DONASI ==================
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti-donasi.index');
    Route::get('/bukti-donasi/{id}', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');

    // ================== ADMIN ==================
    Route::prefix('admin')->name('admin.')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', function () {

            if (Auth::user()->role !== 'admin') {
                return redirect()->route('dashboard');
            }

            $donations = Donation::latest()->get();

            return view('admin.dashboardAdmin', compact('donations'));

        })->name('dashboard');

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

        // PENUGASAN
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::post('/penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');

        // REPORT
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});