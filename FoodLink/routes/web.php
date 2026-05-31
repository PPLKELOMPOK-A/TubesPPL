<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\Donation;
use App\Models\Chat;
use App\Models\Komunitas;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;
use App\Http\Controllers\RiwayatDonationController;
use App\Http\Controllers\TipsController; // <-- Tambahan dari GitHub

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project (CLEAN MERGED VERSION)
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
});


// ======================
// AUTH USER & ADMIN ROUTES
// ======================
Route::middleware('auth')->group(function () {

    // ===== DASHBOARD USER (Diambil dari versi perbaikan sebelumnya) =====
    Route::get('/dashboard', function (Request $request) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        
        $donations = \App\Models\KegiatanDonasi::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // ===== FITUR PENGAJUAN DONASI & DETAIL =====
    Route::get('/donasi/detail/{id}', function ($id) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        $data = \App\Models\KegiatanDonasi::findOrFail($id);
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');

    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
    
    // ===== FITUR TRACKING & BUKTI DONASI =====
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking'); // Tetap pakai nama route lama agar tidak rusak
    Route::get('/tracking/{id}', function ($id) { // Fitur baru dari GitHub
        return view('tracking.trackingdetail', [
            'donation' => Donation::findOrFail($id)
        ]);
    })->name('tracking.detail');

    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');
    Route::get('/bukti-donasi/{id}/bukti', [BuktiDonasiController::class, 'showBukti'])->name('bukti-donasi.bukti');
    Route::get('/bukti-donasi/{id}/detail', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');

<<<<<<< HEAD

=======
>>>>>>> 0318b472c7e814449c8accb87866566fd3c80ade
    // ===== FITUR RIWAYAT & MANAJEMEN DONASI (Gabungan) =====
    Route::get('/riwayat-donasi', [RiwayatDonationController::class, 'index'])->name('riwayat-donasi.index');
    Route::get('/donasi/{id}/edit', [DonasiMakananController::class, 'edit'])->name('donasi.edit');
    Route::put('/donasi/update/{id}', [DonasiMakananController::class, 'update'])->name('donasi.update');
    Route::delete('/donasi/batal/{id}', [DonasiMakananController::class, 'cancel'])->name('donasi.cancel');
    Route::get('/riwayat-donasi/bukti/{id}', [RiwayatDonationController::class, 'showBukti'])->name('riwayat-donasi.bukti');
    Route::post('/riwayat-donasi/rating/{id}', [RiwayatDonationController::class, 'updateRating'])->name('riwayat-donasi.update-rating'); // Fitur baru dari GitHub

    // ===== FITUR BARU: TIPS & KOMUNITAS (Dari GitHub) =====
    Route::get('/tips', [TipsController::class, 'index'])->name('tips.index');
    Route::post('/tips/proses', [TipsController::class, 'prosesPembayaran'])->name('tips.proses');
    Route::get('/komunitas/{id}', function ($id) {
        return view('komunitas-detail', ['post' => Komunitas::findOrFail($id)]);
    })->name('komunitas.detail');

    // ===== FITUR BARU: CHAT (Dari GitHub) =====
    Route::get('/chat', function () {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if (!$admin) abort(500, 'Admin tidak ada');

        $chats = Chat::where(function ($q) use ($admin) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $admin->id);
        })
        ->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)->where('receiver_id', auth()->id());
        })
        ->latest()->get();

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
    // GRUP ROUTE ADMIN (Dengan Prefix 'admin.')
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // --- DASHBOARD ADMIN (Model KegiatanDonasi + Search/Filter dari GitHub) ---
        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $query = \App\Models\KegiatanDonasi::query();
            
            if ($request->search) {
                $query->where('judul_donasi', 'like', "%{$request->search}%");
            }
            if ($request->kategori) {
                $query->whereIn('kategori_penerima', (array) $request->kategori);
            }

            return view('admin.dashboardAdmin', ['semuaDonasi' => $query->latest()->get()]);
        })->name('dashboard');

        // Kegiatan Baru
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

        // ===== MANAJEMEN DONASI =====
        Route::get('/donasi/tambah', function () { return view('admin.create'); })->name('donasi.create'); // Sesuai permintaan: admin.create
        
        Route::post('/donasi/tambah', function (Request $request) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : null;
            Donation::create([
                // Penamaan field disesuaikan dengan kode baru dari GitHub
                'judul_donasi' => $request->judul, 'kategori_penerima' => $request->kategori, 'tanggal_kegiatan' => $request->tanggal,
                'foto_kegiatan' => $fotoPath, 'deskripsi' => $request->deskripsi, 'alamat_penyaluran' => $request->alamat, 'user_id' => Auth::id()
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil ditambahkan!');
        })->name('donasi.store');

        Route::get('/donasi/detail/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            return view('admin.detail-donasi', ['data' => Donation::findOrFail($id)]);
        })->name('donasi.detail');

        Route::get('/donasi/edit/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            return view('admin.edit-donasi', ['data' => Donation::findOrFail($id)]);
        })->name('donasi.edit');

        Route::post('/donasi/hapus/{id}', function ($id) {
            $donasi = Donation::findOrFail($id);
            // Menghapus file foto baik menggunakan nama field lama atau baru
            if ($donasi->foto_kegiatan) { Storage::disk('public')->delete($donasi->foto_kegiatan); }
            if ($donasi->foto) { Storage::disk('public')->delete($donasi->foto); }
            $donasi->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
        })->name('donasi.delete');

        // Retur, Penugasan, Report
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    });

    // ==========================================
    // GRUP KERJASAMA MITRA & DROPBOX (Tanpa 'admin.' name prefix)
    // ==========================================
    Route::prefix('admin')->group(function () {
        
        // --- KERJA SAMA MITRA ---
        Route::get('/kerjasama-mitra', function (Request $request) {
            if (!session()->has('mitra_data')) {
                session()->put('mitra_data', [
                    ['id' => 1, 'nama_mitra' => 'Restoran Sederhana', 'status' => 'aktif', 'kategori' => 'Restoran', 'lokasi' => 'Jakarta Selatan', 'keterangan_waktu' => 'Bergabung Jan 2025', 'total_donasi' => '142', 'porsi_tersalur' => '1.2k', 'logo' => null, 'deskripsi' => 'Restoran Sederhana merupakan mitra kuliner berjenis Restoran yang berlokasi di Jakarta Selatan. Mitra ini berkomitmen penuh untuk mendukung program FoodLink dalam mendistribusikan makanan layak konsumsi guna mengurangi food waste dan membantu masyarakat sekitar.'],
                ]);
            }
            $allMitras = collect(session('mitra_data'))->map(function($item) { return (object) $item; });
            $status = $request->query('status'); $kategori = $request->query('kategori'); $search = $request->query('search');
            $mitras = $allMitras;
            
            if ($status) $mitras = $mitras->where('status', $status);
            if ($kategori) $mitras = $mitras->where('kategori', $kategori);
            if ($search) {
                $mitras = $mitras->filter(function($item) use ($search) { return stripos($item->nama_mitra, $search) !== false || stripos($item->lokasi, $search) !== false; });
            }
            return view('admin.kerjasamamitra', [
                'mitras' => $mitras, 'totalMitra' => $allMitras->count(), 
                'mitraAktif' => $allMitras->where('status', 'aktif')->count(), 'mitraPengajuan' => $allMitras->where('status', 'pengajuan')->count()
            ]);
        })->name('mitra.index');

        Route::post('/kerjasama-mitra/store', function (Request $request) {
            $mitras = session('mitra_data', []);
            $newId = count($mitras) > 0 ? max(array_column($mitras, 'id')) + 1 : 1;
            $nama = $request->input('nama_mitra'); $kategori = $request->input('kategori'); $lokasi = $request->input('lokasi');
            $deskripsi = "{$nama} merupakan mitra kerja sama berjenis {$kategori} di {$lokasi}.";
            $mitras[] = ['id' => $newId, 'nama_mitra' => $nama, 'status' => 'pengajuan', 'kategori' => $kategori, 'lokasi' => $lokasi, 'keterangan_waktu' => 'Diajukan ' . date('d M Y'), 'total_donasi' => 0, 'porsi_tersalur' => 0, 'logo' => null, 'deskripsi' => $deskripsi];
            session()->put('mitra_data', $mitras);
            return redirect()->route('mitra.index', ['status' => 'pengajuan']);
        })->name('mitra.store');

        Route::patch('/kerjasama-mitra/{id}/status', function (Request $request, $id) {
            $statusBaru = $request->input('status');
            $mitras = session('mitra_data', []);
            if ($statusBaru === 'ditolak') {
                $mitras = array_filter($mitras, function($m) use ($id) { return $m['id'] != $id; });
                session()->put('mitra_data', array_values($mitras));
                return redirect()->route('mitra.index', ['status' => 'pengajuan']);
            }
            foreach ($mitras as $key => $mitra) {
                if ($mitra['id'] == $id) { $mitras[$key]['status'] = $statusBaru; break; }
            }
            session()->put('mitra_data', $mitras);
            return redirect()->route('mitra.index', ['status' => $statusBaru]);
        })->name('mitra.updateStatus');

        // --- DROP BOX ---
        Route::get('/drop-box', function (Request $request) {
            date_default_timezone_set('Asia/Jakarta');
            if (!session()->has('dropbox_data')) {
                session()->put('dropbox_data', [
                    ['id' => 1, 'nama' => 'Drop Box Sudirman', 'status' => 'tersedia', 'lokasi' => 'Jl. Jend. Sudirman No.1', 'mitra' => 'Gedung Artha', 'kapasitas' => 30, 'update' => 'Menunggu Penjemputan', 'lat' => -6.2250, 'lng' => 106.8056, 'history' => []],
                ]);
            }
            $dropboxes = session('dropbox_data');
            return view('admin.dropbox', [
                'dropboxes' => collect($dropboxes)->map(function($item) { return (object) $item; }),
                'totalLokasi' => count($dropboxes)
            ]);
        })->name('dropbox.index');

        Route::post('/drop-box/store', function (Request $request) {
            $dropboxes = session('dropbox_data', []);
            $dropboxes[] = [
                'id' => rand(10, 100), 'nama' => $request->input('nama'), 'status' => 'tersedia', 
                'lokasi' => $request->input('lokasi'), 'mitra' => $request->input('mitra'), 
                'kapasitas' => rand(2, 10), 'update' => 'Baru saja ditambahkan', 'lat' => -6.2000, 'lng' => 106.8100, 'history' => []
            ];
            session()->put('dropbox_data', $dropboxes);
            return redirect()->route('dropbox.index');
        })->name('dropbox.store');

        Route::post('/drop-box/{id}/jemput', function (Request $request, $id) {
            return redirect()->route('dropbox.index');
        })->name('dropbox.jemput');
    });

    // ================= RETUR DONASI GLOBAL =================
    Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
    Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');
    
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});