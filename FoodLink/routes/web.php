<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Donation;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\RiwayatDonationController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;
use App\Http\Controllers\TipsController;

Route::get('/', function () { return view('welcome'); });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        $donations = Donation::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    Route::get('/donasi/detail/{id}', function ($id) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        $data = Donation::findOrFail($id);
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');

    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
    
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');
    Route::get('/bukti-donasi/{id}/detail', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');
    Route::get('/bukti-donasi/{id}/bukti', [BuktiDonasiController::class, 'showBukti'])->name('bukti-donasi.bukti');

    Route::get('/riwayat-donation', [RiwayatDonationController::class, 'index'])->name('riwayat-donasi.index');
    Route::get('/riwayat-donasi/{id}/bukti', [RiwayatDonationController::class, 'showBukti'])->name('riwayat-donasi.show-bukti');
    Route::post('/riwayat-donasi/rating/{id}', [RiwayatDonationController::class, 'updateRating'])->name('riwayat-donasi.update-rating');

    Route::get('/tips', [TipsController::class, 'index'])->name('tips.index');
    Route::post('/tips/proses', [TipsController::class, 'prosesPembayaran'])->name('tips.proses');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $semuaDonasi = Donation::orderBy('created_at', 'desc')->get();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('dashboard');

        Route::get('/kegiatan/baru', [KegiatanDonasiController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan/simpan', [KegiatanDonasiController::class, 'store'])->name('kegiatan.store');

        Route::prefix('validasi-proses-donasi')->group(function () {
            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'halamanDisetujui'])->name('validasi.disetujui');
            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'halamanDitolak'])->name('validasi.ditolak');
            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])->name('validasi.setujui');
            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])->name('validasi.tolak');
        });

        Route::get('/donasi/tambah', function () { return view('admin.tambah-donasi'); })->name('donasi.create');
        Route::post('/donasi/tambah', function (Request $request) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : null;
            Donation::create([
                'judul' => $request->judul, 'kategori' => $request->kategori, 'tanggal' => $request->tanggal,
                'foto' => $fotoPath, 'deskripsi' => $request->deskripsi, 'alamat' => $request->alamat, 'user_id' => Auth::id()
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

        Route::post('/donasi/hapus/{id}', function ($id) {
            $donasi = Donation::findOrFail($id);
            if ($donasi->foto_kegiatan) { Storage::disk('public')->delete($donasi->foto_kegiatan); }
            if ($donasi->foto) { Storage::disk('public')->delete($donasi->foto); }
            $donasi->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
        })->name('donasi.delete');

        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });

    // ===== KERJA SAMA MITRA =====
    Route::prefix('admin')->group(function () {
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

        // ===== DROP BOX INTERAKTIF MAPS ANIMATION =====
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
                        // Selesai Di Antar
                        $dropboxes[$key]['kapasitas'] = rand(0, 5);
                        $dropboxes[$key]['status'] = 'tersedia';
                        $dropboxes[$key]['update'] = date('H:i', $t['waktu_selesai']) . " | {$t['petugas']} - Selesai Di Antar";
                        $dropboxes[$key]['history'][] = $dropboxes[$key]['update']; 
                        unset($dropboxes[$key]['active_task']);
                        $dataChanged = true;
                    } elseif ($now >= $t['waktu_jemput']) {
                        // Sedang Mengantar Barang (Dari Drop Box ke Tujuan)
                        $dropboxes[$key]['update'] = date('H:i', $now) . " | {$t['petugas']} - Sedang Mengantar Barang (Est Selesai: " . date('H:i', $t['waktu_selesai']) . ")";
                        $dataChanged = true;
                    } elseif ($now >= $t['waktu_mulai']) {
                        // Sedang Menjemput Barang (Dari Pangkalan ke Drop Box)
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
            $jamMulaiStr = $request->input('waktu'); // Input Jam

            $waktuMulaiTS = strtotime(date('Y-m-d ') . $jamMulaiStr);

            foreach ($dropboxes as $key => $db) {
                if ($db['id'] == $id) {
                    if(!isset($dropboxes[$key]['history'])) { $dropboxes[$key]['history'] = []; }

                    // Generate koordinat Pangkalan Relawan (Awal) dan Tujuan Antar (Akhir)
                    $latAwal = $db['lat'] + (mt_rand(-300, 300) / 10000);
                    $lngAwal = $db['lng'] + (mt_rand(-300, 300) / 10000);
                    $latTujuan = $db['lat'] + (mt_rand(-400, 400) / 10000);
                    $lngTujuan = $db['lng'] + (mt_rand(-400, 400) / 10000);

                    // Set Tugas Aktif (Cepat untuk Presentasi: Jemput +1 Menit, Antar +2 Menit)
                    $dropboxes[$key]['active_task'] = [
                        'petugas' => $namaPetugas,
                        'waktu_mulai' => $waktuMulaiTS,               // Berangkat dari Pangkalan
                        'waktu_jemput' => $waktuMulaiTS + (1 * 60),   // Tiba di Drop Box
                        'waktu_selesai' => $waktuMulaiTS + (2 * 60),  // Tiba di Tujuan (Selesai)
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
            return redirect()->route('dropbox.index');
        })->name('dropbox.jemput');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});