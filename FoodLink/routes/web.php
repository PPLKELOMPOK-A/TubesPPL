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
    Route::get('/dashboard', function (Request $request) { // <-- PERUBAHAN: Tambah Request untuk Filter
        if (auth()->user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        
        $query = Donation::query();

        // Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Logika Filter Kategori
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->whereIn('kategori', $request->kategori);
        }

        // Menambahkan pengambilan data donasi agar bisa di-looping di dashboard user
        $donations = $query->get();
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
        Route::get('/dashboard', function (Request $request) { // <-- PERUBAHAN: Tambah Request untuk Filter
            if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
            
            $query = Donation::query();

            // Logika Pencarian (Search)
            if ($request->has('search') && $request->search != '') {
                $query->where('judul', 'like', '%' . $request->search . '%');
            }

            // Logika Filter Kategori
            if ($request->has('kategori') && !empty($request->kategori)) {
                $query->whereIn('kategori', $request->kategori);
            }

            // Mengambil semua data dari Database berdasarkan filter
            $semuaDonasi = $query->get();
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

    // HAPUS DONASI
    Route::post('/donasi/hapus/{id}', function ($id) {
        if (auth()->user()->role !== 'admin') { return redirect('/dashboard'); }
        
        $donasi = Donation::findOrFail($id);
        
        // Hapus foto dari folder storage jika fotonya ada
        if ($donasi->foto) {
            Storage::disk('public')->delete($donasi->foto);
        }
        
        // Hapus datanya dari Database
        $donasi->delete();
        
        return redirect()->route('admin.dashboard')->with('success', 'Data Donasi berhasil dihapus!');
    })->name('admin.donasi.delete');


    // ==========================================
    // --- ROUTE PROFIL USER ---
    // ==========================================
    Route::get('/profil', function () {
        // Mengarahkan ke file resources/views/profil.blade.php
        return view('profil');
    })->name('profil');

    // ROUTE HALAMAN EDIT PROFIL
    Route::get('/profil/edit', function () {
        // Mengarahkan ke file resources/views/edit-profil.blade.php
        return view('edit-profil'); 
    })->name('admin.profil.edit');

    // ROUTE PROSES UPDATE PROFIL KESELURUHAN
    Route::post('/profil/update', function (Request $request) {
        $user = auth()->user();

        // Cek jika ada foto yang diupload
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) { 
                Storage::disk('public')->delete($user->foto_profil); 
            }
            $user->foto_profil = $request->file('foto_profil')->store('profil', 'public');
        }

        // Simpan data text ke database
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nik = $request->nik;
        $user->telepon = $request->telepon;
        $user->lokasi = $request->lokasi;
        $user->alamat = $request->alamat;

        $user->save();

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
    })->name('admin.profil.update');
    // ==========================================

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});