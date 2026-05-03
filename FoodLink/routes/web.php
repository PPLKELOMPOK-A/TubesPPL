<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Donation; // <-- TAMBAHAN: Memanggil Model Database Asli

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
    
    // --- AREA USER BIASA ---
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        // Menambahkan pengambilan data donasi agar bisa di-looping di dashboard user
        $donations = Donation::all();
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // --- ROUTE DETAIL DONASI UNTUK USER BIASA ---
    Route::get('/donasi/detail/{id}', function ($id) {
        if (auth()->user()->role === 'admin') { return redirect('/admin/dashboard'); }
        
        // Mengambil data dari Database berdasarkan ID
        $data = Donation::findOrFail($id);
        
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');


    // --- AREA KHUSUS ADMIN ---
    Route::prefix('admin')->group(function () {

        // DASHBOARD ADMIN
        Route::get('/dashboard', function () {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            // Mengambil semua data dari Database
            $semuaDonasi = Donation::all();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('admin.dashboard');

        // DETAIL DONASI
        Route::get('/donasi/detail/{id}', function ($id) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            $data = Donation::findOrFail($id);
            return view('admin.detail-donasi', compact('data'));
        })->name('admin.donasi.detail');

        // EDIT DONASI
        Route::get('/donasi/edit/{id}', function ($id) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            $data = Donation::findOrFail($id);
            return view('admin.edit-donasi', compact('data'));
        })->name('admin.donasi.edit');

        // UPDATE DONASI
        Route::post('/donasi/edit/{id}', function (Request $request, $id) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            // Mencari data yang akan di-update di Database
            $donasi = Donation::findOrFail($id);

            $fotoPath = $donasi->foto;

            if ($request->hasFile('foto')) {
                if ($fotoPath) { Storage::disk('public')->delete($fotoPath); }
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            // Menyimpan perubahan ke Database
            $donasi->update([
                'judul' => $request->judul,
                'kategori' => $request->kategori,
                'tanggal' => $request->tanggal,
                'foto' => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat
            ]);
            
            return redirect()->route('admin.donasi.detail', ['id' => $id])->with('success', 'Donasi berhasil diperbarui!');
        })->name('admin.donasi.update');
    });

    // FORM TAMBAH DONASI
        Route::get('/donasi/tambah', function () {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            return view('admin.tambah-donasi');
        })->name('admin.donasi.create');

        // PROSES SIMPAN DONASI KE DATABASE
        Route::post('/donasi/tambah', function (Request $request) {
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            // Syntax bawaan Laravel untuk Create Data
            Donation::create([
                'judul' => $request->judul,
                'kategori' => $request->kategori,
                'tanggal' => $request->tanggal,
                'foto' => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat
            ]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Donasi baru berhasil ditambahkan!');
        })->name('admin.donasi.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});