<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Donation;
use App\Models\Chat;
use App\Models\Komunitas;
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
use App\Http\Controllers\TipsController;

/*
|--------------------------------------------------------------------------
| Web Routes - Foodlink Project (FIXED MERGED VERSION)
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

    // --- RUTE DASHBOARD USER (Pencarian & Filter Aktif) ---
    Route::get('/dashboard', function (Request $request) {
        if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard'); 
        }
        
        $query = Donation::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter
        if ($request->has('kategori') && is_array($request->kategori)) {
            $query->whereIn('kategori', $request->kategori);
        }

        $donations = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // ===== FITUR USER (Tracking, Riwayat, Tips, Komunitas, Bukti) =====
    Route::get('/riwayat-donation', [RiwayatDonationController::class, 'index'])->name('riwayat-donasi.index');
    Route::get('/riwayat-donasi/{id}/bukti', [RiwayatDonationController::class, 'showBukti'])->name('riwayat-donasi.show-bukti');
    Route::post('/riwayat-donasi/rating/{id}', [RiwayatDonationController::class, 'updateRating'])->name('riwayat-donasi.update-rating');

    Route::get('/tracking', [DonationController::class, 'index'])->name('tracking.index');
    Route::get('/tracking/{id}', function ($id) {
        return view('tracking.trackingdetail', [
            'donation' => Donation::findOrFail($id)
        ]);
    })->name('tracking.detail');

    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');

    Route::get('/tips', [TipsController::class, 'index'])->name('tips.index');
    Route::post('/tips/proses', [TipsController::class, 'prosesPembayaran'])->name('tips.proses');

    Route::get('/komunitas/{id}', function ($id) {
        return view('komunitas-detail', ['post' => Komunitas::findOrFail($id)]);
    })->name('komunitas.detail');

    // ===== FITUR CHAT =====
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

        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $query = Donation::query();

            // Fitur Pencarian Admin
            if ($request->has('search') && $request->search != '') {
                $query->where('judul', 'like', '%' . $request->search . '%');
            }

            // Fitur Filter Admin
            if ($request->has('kategori') && is_array($request->kategori)) {
                $query->whereIn('kategori', $request->kategori);
            }

            $semuaDonasi = $query->orderBy('created_at', 'desc')->get();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('dashboard');

        // Kegiatan & Donasi Baru
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
            
            // SEBAIKNYA DISESUAIKAN: Petakan input form ($request) ke kolom database yang benar
            Donation::create([
                'judul_donasi'      => $request->judul, 
                'kategori_penerima'  => $request->kategori, 
                'tanggal_kegiatan'   => $request->tanggal,
                'foto_kegiatan'      => $fotoPath, 
                'deskripsi'          => $request->deskripsi, 
                'alamat_penyaluran'  => $request->alamat, 
                'user_id'            => Auth::id()
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

        // Penugasan & Report
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });


    // ==========================================
    // --- ROUTE UNTUK USER BIASA ---
    // ==========================================
    
    // Rute untuk melihat detail donasi oleh user
    Route::get('/donasi/detail/{id}', function ($id) {
        $data = App\Models\Donation::findOrFail($id);
        return view('detail-donasi-user', compact('data')); 
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
    // GRUP KERJASAMA MITRA & DROPBOX (Tanpa 'admin.' name prefix)
    // ==========================================
    Route::prefix('admin')->group(function () {
        
        // --- KERJA SAMA MITRA ---
        Route::get('/kerjasama-mitra', function (Request $request) {
            if (!session()->has('mitra_data')) {
                session()->put('mitra_data', [
                    ['id' => 1, 'nama_mitra' => 'Restoran Sederhana', 'status' => 'aktif', 'kategori' => 'Restoran', 'lokasi' => 'Jakarta Selatan', 'keterangan_waktu' => 'Bergabung Jan 2025', 'total_donasi' => '142', 'porsi_tersalur' => '1.2k', 'logo' => null, 'deskripsi' => 'Restoran Sederhana merupakan mitra kuliner...'],
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

    // ==========================================
    // --- GRUP ROUTE RETUR DONASI (MURNI TANPA PREFIX NAME) ---
    // ==========================================
    Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
    Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');

    // --- ROUTE PROFIL USER KESELURUHAN ---
    Route::get('/profil', function () {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        return view('profil');
    })->name('profil');

    Route::get('/profil/edit', function () {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        return view('edit-profil'); 
    })->name('profil.edit');

    Route::post('/profil/update', function (Request $request) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }

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

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});