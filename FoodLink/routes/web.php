<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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
    
    // --- FUNGSI BANTUAN SIMULASI DATABASE ---
    // Dipindah ke sini agar BISA DIAKSES OLEH ADMIN MAUPUN USER
    $initDonasiDB = function() {
        if (!Cache::has('donasi_db')) {
            Cache::forever('donasi_db', [
                1 => ['id' => 1, 'judul' => 'Hari Anak Nasional - Panti Bunda Kasih', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-13', 'foto' => null, 'deskripsi' => 'Tersedia 20 paket nasi kotak ayam bakar sisa acara syukuran siang ini. Kondisi masih sangat baik, bersih, dan higienis. Lengkap dengan sayur urap dan sambal.', 'alamat' => 'Jl. Bougenville Timur No. 22'],
                2 => ['id' => 2, 'judul' => 'Program Makan Sehat - Yayasan Peduli Sesama', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Tersedia donasi sayur dan lauk pauk sehat bernutrisi tinggi untuk anak-anak.', 'alamat' => 'Jl. Melati No. 10'],
                3 => ['id' => 3, 'judul' => 'Donasi Kasih Natal - Gereja Santo Paulus', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Paket sembako dan makanan ringan siap konsumsi untuk menyambut perayaan.', 'alamat' => 'Jl. Gereja Lama No. 5'],
                4 => ['id' => 4, 'judul' => 'Jumat Berkah - Masjid Agung', 'kategori' => 'Kegiatan Keagamaan', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => '100 porsi nasi bungkus untuk dibagikan kepada masyarakat yang membutuhkan ba\'da jumat.', 'alamat' => 'Jl. Masjid Raya No. 1'],
                5 => ['id' => 5, 'judul' => 'Hari Anak Nasional - Yayasan Sejahtera', 'kategori' => 'Organisasi (Yayasan)', 'tanggal' => '2026-05-30', 'foto' => null, 'deskripsi' => 'Kue kering dan susu kotak utuh untuk anak-anak yayasan sejahtera.', 'alamat' => 'Jl. Kesejahteraan No. 99']
            ]);
        }
        return Cache::get('donasi_db');
    };

    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        return view('dashboard');
    })->name('dashboard');

    // --- ROUTE DETAIL DONASI UNTUK USER BIASA ---
    // Diperbaiki letaknya agar aman di dalam middleware auth
    Route::get('/donasi/detail/{id}', function ($id) use ($initDonasiDB) {
        if (auth()->user()->role === 'admin') { return redirect('/admin/dashboard'); }
        
        // Memanggil fungsi agar data cache dipastikan ada
        $db = $initDonasiDB();
        if (!isset($db[$id])) abort(404);
        
        $data = $db[$id];
        
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');


    // --- AREA KHUSUS ADMIN ---
    Route::prefix('admin')->group(function () use ($initDonasiDB) {

        // DASHBOARD ADMIN
        Route::get('/dashboard', function () use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            $semuaDonasi = $initDonasiDB();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('admin.dashboard');

        // DETAIL DONASI
        Route::get('/donasi/detail/{id}', function ($id) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            $db = $initDonasiDB();
            if (!isset($db[$id])) abort(404);
            
            $data = $db[$id];
            return view('admin.detail-donasi', compact('data'));
        })->name('admin.donasi.detail');

        // EDIT DONASI
        Route::get('/donasi/edit/{id}', function ($id) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            $db = $initDonasiDB();
            if (!isset($db[$id])) abort(404);
            
            $data = $db[$id];
            return view('admin.edit-donasi', compact('data'));
        })->name('admin.donasi.edit');

        // UPDATE DONASI
        Route::post('/donasi/edit/{id}', function (Request $request, $id) use ($initDonasiDB) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            $db = $initDonasiDB();
            if (!isset($db[$id])) abort(404);

            $fotoPath = $db[$id]['foto'];

            if ($request->hasFile('foto')) {
                if ($fotoPath) { Storage::disk('public')->delete($fotoPath); }
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            $db[$id]['judul'] = $request->judul;
            $db[$id]['kategori'] = $request->kategori;
            $db[$id]['tanggal'] = $request->tanggal;
            $db[$id]['foto'] = $fotoPath;
            $db[$id]['deskripsi'] = $request->deskripsi;
            $db[$id]['alamat'] = $request->alamat;

            Cache::forever('donasi_db', $db);
            
            return redirect()->route('admin.donasi.detail', ['id' => $id])->with('success', 'Donasi berhasil diperbarui!');
        })->name('admin.donasi.update');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});