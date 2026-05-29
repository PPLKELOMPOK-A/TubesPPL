<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Donation;

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [Controller::class, 'showLogin'])->name('login');
    Route::post('/login', [Controller::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Rute Lupa Password
    Route::get('/forgot-password', function () {
        return view('auth.lupa-password'); 
    })->name('password.request');

    // Rute proses cek email
    Route::post('/forgot-password/check', [AuthController::class, 'checkEmail'])->name('password.check');
    
    // RUTE EDIT PASSWORD (Dapat diakses Guest untuk reset)
    Route::get('/edit-password', function () {
        return view('auth.edit-password');
    })->name('profil.edit-password');
});

// Rute Update Password tetap di luar agar bisa diakses oleh GUEST dan AUTH
Route::post('/profil/update-password', [AuthController::class, 'updatePassword'])->name('profil.update-password');

Route::middleware(['auth'])->group(function () {
    
    // --- DASHBOARD USER ---
    Route::get('/dashboard', function (Request $request) {
        if (auth()->user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        
        $query = Donation::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->has('kategori') && is_array($request->kategori)) {
            $query->whereIn('kategori', $request->kategori);
        }

        $donations = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // --- AREA KHUSUS ADMIN ---
    Route::prefix('admin')->group(function () {
        $initDonasiDB = function() {
            if (!Cache::has('donasi_db')) {
                Cache::forever('donasi_db', [
                    1 => ['id' => 1, 'judul' => 'Hari Anak Nasional - Panti Bunda Kasih', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-13', 'foto' => null, 'deskripsi' => 'Deskripsi 1...', 'alamat' => 'Jl. Bougenville Timur No. 22'],
                    2 => ['id' => 2, 'judul' => 'Program Makan Sehat - Yayasan Peduli Sesama', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Deskripsi 2...', 'alamat' => 'Jl. Melati No. 10'],
                    3 => ['id' => 3, 'judul' => 'Donasi Kasih Natal - Gereja Santo Paulus', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Deskripsi 3...', 'alamat' => 'Jl. Gereja Lama No. 5'],
                    4 => ['id' => 4, 'judul' => 'Jumat Berkah - Masjid Agung', 'kategori' => 'Kegiatan Keagamaan', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Deskripsi 4...', 'alamat' => 'Jl. Masjid Raya No. 1'],
                    5 => ['id' => 5, 'judul' => 'Hari Anak Nasional - Yayasan Sejahtera', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Deskripsi 5...', 'alamat' => 'Jl. Kesejahteraan No. 99']
                ]);
            }
            return Cache::get('donasi_db');
        };

        Route::get('/dashboard', function (Request $request) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            $db = $initDonasiDB();
            $semuaDonasi = collect($db);
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('admin.dashboard');

        Route::get('/donasi/detail/{id}', function ($id) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            $db = $initDonasiDB();
            if (!isset($db[$id])) abort(404);
            $data = $db[$id];
            return view('admin.detail-donasi', compact('data'));
        })->name('admin.donasi.detail');

        // Rute edit diperbaiki namanya menjadi admin.donasi.edit
        Route::get('/donasi/edit/{id}', function ($id) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            $db = $initDonasiDB();
            if (!isset($db[$id])) abort(404);
            $data = $db[$id];
            return view('admin.edit-donasi', compact('data'));
        })->name('admin.donasi.edit');

        Route::post('/donasi/edit/{id}', function (Request $request, $id) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            $db = $initDonasiDB();
            if (!isset($db[$id])) abort(404);
            $db[$id]['judul'] = $request->judul;
            $db[$id]['kategori'] = $request->kategori;
            $db[$id]['tanggal'] = $request->tanggal;
            $db[$id]['deskripsi'] = $request->deskripsi;
            $db[$id]['alamat'] = $request->alamat;
            Cache::forever('donasi_db', $db);
            return redirect()->route('admin.donasi.detail', ['id' => $id])->with('success', 'Donasi berhasil diperbarui!');
        })->name('admin.donasi.update');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});