<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

// Models
use App\Models\Donation;
use App\Models\Chat;
use App\Models\Komunitas;
use App\Models\KegiatanDonasi; // <-- Tambahkan ini

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\RiwayatDonationController;
use App\Http\Controllers\TipsController; 

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('welcome'); });

// ======================
// GUEST ROUTES
// ======================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', function () {
        return view('auth.lupa-password');
    })->name('password.request');

    Route::post('/forgot-password/check', [AuthController::class, 'checkEmail'])->name('password.check');

    Route::get('/edit-password', function () {
        return view('auth.edit-password');
    })->name('profil.edit-password');
});

Route::post('/profil/update-password', [AuthController::class, 'updatePassword'])->name('profil.update-password');

// ======================
// AUTH ROUTES
// ======================
Route::middleware(['auth'])->group(function () {

    // --- PROFIL ---
    Route::get('/profil', function () {
        return view('profil.index'); 
    })->name('profil.index');
    
    // --- AREA PUSAT NOTIFIKASI ---
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-as-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'markSingleAsRead'])->name('notifikasi.markSingleAsRead');
    Route::get('/notifikasi/{id}/detail', [NotifikasiController::class, 'show'])->name('notifikasi.show');

    // --- AREA USER BIASA ---
    Route::get('/dashboard', function (Request $request) {
        // Redirect jika admin nyasar ke dashboard user
        if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        
        // Murni hanya menampilkan data dari model KegiatanDonasi
        $donations = \App\Models\KegiatanDonasi::orderBy('created_at', 'desc')->paginate(10);
        
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // --- USER DONASI ---
    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
    
    // REVISI: Route untuk Detail Donasi (User Biasa), menggunakan model KegiatanDonasi
    Route::get('/donasi/detail/{id}', function ($id) {
        // Menggunakan KegiatanDonasi karena list di dashboard bersumber dari model ini
        $data = KegiatanDonasi::findOrFail($id);
        return view('detail-donasi-user', compact('data'));
    })->name('user.donasi.detail');
    
    Route::get('/donasi/{id}/edit', [DonasiMakananController::class, 'edit'])->name('donasi.edit');
    Route::put('/donasi/update/{id}', [DonasiMakananController::class, 'update'])->name('donasi.update');
    Route::delete('/donasi/batal/{id}', [DonasiMakananController::class, 'cancel'])->name('donasi.cancel');

    // --- FITUR TRACKING & BUKTI DONASI ---
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking'); 
    Route::get('/tracking/{id}', function ($id) { 
        return view('tracking.trackingdetail', [
            'donation' => Donation::findOrFail($id)
        ]);
    })->name('tracking.detail');

    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti-donasi.index');
    Route::get('/bukti-donasi/{id}/detail', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');
    Route::get('/bukti-donasi/{id}/bukti', [BuktiDonasiController::class, 'showBukti'])->name('bukti-donasi.bukti');

    // --- RIWAYAT DONASI ---
    Route::get('/riwayat-donasi', [RiwayatDonationController::class, 'index'])->name('riwayat-donasi.index');
    Route::get('/riwayat-donasi/bukti/{id}', [RiwayatDonationController::class, 'showBukti'])->name('riwayat-donasi.bukti');
    Route::post('/riwayat-donasi/rating/{id}', [RiwayatDonationController::class, 'updateRating'])->name('riwayat-donasi.update-rating'); 

    // --- TIPS & KOMUNITAS & CHAT ---
    Route::get('/tips', [TipsController::class, 'index'])->name('tips.index');
    Route::post('/tips/proses', [TipsController::class, 'prosesPembayaran'])->name('tips.proses');

    Route::get('/komunitas/{id}', function ($id) {
        return view('komunitas-detail', ['post' => Komunitas::findOrFail($id)]);
    })->name('komunitas.detail');

    Route::get('/chat', function () {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if (!$admin) abort(500, 'Admin tidak ada');
        $chats = Chat::where(function ($q) use ($admin) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $admin->id);
        })->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)->where('receiver_id', auth()->id());
        })->latest()->get();
        return view('chat.user', compact('chats', 'admin'));
    })->name('chat.user');

    Route::post('/chat/send', function (Request $request) {
        $admin = \App\Models\User::where('role', 'admin')->first();
        Chat::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $admin->id,
            'message' => $request->message,
        ]);
        return back();
    })->name('chat.send');

    // ==========================================
    // GRUP ROUTE ADMIN
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // VALIDASI DONASI
        Route::prefix('validasi-proses-donasi')->group(function () {
            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])->name('validasi.setujui');
            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])->name('validasi.tolak');
            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])->name('validasi.return');
            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'disetujui'])->name('validasi.disetujui');
            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'ditolak'])->name('validasi.ditolak');
        });

        // MANAJEMEN DONASI (CRUD Admin) - Ini menggunakan model Donation/KegiatanDonasi tergantung arsitektur awal Anda.
        Route::get('/donasi/tambah', function () { return view('admin.create'); })->name('donasi.create'); 
        Route::post('/donasi/tambah', function (Request $request) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : null;
            Donation::create([
                'judul_donasi' => $request->judul, 'kategori_penerima' => $request->kategori, 'tanggal_kegiatan' => $request->tanggal,
                'foto_kegiatan' => $fotoPath, 'deskripsi' => $request->deskripsi, 'alamat_penyaluran' => $request->alamat, 'user_id' => Auth::id()
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil ditambahkan!');
        })->name('donasi.store');

        Route::get('/donasi/detail/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $data = Donation::findOrFail($id);
            return view('admin.detail-donasi', compact('data'));
        })->name('donasi.detail');
        // Fallback untuk route yang dipanggil AdminController
        Route::get('/donasi/detail-alt/{id}', [AdminController::class, 'detailDonasi'])->name('donasi.detail.alt');

        Route::get('/donasi/edit/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $data = Donation::findOrFail($id);
            return view('admin.edit-donasi', compact('data'));
        })->name('donasi.edit');

        Route::post('/donasi/update/{id}', function (Request $request, $id) {
            $donasi = Donation::findOrFail($id);
            $donasi->judul_donasi = $request->judul;
            $donasi->kategori_penerima = $request->kategori;
            $donasi->tanggal_kegiatan = $request->tanggal;
            $donasi->deskripsi = $request->deskripsi;
            $donasi->alamat_penyaluran = $request->alamat;

            if ($request->hasFile('foto')) {
                if ($donasi->foto_kegiatan) { Storage::disk('public')->delete($donasi->foto_kegiatan); }
                $donasi->foto_kegiatan = $request->file('foto')->store('donasi', 'public');
            }
            $donasi->save();
            return redirect()->route('admin.dashboard')->with('success', 'Data kegiatan berhasil diperbarui!');
        })->name('donasi.update');

        Route::post('/donasi/hapus/{id}', function ($id) {
            $donasi = Donation::findOrFail($id);
            if ($donasi->foto_kegiatan) { Storage::disk('public')->delete($donasi->foto_kegiatan); }
            if ($donasi->foto) { Storage::disk('public')->delete($donasi->foto); }
            $donasi->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
        })->name('donasi.delete');

        // RETUR
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');

        // PENUGASAN RELAWAN
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::get('/penugasan/create', [PenugasanController::class, 'create'])->name('penugasan.create');
        Route::post('/penugasan/store', [PenugasanController::class, 'store'])->name('penugasan.store');
        Route::get('/penugasan/{id}/edit', [PenugasanController::class, 'edit'])->name('penugasan.edit');
        Route::put('/penugasan/{id}', [PenugasanController::class, 'update'])->name('penugasan.update');
        Route::delete('/penugasan/{id}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');
        
        // REPORT
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');

        // KOMUNITAS ADMIN
        Route::get('/komunitas', function () {
            return view('admin.komunitas.index'); 
        })->name('komunitas.index');

        Route::get('/kerjasama-mitra', function () {
        // Sesuaikan 'admin.mitra.index' dengan lokasi file blade Anda
            return view('admin.kerjasamamitra'); 
        })->name('mitra.index');        

    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});