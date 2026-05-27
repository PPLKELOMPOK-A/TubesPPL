<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Models\Donation;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project
|--------------------------------------------------------------------------
*/

// --- HOMEPAGE ---
Route::get('/', function () {
    return view('welcome');
});

// --- GUEST AREA (Hanya untuk yang belum login) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [Controller::class, 'showLogin'])->name('login');
    Route::post('/login', [Controller::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- AUTH AREA (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    
    // --- AREA PUSAT NOTIFIKASI (Akses Admin & Donatur) ---
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
    Route::post('/notifikasi/mark-as-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    // Tambahkan ini di dalam group Route::middleware(['auth'])->group(function () { ... })
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-as-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markSingleAsRead'])->name('notifikasi.markSingleAsRead');
    // Di dalam Route::middleware(['auth'])->group(function () { ... })
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-as-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    // Mengubah status baca lalu dialihkan ke halaman detail
    Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'markSingleAsRead'])->name('notifikasi.markSingleAsRead');
    // Halaman khusus untuk menampilkan detail notifikasi
    Route::get('/notifikasi/{id}/detail', [NotifikasiController::class, 'show'])->name('notifikasi.show');

    // --- AREA USER BIASA ---
    Route::get('/dashboard', function (Request $request) {
        // Redirect jika admin nyasar ke dashboard user
        if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        
        // Ambil semua data kegiatan donasi yang tersedia (Tanpa filter user_id)
        $donations = Donation::orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // Detail Donasi untuk User
    Route::get('/donasi/detail/{id}', function ($id) {
        if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        $data = Donation::findOrFail($id);
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');

    // Fitur Pengajuan Donasi (Oleh User)
    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
    
    // Tracking & Bukti Donasi
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');


    // --- AREA KHUSUS ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $query = Donation::query();

            // Logika Pencarian (Search)
            if ($request->has('search') && $request->search != '') {
                $query->where('judul_donasi', 'like', '%' . $request->search . '%');
            }

            // Logika Filter Kategori
            if ($request->has('kategori') && !empty($request->kategori)) {
                $query->whereIn('kategori_penerima', $request->kategori);
            }

            $semuaDonasi = $query->orderBy('created_at', 'desc')->get();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('dashboard');

        // --- MANAJEMEN KEGIATAN DONASI (Menggunakan Controller) ---
        Route::get('/kegiatan/baru', [KegiatanDonasiController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan/simpan', [KegiatanDonasiController::class, 'store'])->name('kegiatan.store');

        // --- CRUD DONASI ---
        
        // Form Tambah Donasi
        Route::get('/donasi/tambah', function () {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            return view('admin.tambah-donasi');
        })->name('donasi.create');

        // Proses Simpan Donasi Baru
        Route::post('/donasi/tambah', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            Donation::create([
                'judul_donasi'      => $request->judul,
                'kategori_penerima' => $request->kategori,
                'tanggal_kegiatan'  => $request->tanggal,
                'foto_kegiatan'     => $fotoPath,
                'deskripsi'         => $request->deskripsi,
                'alamat_penyaluran' => $request->alamat
            ]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Donasi baru berhasil ditambahkan!');
        })->name('donasi.store');

        // Detail Donasi (Admin)
        Route::get('/donasi/detail/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $data = Donation::findOrFail($id);
            return view('admin.detail-donasi', compact('data'));
        })->name('donasi.detail');

        // Form Edit Donasi
        Route::get('/donasi/edit/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $data = Donation::findOrFail($id);
            return view('admin.edit-donasi', compact('data'));
        })->name('donasi.edit');

        // Proses Update Donasi
        Route::post('/donasi/edit/{id}', function (Request $request, $id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }

            $donasi = Donation::findOrFail($id);
            $fotoPath = $donasi->foto_kegiatan;

            if ($request->hasFile('foto')) {
                if ($fotoPath) { Storage::disk('public')->delete($fotoPath); }
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            }

            $donasi->update([
                'judul_donasi'      => $request->judul,
                'kategori_penerima' => $request->kategori,
                'tanggal_kegiatan'  => $request->tanggal,
                'foto_kegiatan'     => $fotoPath,
                'deskripsi'         => $request->deskripsi,
                'alamat_penyaluran' => $request->alamat
            ]);
            
            return redirect()->route('admin.donasi.detail', ['id' => $id])->with('success', 'Donasi berhasil diperbarui!');
        })->name('donasi.update');

        // Hapus Donasi
        Route::post('/donasi/hapus/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $donasi = Donation::findOrFail($id);
            
            // Hapus foto dari folder storage jika fotonya ada
            if ($donasi->foto_kegiatan) {
                Storage::disk('public')->delete($donasi->foto_kegiatan);
            }
            
            // Hapus datanya dari Database
            $donasi->delete();
            
            return redirect()->route('admin.dashboard')->with('success', 'Data Donasi berhasil dihapus!');
        })->name('donasi.delete');

        // --- FITUR ADMIN LAINNYA ---
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    }); // End Prefix Admin Group

    // GLOBAL LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});