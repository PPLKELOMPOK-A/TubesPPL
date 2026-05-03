<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
});

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        return view('dashboard');
    })->name('dashboard');

    // --- AREA KHUSUS ADMIN ---
    Route::prefix('admin')->group(function () {
        
        Route::get('/dashboard', function () {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/donasi/detail', function () {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
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
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
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

        // REVISI: Route Simpan (Satu URL dengan GET Edit tapi metodenya POST)
        Route::post('/donasi/edit', function (Request $request) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            $oldData = session('donasi_data', []);
            $fotoPath = $oldData['foto'] ?? null;

            if ($request->hasFile('foto')) {
                if ($fotoPath) { Storage::disk('public')->delete($fotoPath); }
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            // Simpan data baru
            session(['donasi_data' => [
                'judul'     => $request->judul,
                'kategori'  => $request->kategori,
                'tanggal'   => $request->tanggal,
                'foto'      => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat'    => $request->alamat,
            ]]);
            
            return redirect()->route('admin.donasi.detail')->with('success', 'Donasi berhasil diperbarui!');
        })->name('admin.donasi.update'); // Kita beri nama update agar lebih jelas
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});