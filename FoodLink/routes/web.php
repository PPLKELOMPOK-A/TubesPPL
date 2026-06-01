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
use App\Models\KegiatanDonasi;
use App\Models\Penugasan; // <-- SAYA TAMBAHKAN INI

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
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\RiwayatDonationController;
use App\Http\Controllers\TipsController; 

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
    
    Route::get('/forgot-password', function () {
        return view('auth.lupa-password');
    })->name('password.request');
    Route::post('/forgot-password/check', [AuthController::class, 'checkEmail'])->name('password.check');
});

Route::middleware('auth')->group(function () {

    // --- AREA PUSAT NOTIFIKASI ---
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-as-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'markSingleAsRead'])->name('notifikasi.markSingleAsRead');
    Route::get('/notifikasi/{id}/detail', [NotifikasiController::class, 'show'])->name('notifikasi.show');

    // ===== DASHBOARD USER =====
    Route::get('/dashboard', function (Request $request) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        
        $query = KegiatanDonasi::query();
        
        // Logika Input Search Box
        if ($request->filled('search')) {
            $query->where('judul_donasi', 'like', "%{$request->search}%");
        }
        
        // Logika Checkbox Kategori Filter
        if ($request->has('kategori')) {
            $query->whereIn('kategori_penerima', (array) $request->kategori);
        }

        $donations = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard', compact('donations'));
    })->name('dashboard');

    // ===== FITUR PENGAJUAN DONASI & DETAIL =====
    Route::get('/donasi/detail/{id}', function ($id) {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        $data = KegiatanDonasi::findOrFail($id);
        return view('detail-donasi-user', compact('data')); 
    })->name('user.donasi.detail');

    Route::get('/donasi/baru', [DonasiMakananController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/simpan', [DonasiMakananController::class, 'store'])->name('donasi.store');
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

    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti.donasi');
    Route::get('/bukti-donasi/detail/{id}', [BuktiDonasiController::class, 'show'])->name('bukti.donasi.detail');
    Route::get('/bukti-donasi/{id}/bukti', [BuktiDonasiController::class, 'showBukti'])->name('bukti-donasi.bukti');

    // ===== FITUR RIWAYAT & MANAJEMEN DONASI =====
    Route::get('/riwayat-donasi', [RiwayatDonationController::class, 'index'])->name('riwayat-donasi.index');
    Route::get('/riwayat-donasi/bukti/{id}', [RiwayatDonationController::class, 'showBukti'])->name('riwayat-donasi.bukti');
    Route::post('/riwayat-donasi/rating/{id}', [RiwayatDonationController::class, 'updateRating'])->name('riwayat-donasi.update-rating'); 

    // ===== FITUR BARU: TIPS & KOMUNITAS =====
    Route::get('/tips', [TipsController::class, 'index'])->name('tips.index');
    Route::post('/tips/proses', [TipsController::class, 'prosesPembayaran'])->name('tips.proses');
    Route::get('/komunitas/{id}', function ($id) {
        return view('komunitas-detail', ['post' => Komunitas::findOrFail($id)]);
    })->name('komunitas.detail');

    // ===== FITUR BARU: CHAT =====
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

        // --- DASHBOARD ADMIN ---
        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            
            $query = KegiatanDonasi::query();
            
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
        Route::get('/donasi/tambah', function () { 
            // Ambil semua data lokasi dropbox dari database
            $dropboxes = \App\Models\DropBox::all(); 
            
            // Kirim variabel $dropboxes ke view
            return view('admin.create', compact('dropboxes')); 
        })->name('donasi.create');

        Route::post('/donasi/tambah', function (Request $request) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('donasi', 'public') : 'donasi/default.jpg';

            KegiatanDonasi::create([
                'judul_donasi' => $request->judul, 
                'kategori_penerima' => $request->kategori, 
                'tanggal_kegiatan' => $request->tanggal,
                'foto_kegiatan' => $fotoPath, 
                'deskripsi' => $request->deskripsi, 
                'alamat_penyaluran' => $request->alamat, 
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Berhasil ditambahkan!');
        })->name('donasi.store');

        Route::post('/donasi/update-data/{id}', function (Request $request, $id) {
            $donasi = KegiatanDonasi::findOrFail($id);

            if ($request->hasFile('foto')) {
                if ($donasi->foto_kegiatan && $donasi->foto_kegiatan !== 'donasi/default.jpg') {
                    Storage::disk('public')->delete($donasi->foto_kegiatan);
                }
                $fotoPath = $request->file('foto')->store('donasi', 'public');
            } else {
                $fotoPath = $donasi->foto_kegiatan;
            }

            $donasi->update([
                'judul_donasi' => $request->judul,
                'kategori_penerima' => $request->kategori,
                'tanggal_kegiatan' => $request->tanggal,
                'foto_kegiatan' => $fotoPath,
                'deskripsi' => $request->deskripsi,
                'alamat_penyaluran' => $request->alamat,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil diperbarui!');
        })->name('donasi.update');

        Route::get('/donasi/detail/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $data = KegiatanDonasi::findOrFail($id);
            return view('admin.detail-donasi', compact('data'));
        })->name('donasi.detail');

        Route::get('/donasi/edit/{id}', function ($id) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $data = KegiatanDonasi::findOrFail($id);
            return view('admin.edit-donasi', compact('data'));
        })->name('donasi.edit');

        Route::post('/donasi/hapus/{id}', function ($id) {
            $donasi = KegiatanDonasi::findOrFail($id);
            if ($donasi->foto_kegiatan && $donasi->foto_kegiatan !== 'donasi/default.jpg') { Storage::disk('public')->delete($donasi->foto_kegiatan); }
            $donasi->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
        })->name('donasi.delete');

        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('retur.store');
        //Penugasan Relawan
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::get('/penugasan/create', [PenugasanController::class, 'create'])->name('penugasan.create');
        Route::post('/penugasan/store', [PenugasanController::class, 'store'])->name('penugasan.store');
        Route::get('/penugasan/{id}/edit', [PenugasanController::class, 'edit'])->name('penugasan.edit');
        Route::put('/penugasan/{id}', [PenugasanController::class, 'update'])->name('penugasan.update');
        Route::delete('/penugasan/{id}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });

    // ==========================================
    // GRUP ROUTE TANPA PREFIX NAMA ADMIN
    // ==========================================
    Route::prefix('admin')->group(function () {
        
        // ===== FITUR KERJASAMA MITRA =====
        Route::get('/kerjasama-mitra', function (Request $request) {
            if (!session()->has('mitra_data')) {
                session()->put('mitra_data', [
                    ['id' => 1, 'nama_mitra' => 'Restoran Sederhana', 'status' => 'aktif', 'kategori' => 'Restoran', 'lokasi' => 'Jakarta Selatan', 'keterangan_waktu' => 'Bergabung Jan 2025', 'total_donasi' => '142', 'porsi_tersalur' => '1.2k', 'logo' => null, 'deskripsi' => 'Restoran Sederhana merupakan mitra kuliner berjenis Restoran yang berlokasi di Jakarta Selatan. Mitra ini berkomitmen penuh untuk mendukung program FoodLink dalam mendistribusikan makanan layak konsumsi guna mengurangi food waste dan membantu masyarakat sekitar.'],
                    ['id' => 2, 'nama_mitra' => 'Yayasan Peduli Pangan', 'status' => 'pengajuan', 'kategori' => 'NGO', 'lokasi' => 'Jakarta Timur', 'keterangan_waktu' => 'Diajukan 20 Mar 2026', 'total_donasi' => 0, 'porsi_tersalur' => 0, 'logo' => null, 'deskripsi' => 'Yayasan Peduli Pangan merupakan lembaga swadaya masyarakat berjenis NGO yang berfokus di Jakarta Timur. Bermitra dengan FoodLink, yayasan ini aktif bergerak dalam pengelolaan sisa makanan secara higienis untuk disalurkan kepada pihak yang membutuhkan.'],
                    ['id' => 3, 'nama_mitra' => 'Kantin Kampus UI', 'status' => 'tidak_aktif', 'kategori' => 'Kantin', 'lokasi' => 'Depok', 'keterangan_waktu' => 'Terakhir aktif Nov 2025', 'total_donasi' => '23', 'porsi_tersalur' => '180', 'logo' => null, 'deskripsi' => 'Kantin Kampus UI merupakan area kuliner berjenis Kantin yang terletak di Depok. Melalui kolaborasi bersama FoodLink, para pelaku usaha di kantin ini ikut berkontribusi nyata dalam mendonasikan surplus makanan layak makan bagi lingkungan sekitar.'],
                ]);
            }
            $allMitras = collect(session('mitra_data'))->map(function($item) { return (object) $item; });
            $status = $request->query('status');
            $kategori = $request->query('kategori');
            $search = $request->query('search');
            $mitras = $allMitras;
            
            if ($status) $mitras = $mitras->where('status', $status);
            if ($kategori) $mitras = $mitras->where('kategori', $kategori);
            if ($search) {
                $mitras = $mitras->filter(function($item) use ($search) { return stripos($item->nama_mitra, $search) !== false || stripos($item->lokasi, $search) !== false || stripos($item->kategori, $search) !== false; });
            }
            
            return view('admin.kerjasamamitra', [
                'mitras' => $mitras,
                'totalMitra' => $allMitras->count(), 
                'mitraAktif' => $allMitras->where('status', 'aktif')->count(),
                'mitraPengajuan' => $allMitras->where('status', 'pengajuan')->count()
            ]);
        })->name('mitra.index');

        Route::post('/kerjasama-mitra/store', function (Request $request) {
            $mitras = session('mitra_data', []);
            $newId = count($mitras) > 0 ? max(array_column($mitras, 'id')) + 1 : 1;
            $nama = $request->input('nama_mitra');
            $kategori = $request->input('kategori');
            $lokasi = $request->input('lokasi');
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

        // ===== FITUR DROP BOX (SUDAH TERHUBUNG DATABASE) =====
        Route::get('/drop-box', function (Request $request) {
            date_default_timezone_set('Asia/Jakarta');
            
            // Mengambil data dari tabel drop_boxes
            $dropboxes = \App\Models\DropBox::all();
            $now = time();
            
            foreach ($dropboxes as $box) {
                if ($box->active_task) {
                    $task = $box->active_task;
                    $updated = false;

                    if ($now >= $task['waktu_selesai']) {
                        $box->keterangan_status = 'Selesai mengantar';
                        $box->active_task = null; 
                        $updated = true;
                    } elseif ($now >= $task['waktu_sampai_dropbox']) {
                        $box->keterangan_status = 'Barang sudah dijemput dan sedang menuju alamat pengantaran';
                        $updated = true;
                    } else {
                        $box->keterangan_status = 'Kurir ' . $task['petugas'] . ' sedang menjemput barang';
                        $updated = true;
                    }

                    if ($updated) {
                        $box->save(); 
                    }
                }
            }
            
            $totalLokasi = $dropboxes->count();
            $tersedia = $dropboxes->where('status', 'tersedia')->count();
            $hampirPenuh = $dropboxes->where('status', 'hampir_penuh')->count();
            $penuh = $dropboxes->where('status', 'penuh')->count();
            
            return view('admin.dropbox', compact('dropboxes', 'totalLokasi', 'tersedia', 'hampirPenuh', 'penuh'));
        })->name('dropbox.index');

        Route::post('/drop-box/store', function (Request $request) {
            $nama = $request->input('nama');
            $lokasiDetail = $request->input('lokasi');

            $lat = -6.2088; 
            $lng = 106.8456; 
            $namaLokasiBersih = str_ireplace(['Drop Box ', 'Dropbox ', 'Drop ', 'Box '], '', strtolower($nama));

            $daftarLokasi = [
                'monas' => [-6.1754, 106.8272], 'cempaka putih' => [-6.1825, 106.8718],
                'tebet' => [-6.2260, 106.8580], 'menteng' => [-6.1950, 106.8321],
                'ragunan' => [-6.3039, 106.8267], 'ancol' => [-6.1244, 106.8335],
                'senayan' => [-6.2185, 106.8021], 'pancoran' => [-6.2514, 106.8451],
                'cipete' => [-6.2778, 106.8000]
            ];

            foreach ($daftarLokasi as $key => $coords) {
                if (strpos($namaLokasiBersih, $key) !== false) {
                    $lat = $coords[0]; $lng = $coords[1]; break;
                }
            }

            if ($lat == -6.2088) {
                try {
                    $queryCari = trim($namaLokasiBersih) . ', Jakarta, Indonesia'; 
                    $response = Http::withoutVerifying()->withHeaders(['User-Agent' => 'FoodLink-App/1.0'])->timeout(5)
                        ->get('https://nominatim.openstreetmap.org/search', ['format' => 'json', 'q' => $queryCari, 'limit' => 1]);

                    if ($response->successful()) {
                        $data = $response->json();
                        if (!empty($data) && isset($data[0])) {
                            $lat = (float) $data[0]['lat']; $lng = (float) $data[0]['lon'];
                        }
                    }
                } catch (\Exception $e) {}
            }
            
            \App\Models\DropBox::create([
                'nama' => $nama,
                'status' => 'tersedia',
                'lokasi' => $lokasiDetail, 
                'mitra' => $request->input('mitra'), 
                'kapasitas' => rand(2, 10) . '/20', 
                'keterangan_status' => 'Baru saja ditambahkan', 
                'lat' => $lat, 
                'lng' => $lng, 
                'history' => [] 
            ]);
            
            return redirect()->route('dropbox.index');
        })->name('dropbox.store');

        Route::post('/drop-box/{id}/jemput', function (Request $request, $id) {
            $box = \App\Models\DropBox::findOrFail($id);
            
            $petugas = $request->input('petugas');
            $waktuJemput = $request->input('waktu'); 
            $latGudang = -6.1754; 
            $lngGudang = 106.8272; 

            $waktuMulaiAnimasi = time();
            $durasiPerRute = 60; 

            $box->active_task = [
                'petugas' => $petugas,
                'waktu_mulai' => $waktuMulaiAnimasi,
                'waktu_sampai_dropbox' => $waktuMulaiAnimasi + $durasiPerRute,
                'waktu_selesai' => $waktuMulaiAnimasi + ($durasiPerRute * 2),
                'lat_gudang' => $latGudang,
                'lng_gudang' => $lngGudang,
                'lat_dropbox' => $box->lat,
                'lng_dropbox' => $box->lng,
            ];

            $tanggal = date('d M Y');
            $estimasiSelesai = date('H:i', $waktuMulaiAnimasi + ($durasiPerRute * 2));
            $keteranganHistory = "<span style='font-size: 11px; color: #718096;'>{$tanggal} &bull; {$waktuJemput} - {$estimasiSelesai} WIB</span><br/>Relawan <b>{$petugas}</b> menjemput barang dari {$box->nama} dan mengantarnya ke Gudang Pusat FoodLink.";
            
            $historyLama = $box->history ?? [];
            array_unshift($historyLama, $keteranganHistory);
            
            $box->history = $historyLama;
            $box->status = 'tersedia';
            $box->kapasitas = '0/20';
            $box->keterangan_status = 'Kurir ' . $petugas . ' sedang menjemput barang';
            
            $box->save(); 
            
            // ==========================================
            // KODE BARU: PELACAK ERROR DATABASE PENUGASAN
            // ==========================================
            try {
                \App\Models\Penugasan::create([
                    'id_penugasan' => 'TGS-' . rand(1000, 9999), 
                    'id_donasi' => $box->id, // Murni angka, aman untuk integer
                    'nama_donatur' => $box->mitra, 
                    'relawan' => $petugas, 
                    'lokasi_pengambilan' => $box->nama . ' (' . $box->lokasi . ')', 
                    'lokasi_pengantaran' => 'Gudang Pusat FoodLink',
                    'tanggal_penugasan' => date('Y-m-d') . ' ' . $waktuJemput . ':00',
                ]);
            } catch (\Exception $e) {
                // Jika databasenya menolak, layar akan menampilkan pesan ini
                dd("GAGAL MENYIMPAN KE DATABASE PENUGASAN! Ini pesan error dari MySQL: " . $e->getMessage());
            }
            
            return redirect()->route('dropbox.index');
        })->name('dropbox.jemput');
    });

    // ================= PROFIL USER =================
    Route::get('/profil', function () {
        if (Auth::user()->role === 'admin') { return redirect()->route('admin.dashboard'); }
        return view('profil'); 
    })->name('profil.index'); 

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

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui!');
    })->name('profil.update');

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});