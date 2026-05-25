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
// AUTH USER & ADMIN
// ======================
Route::middleware('auth')->group(function () {

    // --- LOGOUT GLOBAL ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================
    // AREA USER BIASA
    // ==========================================
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard', [
            'donations' => Donation::latest()->get()
        ]);
    })->name('dashboard');

    Route::get('/donasi/detail/{id}', function ($id) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        $data = Donation::findOrFail($id);
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');

    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
    
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');
    Route::get('/tracking/{id}', function ($id) {
        return view('trackingdetail', [
            'donation' => Donation::findOrFail($id)
        ]);
    })->name('tracking.detail');

    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');

    // --- KOMUNITAS ---
    Route::get('/komunitas', function (Request $request) {
        $posts = Komunitas::query();
        if ($request->search) {
            $posts->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('isi', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->kategori) {
            $posts->where('kategori', $request->kategori);
        }
        $posts = $posts->latest()->get();
        return view('komunitas', compact('posts'));
    })->name('komunitas.index');

    Route::get('/komunitas/create', function () {
        return view('tambah-komunitas');
    })->name('komunitas.create');

    Route::get('/komunitas/{id}', function ($id) {
        $post = Komunitas::findOrFail($id);
        return view('komunitas-detail', compact('post'));
    })->name('komunitas.detail');

    Route::post('/komunitas/store', function (Request $request) {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'kategori' => 'nullable',
        ]);
        Komunitas::create([
            'nama_user' => auth()->user()->name,
            'judul'     => $request->judul,
            'isi'       => $request->isi,
            'kategori'  => $request->kategori,
        ]);
        return redirect()->route('komunitas.index');
    })->name('komunitas.store');

    // // --- KOMUNITAS (Sudah Dikeluarkan dari Area Admin) ---
    // Route::get('/komunitas', function (Request $request) {
    //     $posts = Komunitas::query();
    //     if ($request->search) {
    //         $posts->where(function ($q) use ($request) {
    //             $q->where('judul', 'like', '%' . $request->search . '%')
    //               ->orWhere('isi', 'like', '%' . $request->search . '%');
    //         });
    //     }
    //     if ($request->kategori) {
    //         $posts->where('kategori', $request->kategori);
    //     }
    //     $posts = $posts->latest()->get();
    //     return view('komunitas', compact('posts'));
    // })->name('komunitas.index');

    // Route::get('/komunitas/create', function () {
    //     return view('tambah-komunitas');
    // })->name('komunitas.create');

    // Route::get('/komunitas/{id}', function ($id) {
    //     $post = Komunitas::findOrFail($id);
    //     return view('komunitas-detail', compact('post'));
    // })->name('komunitas.detail');

    // Route::post('/komunitas/store', function (Request $request) {
    //     $request->validate([
    //         'judul' => 'required',
    //         'isi' => 'required',
    //         'kategori' => 'nullable',
    //     ]);
    //     Komunitas::create([
    //         'nama_user' => auth()->user()->name,
    //         'judul'     => $request->judul,
    //         'isi'       => $request->isi,
    //         'kategori'  => $request->kategori,
    //     ]);
    //     return redirect()->route('komunitas.index');
    // })->name('komunitas.store');

    // --- CHAT USER ---
    Route::get('/chat', function () {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if (!$admin) abort(500, 'Admin tidak ada');
        $chats = \App\Models\Chat::where(function ($q) use ($admin) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $admin->id);
        })
        ->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)->where('receiver_id', auth()->id());
        })
        ->orderBy('created_at', 'asc')->get();
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
    // AREA KHUSUS ADMIN
    // ==========================================
    
    // GRUP ADMIN 1 (Route menggunakan nama awalan 'admin.')
    Route::prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $query = Donation::query();
            if ($request->search) { $query->where('judul_donasi', 'like', "%{$request->search}%"); }
            if ($request->kategori) { $query->whereIn('kategori_penerima', (array) $request->kategori); }
            return view('admin.dashboardAdmin', ['semuaDonasi' => $query->latest()->get()]);
        })->name('dashboard');

        Route::get('/validasi', [AdminValidasiController::class, 'index'])->name('validasi.index');
        Route::get('/penugasan', fn () => view('admin.penugasan'))->name('penugasan.index');
        
        Route::get('/donasi/tambah', fn () => view('admin.tambah-donasi'))->name('donasi.create');
        Route::post('/donasi/tambah', function (Request $request) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : null;
            Donation::create([
                'judul_donasi' => $request->judul,
                'kategori_penerima' => $request->kategori,
                'tanggal_kegiatan' => $request->tanggal,
                'foto_kegiatan' => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat_penyaluran' => $request->alamat
            ]);
            return redirect()->route('admin.dashboard');
        })->name('donasi.store');

        Route::post('/donasi/hapus/{id}', function ($id) {
            $donasi = Donation::findOrFail($id);
            if ($donasi->foto_kegiatan) { Storage::disk('public')->delete($donasi->foto_kegiatan); }
            if ($donasi->foto) { Storage::disk('public')->delete($donasi->foto); }
            $donasi->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
        })->name('donasi.delete');

        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });

    // GRUP ADMIN 2 (Route Mitra & Dropbox tanpa awalan nama 'admin.')
    Route::prefix('admin')->group(function () {
        
        // --- KERJA SAMA MITRA ---
        Route::get('/kerjasama-mitra', function (Request $request) {
            if (!session()->has('mitra_data')) {
                session()->put('mitra_data', [
                    ['id' => 1, 'nama_mitra' => 'Restoran Sederhana', 'status' => 'aktif', 'kategori' => 'Restoran', 'lokasi' => 'Jakarta Selatan', 'keterangan_waktu' => 'Bergabung Jan 2025', 'total_donasi' => '142', 'porsi_tersalur' => '1.2k', 'logo' => null, 'deskripsi' => 'Restoran Sederhana merupakan mitra kuliner berjenis Restoran yang berlokasi di Jakarta Selatan. Mitra ini berkomitmen penuh untuk mendukung program FoodLink dalam mendistribusikan makanan layak konsumsi guna mengurangi food waste dan membantu masyarakat sekitar.'],
                    ['id' => 2, 'nama_mitra' => 'Yayasan Peduli Pangan', 'status' => 'pengajuan', 'kategori' => 'NGO', 'lokasi' => 'Jakarta Timur', 'keterangan_waktu' => 'Diajukan 20 Mar 2026', 'total_donasi' => 0, 'porsi_tersalur' => 0, 'logo' => null, 'deskripsi' => 'Yayasan Peduli Pangan merupakan lembaga swadaya masyarakat berjenis NGO yang berfokus di Jakarta Timur. Bermitra dengan FoodLink, yayasan ini aktif bergerak dalam pengelolaan sisa makanan secara higienis untuk disalurkan kepada pihak yang membutuhkan.'],
                    ['id' => 3, 'nama_mitra' => 'Kantin Kampus UI', 'status' => 'tidak_aktif', 'kategori' => 'Kantin', 'lokasi' => 'Depok', 'keterangan_waktu' => 'Terakhir aktif Nov 2025', 'total_donasi' => '23', 'porsi_tersalur' => '180', 'logo' => null, 'deskripsi' => 'Kantin Kampus UI merupakan area kuliner berjenis Kantin yang terletak di Depok. Melalui kolaborasi bersama FoodLink, para pelaku usaha di kantin ini ikut berkontribusi nyata dalam mendonasikan surplus makanan layak makan bagi lingkungan sekitar.'],
                ]);
            }
            $allMitras = collect(session('mitra_data'))->map(function($item) { return (object) $item; });
            $status = $request->query('status'); $kategori = $request->query('kategori'); $search = $request->query('search');
            $mitras = $allMitras;
            if ($status) $mitras = $mitras->where('status', $status);
            if ($kategori) $mitras = $mitras->where('kategori', $kategori);
            if ($search) {
                $mitras = $mitras->filter(function($item) use ($search) { return stripos($item->nama_mitra, $search) !== false || stripos($item->lokasi, $search) !== false || stripos($item->kategori, $search) !== false; });
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
            $deskripsiDinamis = "{$nama} merupakan mitra kerja sama berjenis {$kategori} yang beroperasi di wilayah {$lokasi}. Mitra ini berkomitmen penuh untuk bersinergi bersama platform FoodLink dalam mengelola surplus makanan layak guna menyebarkan dampak sosial positif bagi masyarakat sekitar.";
            $newMitra = ['id' => $newId, 'nama_mitra' => $nama, 'status' => 'pengajuan', 'kategori' => $kategori, 'lokasi' => $lokasi, 'keterangan_waktu' => 'Diajukan ' . date('d M Y'), 'total_donasi' => 0, 'porsi_tersalur' => 0, 'logo' => null, 'deskripsi' => $deskripsiDinamis];
            $mitras[] = $newMitra;
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
            $now = time();

            if (!session()->has('dropbox_data')) {
                session()->put('dropbox_data', [
                    ['id' => 1, 'nama' => 'Drop Box Sudirman', 'status' => 'tersedia', 'lokasi' => 'Jl. Jend. Sudirman No.1', 'mitra' => 'Gedung Artha', 'kapasitas' => 30, 'update' => 'Menunggu Penjemputan', 'lat' => -6.2250, 'lng' => 106.8056, 'history' => []],
                    ['id' => 2, 'nama' => 'Drop Box Tebet', 'status' => 'hampir_penuh', 'lokasi' => 'Tebet Eco Park', 'mitra' => 'Dinas Taman', 'kapasitas' => 85, 'update' => 'Menunggu Penjemputan', 'lat' => -6.2400, 'lng' => 106.8550, 'history' => []],
                    ['id' => 3, 'nama' => 'Drop Box Cempaka Putih', 'status' => 'penuh', 'lokasi' => 'RS Yarsi', 'mitra' => 'RS Yarsi', 'kapasitas' => 100, 'update' => 'Menunggu Penjemputan', 'lat' => -6.1700, 'lng' => 106.8700, 'history' => []],
                ]);
            }

            $dropboxes = session('dropbox_data');
            $dataChanged = false;

            foreach ($dropboxes as $key => $db) {
                if (isset($db['active_task'])) {
                    $t = $db['active_task'];
                    if ($now >= $t['waktu_selesai']) {
                        $dropboxes[$key]['kapasitas'] = rand(0, 5);
                        $dropboxes[$key]['status'] = 'tersedia';
                        $dropboxes[$key]['update'] = date('H:i', $t['waktu_selesai']) . " | {$t['petugas']} - Selesai Di Antar";
                        $dropboxes[$key]['history'][] = $dropboxes[$key]['update']; 
                        unset($dropboxes[$key]['active_task']);
                        $dataChanged = true;
                    } elseif ($now >= $t['waktu_jemput']) {
                        $dropboxes[$key]['update'] = date('H:i', $now) . " | {$t['petugas']} - Sedang Mengantar Barang (Est Selesai: " . date('H:i', $t['waktu_selesai']) . ")";
                        $dataChanged = true;
                    } elseif ($now >= $t['waktu_mulai']) {
                        $dropboxes[$key]['update'] = date('H:i', $now) . " | {$t['petugas']} - Sedang Menjemput Barang (Est Tiba: " . date('H:i', $t['waktu_jemput']) . ")";
                        $dataChanged = true;
                    } else {
                        $dropboxes[$key]['update'] = "Dijadwalkan: {$t['petugas']} pada " . date('H:i', $t['waktu_mulai']);
                    }
                }
            }
            if ($dataChanged) { session()->put('dropbox_data', $dropboxes); }

            $allDropboxes = collect($dropboxes)->map(function($item) { return (object) $item; });
            $search = $request->query('search');
            if ($search) {
                $allDropboxes = $allDropboxes->filter(function($item) use ($search) { 
                    return stripos($item->nama, $search) !== false || stripos($item->lokasi, $search) !== false; 
                });
            }

            return view('admin.dropbox', [
                'dropboxes' => $allDropboxes,
                'totalLokasi' => $allDropboxes->count(),
                'tersedia' => $allDropboxes->where('status', 'tersedia')->count(),
                'hampirPenuh' => $allDropboxes->where('status', 'hampir_penuh')->count(),
                'penuh' => $allDropboxes->where('status', 'penuh')->count()
            ]);
        })->name('dropbox.index');

        Route::post('/drop-box/store', function (Request $request) {
            $dropboxes = session('dropbox_data', []);
            $newId = count($dropboxes) > 0 ? max(array_column($dropboxes, 'id')) + 1 : 1;
            $lat = -6.2000 + (mt_rand(-400, 400) / 10000); $lng = 106.8100 + (mt_rand(-400, 400) / 10000);
            $newDropbox = [
                'id' => $newId, 'nama' => $request->input('nama'), 'status' => 'tersedia', 
                'lokasi' => $request->input('lokasi'), 'mitra' => $request->input('mitra'), 
                'kapasitas' => rand(2, 10), 'update' => 'Baru saja ditambahkan', 'lat' => $lat, 'lng' => $lng, 'history' => []
            ];
            $dropboxes[] = $newDropbox;
            session()->put('dropbox_data', $dropboxes);
            return redirect()->route('dropbox.index');
        })->name('dropbox.store');

        Route::post('/drop-box/{id}/jemput', function (Request $request, $id) {
            date_default_timezone_set('Asia/Jakarta');
            $dropboxes = session('dropbox_data', []);
            $namaPetugas = $request->input('petugas');
            $jamMulaiStr = $request->input('waktu');
            $waktuMulaiTS = strtotime(date('Y-m-d ') . $jamMulaiStr);

            foreach ($dropboxes as $key => $db) {
                if ($db['id'] == $id) {
                    if(!isset($dropboxes[$key]['history'])) { $dropboxes[$key]['history'] = []; }
                    $latAwal = $db['lat'] + (mt_rand(-300, 300) / 10000);
                    $lngAwal = $db['lng'] + (mt_rand(-300, 300) / 10000);
                    $latTujuan = $db['lat'] + (mt_rand(-400, 400) / 10000);
                    $lngTujuan = $db['lng'] + (mt_rand(-400, 400) / 10000);

                    $dropboxes[$key]['active_task'] = [
                        'petugas' => $namaPetugas,
                        'waktu_mulai' => $waktuMulaiTS,               
                        'waktu_jemput' => $waktuMulaiTS + (1 * 60),   
                        'waktu_selesai' => $waktuMulaiTS + (2 * 60),  
                        'lat_awal' => $latAwal,
                        'lng_awal' => $lngAwal,
                        'lat_tujuan' => $latTujuan,
                        'lng_tujuan' => $lngTujuan
                    ];
                    $dropboxes[$key]['update'] = "Dijadwalkan: $namaPetugas pada $jamMulaiStr";
                    break;
                }
            }
            session()->put('dropbox_data', $dropboxes);
            return redirect()->route('dropbox.jemput');
        })->name('dropbox.jemput');
    });
});