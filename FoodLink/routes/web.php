<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Donation;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('welcome'); });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        $donations = Donation::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // ==========================================
    // GRUP ROUTE ADMIN (Dengan Prefix 'admin.')
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $semuaDonasi = Donation::orderBy('created_at', 'desc')->get();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('dashboard');

        Route::get('/kegiatan/baru', [KegiatanDonasiController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan/simpan', [KegiatanDonasiController::class, 'store'])->name('kegiatan.store');

        // ===== FITUR VALIDASI PROSES DONASI =====
        Route::prefix('validasi-proses-donasi')->group(function () {
            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'halamanDisetujui'])->name('validasi.disetujui');
            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'halamanDitolak'])->name('validasi.ditolak');
            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])->name('validasi.setujui');
            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])->name('validasi.tolak');
            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])->name('validasi.return');
        });

        // ===== CRUD DONASI OLEH ADMIN =====
        Route::get('/donasi/tambah', function () { 
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            return view('admin.tambah-donasi'); 
        })->name('donasi.create');

        Route::post('/donasi/tambah', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : null;
            
            Donation::create([
                'judul' => $request->judul, 
                'kategori' => $request->kategori, 
                'tanggal' => $request->tanggal,
                'foto' => $fotoPath, 
                'deskripsi' => $request->deskripsi, 
                'alamat' => $request->alamat, 
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil ditambahkan!');
        })->name('donasi.store');

        Route::get('/donasi/detail/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $data = Donation::findOrFail($id);
            return view('admin.detail-donasi', compact('data'));
        })->name('donasi.detail');

        Route::get('/donasi/edit/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $data = Donation::findOrFail($id);
            return view('admin.edit-donasi', compact('data'));
        })->name('donasi.edit');

        // Route untuk Proses UPDATE Data Donasi
        Route::post('/donasi/update/{id}', function (Request $request, $id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $donasi = Donation::findOrFail($id);
            $fotoPath = $donasi->foto;

            if ($request->hasFile('foto')) {
                if ($fotoPath) { Storage::disk('public')->delete($fotoPath); }
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            $donasi->update([
                'judul' => $request->judul,
                'kategori' => $request->kategori,
                'tanggal' => $request->tanggal,
                'foto' => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat
            ]);
            
            return redirect()->route('admin.donasi.detail', ['id' => $id])->with('success', 'Donasi berhasil diperbarui!');
        })->name('donasi.update');

        // Route untuk Proses HAPUS Data Donasi
        Route::post('/donasi/hapus/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $donasi = Donation::findOrFail($id);
            
            if ($donasi->foto) {
                Storage::disk('public')->delete($donasi->foto);
            }
            
            $donasi->delete();
            
            return redirect()->route('admin.dashboard')->with('success', 'Data Donasi berhasil dihapus!');
        })->name('donasi.delete');

        // ==========================================
        // TUNTASKAN ERROR: ROUTE UNTUK REPORT
        // ==========================================
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });


    // ==========================================
    // --- ROUTE UNTUK USER BIASA ---
    // ==========================================
    
    // Rute untuk melihat detail donasi oleh user
    Route::get('/donasi/detail/{id}', function ($id) {
        $data = App\Models\Donation::findOrFail($id);
        // Pastikan 'detail-donasi' di bawah ini sesuai dengan nama file blade kamu ya!
        return view('detail-donasi', compact('data')); 
    })->name('user.donasi.detail');

    // Rute untuk form daftar donasi
    Route::get('/donasi/daftar/{id}', function ($id) {
        return "Halaman form pendaftaran donasi (Dalam Pengembangan)";
    })->name('user.donasi.create');
    
    // ==========================================
    // 👇 TAMBAHAN AGAR SIDEBAR TIDAK ERROR 👇
    // ==========================================
    Route::get('/riwayat-donasi', function () {
        return "Halaman Riwayat Donasi (Belum Dibuat Temanmu)";
    })->name('riwayat-donasi.index');


    // ==========================================
    // TUNTASKAN ERROR: ROUTE UNTUK KERJASAMA MITRA
    // ==========================================
    Route::get('/admin/kerjasama-mitra', function () {
        return "Halaman Kerjasama Mitra masih dalam pengembangan.";
    })->name('mitra.index');


    // ==========================================
    // --- ROUTE PROFIL USER KESELURUHAN ---
    // (Bisa diakses oleh Admin maupun User biasa)
    // ==========================================
    Route::get('/profil', function () {
        return view('profil');
    })->name('profil');

    Route::get('/profil/edit', function () {
        return view('edit-profil'); 
    })->name('profil.edit');

    Route::post('/profil/update', function (Request $request) {
        $user = Auth::user();

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) { 
                Storage::disk('public')->delete($user->foto_profil); 
            }
            $user->foto_profil = $request->file('foto_profil')->store('profil', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->nik = $request->nik;
        $user->telepon = $request->telepon;
        $user->lokasi = $request->lokasi;
        $user->alamat = $request->alamat;

        $user->save();

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
    })->name('profil.update');

    // ==========================================
    // ROUTE LOGOUT
    // ==========================================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});