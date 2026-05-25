<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;
use App\Http\Controllers\AdminValidasiController;

use App\Models\Donation;
use App\Models\Komunitas;
use App\Models\Chat;

/*
|--------------------------------------------------------------------------
| FOODLINK ROUTES CLEAN VERSION
|--------------------------------------------------------------------------
*/

// ======================
// HOME
// ======================
Route::get('/', fn () => view('welcome'));


// ======================
// GUEST
// ======================
Route::middleware('guest')->group(function () {

    Route::get('/login', [Controller::class, 'showLogin'])->name('login');
    Route::post('/login', [Controller::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// ======================
// AUTH USER
// ======================
Route::middleware('auth')->group(function () {

    // DASHBOARD USER
    Route::get('/dashboard', function () {

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard', [
            'donations' => Donation::latest()->get()
        ]);
    })->name('dashboard');


    // DETAIL DONASI USER
    Route::get('/donasi/detail/{id}', function ($id) {

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('detail-donasi-user', [
            'data' => Donation::findOrFail($id)
        ]);
    })->name('user.donasi.detail');


    // DONASI USER
    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');


    // TRACKING
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');

    Route::get('/tracking/{id}', function ($id) {
        return view('trackingdetail', [
            'donation' => Donation::findOrFail($id)
        ]);
    })->name('tracking.detail');


    // BUKTI
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');


    // KOMUNITAS
    Route::get('/komunitas', function (Request $request) {

        $posts = Komunitas::query();

        if ($request->search) {
            $posts->where('judul', 'like', "%{$request->search}%")
                  ->orWhere('isi', 'like', "%{$request->search}%");
        }

        if ($request->kategori) {
            $posts->where('kategori', $request->kategori);
        }

        return view('komunitas', [
            'posts' => $posts->latest()->get()
        ]);
    })->name('komunitas.index');


    Route::get('/komunitas/create', fn () => view('tambah-komunitas'))
        ->name('komunitas.create');

    Route::get('/komunitas/{id}', function ($id) {
        return view('komunitas-detail', [
            'post' => Komunitas::findOrFail($id)
        ]);
    })->name('komunitas.detail');

    Route::post('/komunitas/store', function (Request $request) {

        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        Komunitas::create([
            'nama_user' => auth()->user()->name,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('komunitas.index');
    })->name('komunitas.store');


    // CHAT USER
    Route::get('/chat', function () {

        $admin = \App\Models\User::where('role', 'admin')->first();

        if (!$admin) abort(500, 'Admin tidak ada');

        $chats = Chat::where(function ($q) use ($admin) {
            $q->where('sender_id', auth()->id())
              ->where('receiver_id', $admin->id);
        })
        ->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)
              ->where('receiver_id', auth()->id());
        })
        ->latest()
        ->get();

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


    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ======================
// ADMIN
// ======================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function (Request $request) {

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $query = Donation::query();

        if ($request->search) {
            $query->where('judul_donasi', 'like', "%{$request->search}%");
        }

        if ($request->kategori) {
            $query->whereIn('kategori_penerima', (array) $request->kategori);
        }

        return view('admin.dashboardAdmin', [
            'semuaDonasi' => $query->latest()->get()
        ]);

    })->name('dashboard');


    // VALIDASI
    Route::get('/validasi', [AdminValidasiController::class, 'index'])
        ->name('validasi.index');


    // PENUGASAN (FIX ERROR KAMU)
    Route::get('/penugasan', fn () => view('admin.penugasan'))
        ->name('penugasan.index');


    // DONASI CREATE (FIX ERROR UTAMA KAMU)
    Route::get('/donasi/tambah', fn () => view('admin.tambah-donasi'))
        ->name('donasi.create');


    Route::post('/donasi/tambah', function (Request $request) {

        Donation::create([
            'judul_donasi' => $request->judul,
            'kategori_penerima' => $request->kategori,
            'tanggal_kegiatan' => $request->tanggal,
            'foto_kegiatan' => $request->file('foto')
                ? $request->file('foto')->store('donasi', 'public')
                : null,
            'deskripsi' => $request->deskripsi,
            'alamat_penyaluran' => $request->alamat
        ]);

        return redirect()->route('admin.dashboard');

    })->name('donasi.store');


    // RETUR
    Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');


    // REPORT
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
});