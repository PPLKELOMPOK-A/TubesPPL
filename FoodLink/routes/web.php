<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Models\Donation;
use App\Http\Controllers\DonationController;

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project (Tanpa check blok)
|--------------------------------------------------------------------------
*/

// --- Homepage ---
Route::get('/', function () {
    return view('welcome');
});

// --- Guest Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [Controller::class, 'showLogin'])->name('login');
    Route::post('/login', [Controller::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- Authenticated Routes ---
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        $user = $request->user(); // <-- tipe user jelas
        $donations = Donation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalDonations = $donations->count();
        $sentDonations = $donations->where('status', 'terkirim')->count();
        $inTransitDonations = $donations->where('status', 'dalam_perjalanan')->count();

        return view('dashboard', compact(
            'donations',
            'totalDonations',
            'sentDonations',
            'inTransitDonations'
        ));
    })->name('dashboard');

    
    // --- Logout ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- Bukti Donasi ---
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');

    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])
    ->name('bukti.donasi.detail');

    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');

    // --- Admin Routes ---
   Route::prefix('admin')->middleware('auth')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/donasi/detail', function () {
            $data = session('donasi_data', [
                'judul'     => 'Hari Anak Nasional - Panti Bunda Kasih',
                'kategori'  => 'Organisasi (Yayasan)',
                'tanggal'   => '2026-05-13',
                'foto'      => null,
                'deskripsi' => 'Tersedia 20 paket nasi kotak ayam bakar sisa acara syukuran siang ini...',
                'alamat'    => 'Jl. Bougenville Timur No. 22'
            ]);
            return view('admin.detail-donasi', compact('data'));
        })->name('admin.donasi.detail');

        Route::get('/donasi/edit', function () {
            $data = session('donasi_data', [
                'judul'     => 'Hari Anak Nasional - Panti Bunda Kasih',
                'kategori'  => 'Organisasi (Yayasan)',
                'tanggal'   => '2026-05-13',
                'foto'      => null,
                'deskripsi' => 'Tersedia 20 paket nasi kotak ayam bakar sisa acara syukuran siang ini...',
                'alamat'    => 'Jl. Bougenville Timur No. 22'
            ]);
            return view('admin.edit-donasi', compact('data'));
        })->name('admin.donasi.edit');

        Route::post('/donasi/edit', function (Request $request) {
            $oldData = session('donasi_data', []);
            $fotoPath = $oldData['foto'] ?? null;

            if ($request->hasFile('foto')) {
                if ($fotoPath) Storage::disk('public')->delete($fotoPath);
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            session(['donasi_data' => [
                'judul'     => $request->judul,
                'kategori'  => $request->kategori,
                'tanggal'   => $request->tanggal,
                'foto'      => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat'    => $request->alamat,
            ]]);

            return redirect()->route('admin.donasi.detail')->with('success', 'Donasi berhasil diperbarui!');
        })->name('admin.donasi.update');

        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('admin.retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('admin.retur.store');
    });

});