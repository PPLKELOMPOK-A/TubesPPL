<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\RiwayatDonationController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\PenugasanController;
use App\Models\Donation;


/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project
|--------------------------------------------------------------------------
*/

// ================== HOMEPAGE ==================
Route::get('/', function () {
    return view('welcome');
});

// ================== GUEST ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [Controller::class, 'showLogin'])->name('login');
    Route::post('/login', [Controller::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ================== AUTH ==================
Route::middleware('auth')->group(function () {

    // DASHBOARD USER
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();
        $donations = Donation::where('user_id', $user->id)->latest()->paginate(10);
        $totalDonations = $donations->count();
        $sentDonations = $donations->where('status', 'terkirim')->count();
        $inTransitDonations = $donations->where('status', 'dalam_perjalanan')->count();

        return view('dashboard', compact('donations', 'totalDonations', 'sentDonations', 'inTransitDonations'));
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/tracking-detail', function() {
    return view('trackingdetail'); // sesuaikan nama file tanpa .blade.php
})->name('tracking.detail');

   // Bukti Donasi
   // Bukti Donasi (List)
Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])
    ->name('bukti-donasi.index');

// Detail Donasi
Route::get('/bukti-donasi/{id}', [BuktiDonasiController::class, 'show'])
    ->name('bukti-donasi.show');

Route::get('/bukti-donasi/{id}/bukti', [BuktiDonasiController::class, 'bukti'])
    ->name('bukti-donasi.bukti');

Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti-donasi.index');
Route::get('/bukti-donasi/{id}', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');

Route::get('/riwayat-donasi', [RiwayatDonationController::class, 'index'])->name('riwayat-donasi.index');
Route::post('/donation/rate/{id}', [RiwayatDonationController::class, 'storeRating'])->name('donation.rate');

Route::post('/riwayat-donation/rate', [RiwayatDonationController::class, 'storeRating'])->name('riwayat-donasi.rate');
Route::get('/tracking', [RiwayatDonationController::class, 'index'])->name('donation.tracking');

Route::post('/riwayat-donasi/rating/{id}', [RiwayatDonationController::class, 'updateRating'])->name('donasi.rating');

    // ================== ADMIN ==================
    Route::prefix('admin')->group(function () {

        // ===== VALIDASI DONASI =====
        Route::prefix('validasi-proses-donasi')->group(function () {
            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])->name('validasi.setujui');
            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])->name('validasi.tolak');
            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])->name('validasi.return');
            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'disetujui'])->name('validasi.disetujui');
            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'ditolak'])->name('validasi.ditolak');
        });

        // ===== DASHBOARD ADMIN (REVISI: Ditambah Variabel $donations dari DB) =====
        Route::get('/dashboard', function () {
            // Kita ambil data dari database supaya loop di dashboard.blade tidak error
            $donations = Donation::latest()->get(); 
            
            $donasiData = session('donasi_data', [
                'judul'     => 'Hari Anak Nasional - Panti Bunda Kasih',
                'kategori'  => 'Organisasi (Yayasan)',
                'tanggal'   => '2026-05-13',
                'foto'      => null,
                'deskripsi' => 'Tersedia 20 paket nasi kotak ayam bakar...',
                'alamat'    => 'Jl. Bougenville Timur No. 22'
            ]);

            return view('admin.dashboard', compact('donasiData', 'donations'));
        })->name('admin.dashboard');

        // ===== DETAIL DONASI (REVISI: Supaya bisa baca data tiap klik) =====
        Route::get('/donasi/detail', function (Request $request) {
            if ($request->has('judul')) {
                $data = [
                    'judul'     => $request->query('judul'),
                    'kategori'  => $request->query('org'),
                    'tanggal'   => $request->query('tgl'),
                    'deskripsi' => $request->query('desc'),
                    'alamat'    => $request->query('alamat'),
                    'foto'      => $request->query('img_raw'),
                ];
            } else {
                $data = session('donasi_data', []);
            }
            return view('admin.detail-donasi', compact('data'));
        })->name('admin.donasi.detail');

        // ===== EDIT DONASI =====
        Route::get('/donasi/edit', function () {
            $data = session('donasi_data', [
                'judul'     => 'Hari Anak Nasional - Panti Bunda Kasih',
                'kategori'  => 'Organisasi (Yayasan)',
                'tanggal'   => '2026-05-13',
                'foto'      => null,
                'deskripsi' => 'Tersedia 20 paket nasi kotak...',
                'alamat'    => 'Jl. Bougenville Timur No. 22'
            ]);
            return view('admin.edit-donasi', compact('data'));
        })->name('admin.donasi.edit');

        // UPDATE DONASI (REVISI: Simpan Permanen ke DATABASE)
        Route::post('/donasi/edit', function (Request $request) {
            $oldData = session('donasi_data', []);
            $fotoPath = $oldData['foto'] ?? null;

            if ($request->hasFile('foto')) {
                if ($fotoPath) Storage::disk('public')->delete($fotoPath);
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            $saveData = [
                'judul'     => $request->judul,
                'kategori'  => $request->kategori,
                'tanggal'   => $request->tanggal,
                'foto'      => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat'    => $request->alamat,
            ];

            // SIMPAN PERMANEN KE DATABASE (Update data ID 1 sebagai master)
            Donation::updateOrCreate(['id' => 1], $saveData);

            // Backup ke session
            session(['donasi_data' => $saveData]);

            return redirect()->route('admin.donasi.detail')->with('success', 'Donasi berhasil diperbarui secara permanen!');
        })->name('admin.donasi.update');

        // ===== LAINNYA =====
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('admin.retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('admin.retur.store');
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::post('/penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');
        Route::delete('/penugasan/{id}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');
        Route::get('/penugasan/edit/{id}', [PenugasanController::class, 'edit'])->name('penugasan.edit');
        Route::put('/penugasan/{id}', [PenugasanController::class, 'update'])->name('penugasan.update');

    });
});