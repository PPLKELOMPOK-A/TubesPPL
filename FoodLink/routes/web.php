<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BuktiDonasiController;
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

    // ================== DASHBOARD USER ==================
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();

        $donations = Donation::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $totalDonations = Donation::where('user_id', $user->id)->count();
        $sentDonations = Donation::where('user_id', $user->id)
            ->where('status', 'terkirim')
            ->count();

        $inTransitDonations = Donation::where('user_id', $user->id)
            ->where('status', 'dalam_perjalanan')
            ->count();

        return view('dashboard', compact(
            'donations',
            'totalDonations',
            'sentDonations',
            'inTransitDonations'
        ));
    })->name('dashboard');

    // ================== LOGOUT ==================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ================== TRACKING ==================
    Route::get('/tracking', [DonationController::class, 'index'])
        ->name('donation.tracking');

    Route::get('/tracking-detail', function () {
        return view('trackingdetail');
    })->name('tracking.detail');

    // ================== BUKTI DONASI ==================
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])
        ->name('bukti-donasi.index');

    Route::get('/bukti-donasi/{id}', [BuktiDonasiController::class, 'show'])
        ->name('bukti-donasi.show');


    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {

        // ================== VALIDASI DONASI ==================
        Route::prefix('validasi-proses-donasi')->group(function () {

            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])
                ->name('validasi.index');

            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])
                ->name('validasi.setujui');

            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])
                ->name('validasi.tolak');

            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])
                ->name('validasi.return');

            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'disetujui'])
                ->name('validasi.disetujui');

            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'ditolak'])
                ->name('validasi.ditolak');
        });


        // ================== DASHBOARD ADMIN ==================
        Route::get('/dashboard', function () {

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


        // ================== DETAIL DONASI ==================
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


        // ================== EDIT DONASI ==================
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


        // ================== UPDATE DONASI ==================
        Route::post('/donasi/edit', function (Request $request) {

            $oldData = session('donasi_data', []);
            $fotoPath = $oldData['foto'] ?? null;

            if ($request->hasFile('foto')) {

                if ($fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                }

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

            Donation::updateOrCreate(
                ['id' => 1],
                $saveData
            );

            session([
                'donasi_data' => $saveData
            ]);

            return redirect()
                ->route('admin.donasi.detail')
                ->with('success', 'Donasi berhasil diperbarui secara permanen!');

        })->name('admin.donasi.update');


        // ================== RETUR DONASI ==================
        Route::prefix('retur-donasi')->group(function () {

            Route::get('/', [ReturDonasiController::class, 'index'])
                ->name('admin.retur.index');

            Route::post('/', [ReturDonasiController::class, 'store'])
                ->name('admin.retur.store');
        });


        // ================== PENUGASAN RELAWAN ==================
        Route::prefix('penugasan')->group(function () {

            Route::get('/', [PenugasanController::class, 'index'])
                ->name('penugasan.index');

            // ✅ TAMBAHAN HALAMAN FORM
            Route::get('/create', [PenugasanController::class, 'create'])
                ->name('penugasan.create');

            Route::post('/', [PenugasanController::class, 'store'])
                ->name('penugasan.store');

            Route::delete('/{id}', [PenugasanController::class, 'destroy'])
                ->name('penugasan.destroy');

            Route::get('/edit/{id}', [PenugasanController::class, 'edit'])
                ->name('penugasan.edit');

            Route::put('/{id}', [PenugasanController::class, 'update'])
                ->name('penugasan.update');
        });

    });
});