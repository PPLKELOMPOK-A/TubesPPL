<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\PenugasanController;

use App\Models\Donation;

// ================== HOME ==================
Route::get('/', fn() => view('welcome'));

// ================== GUEST ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ================== AUTH ==================
Route::middleware('auth')->group(function () {

    // ================== USER ==================
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();
        $donations = Donation::where('user_id', $user->id)->latest()->paginate(10);

        return view('dashboard', [
            'donations' => $donations,
            'totalDonations' => $donations->count(),
            'sentDonations' => $donations->where('status', 'terkirim')->count(),
            'inTransitDonations' => $donations->where('status', 'dalam_perjalanan')->count(),
        ]);
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ================== TRACKING ==================
    Route::get('/tracking', [DonationController::class, 'index'])->name('donation.tracking');
    Route::get('/tracking-detail', fn() => view('trackingdetail'))->name('tracking.detail');

    // ================== BUKTI DONASI ==================
    Route::get('/bukti-donasi', [BuktiDonasiController::class, 'index'])->name('bukti-donasi.index');
    Route::get('/bukti-donasi/{id}', [BuktiDonasiController::class, 'show'])->name('bukti-donasi.show');

    // ================== ADMIN ==================
    Route::prefix('admin')->group(function () {

        // ===== VALIDASI DONASI =====
        Route::prefix('validasi-proses-donasi')->group(function () {
            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])->name('validasi.index');
            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])->name('validasi.setujui');
            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])->name('validasi.tolak');
            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])->name('validasi.return');
            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'disetujui'])->name('validasi.disetujui');
            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'ditolak'])->name('validasi.ditolak');
        });

        // ===== DASHBOARD ADMIN =====
        Route::get('/dashboard', function () {
            $donations = Donation::latest()->get();
            return view('admin.dashboard', compact('donations'));
        })->name('admin.dashboard');

        // ===== DETAIL DONASI =====
        Route::get('/donasi/detail', function (Request $request) {
            $data = [
                'judul'     => $request->query('judul'),
                'kategori'  => $request->query('org'),
                'tanggal'   => $request->query('tgl'),
                'deskripsi' => $request->query('desc'),
                'alamat'    => $request->query('alamat'),
                'foto'      => $request->query('img_raw'),
            ];
            return view('admin.detail-donasi', compact('data'));
        })->name('admin.donasi.detail');

        // ===== EDIT DONASI =====
        Route::get('/donasi/edit', function () {
            $data = session('donasi_data', [
                'judul'     => 'Contoh Donasi',
                'kategori'  => 'Organisasi',
                'tanggal'   => now()->format('Y-m-d'),
                'foto'      => null,
                'deskripsi' => 'Deskripsi donasi',
                'alamat'    => 'Alamat donasi'
            ]);
            return view('admin.edit-donasi', compact('data'));
        })->name('admin.donasi.edit');

        // ===== UPDATE DONASI =====
        Route::post('/donasi/edit', function (Request $request) {
            $data = $request->only(['judul', 'kategori', 'tanggal', 'deskripsi', 'alamat']);
            Donation::updateOrCreate(['id' => 1], $data);
            return redirect()->route('admin.donasi.detail')->with('success', 'Donasi berhasil diupdate');
        })->name('admin.donasi.update');

        // ===== RETUR DONASI =====
        Route::get('/retur-donasi', [ReturDonasiController::class, 'index'])->name('admin.retur.index');
        Route::post('/retur-donasi', [ReturDonasiController::class, 'store'])->name('admin.retur.store');

        // ===== PENUGASAN =====
        Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
        Route::post('/penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');
        Route::delete('/penugasan/{id}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');
        Route::get('/penugasan/edit/{id}', [PenugasanController::class, 'edit'])->name('penugasan.edit');
        Route::put('/penugasan/{id}', [PenugasanController::class, 'update'])->name('penugasan.update');

        // ===== KERJA SAMA MITRA =====
        Route::get('/kerjasama-mitra', function (Request $request) {
            if (!session()->has('mitra_data')) {
                session()->put('mitra_data', [
                    [
                        'id' => 1, 
                        'nama_mitra' => 'Restoran Sederhana', 
                        'status' => 'aktif', 
                        'kategori' => 'Restoran', 
                        'lokasi' => 'Jakarta Selatan', 
                        'keterangan_waktu' => 'Bergabung Jan 2025', 
                        'total_donasi' => '142', 
                        'porsi_tersalur' => '1.2k', 
                        'logo' => null,
                        'deskripsi' => 'Restoran Sederhana merupakan mitra kuliner berjenis Restoran yang berlokasi di Jakarta Selatan. Mitra ini berkomitmen penuh untuk mendukung program FoodLink dalam mendistribusikan makanan layak konsumsi guna mengurangi food waste dan membantu masyarakat sekitar.'
                    ],
                    [
                        'id' => 2, 
                        'nama_mitra' => 'Yayasan Peduli Pangan', 
                        'status' => 'pengajuan', 
                        'kategori' => 'NGO', 
                        'lokasi' => 'Jakarta Timur', 
                        'keterangan_waktu' => 'Diajukan 20 Mar 2026', 
                        'total_donasi' => 0, 
                        'porsi_tersalur' => 0, 
                        'logo' => null,
                        'deskripsi' => 'Yayasan Peduli Pangan merupakan lembaga swadaya masyarakat berjenis NGO yang berfokus di Jakarta Timur. Bermitra dengan FoodLink, yayasan ini aktif bergerak dalam pengelolaan sisa makanan secara higienis untuk disalurkan kepada pihak yang membutuhkan.'
                    ],
                    [
                        'id' => 3, 
                        'nama_mitra' => 'Kantin Kampus UI', 
                        'status' => 'tidak_aktif', 
                        'kategori' => 'Kantin', 
                        'lokasi' => 'Depok', 
                        'keterangan_waktu' => 'Terakhir aktif Nov 2025', 
                        'total_donasi' => '23', 
                        'porsi_tersalur' => '180', 
                        'logo' => null,
                        'deskripsi' => 'Kantin Kampus UI merupakan area kuliner berjenis Kantin yang terletak di Depok. Melalui kolaborasi bersama FoodLink, para pelaku usaha di kantin ini ikut berkontribusi nyata dalam mendonasikan surplus makanan layak makan bagi lingkungan sekitar.'
                    ],
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
                $mitras = $mitras->filter(function($item) use ($search) {
                    return stripos($item->nama_mitra, $search) !== false || stripos($item->lokasi, $search) !== false || stripos($item->kategori, $search) !== false;
                });
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
            
            // Format kalimat deskripsi umum dan dinamis untuk mitra baru
            $deskripsiDinamis = "{$nama} merupakan mitra kerja sama berjenis {$kategori} yang beroperasi di wilayah {$lokasi}. Mitra ini berkomitmen penuh untuk bersinergi bersama platform FoodLink dalam mengelola surplus makanan layak guna menyebarkan dampak sosial positif bagi masyarakat sekitar.";

            $newMitra = [
                'id' => $newId, 
                'nama_mitra' => $nama, 
                'status' => 'pengajuan', 
                'kategori' => $kategori, 
                'lokasi' => $lokasi, 
                'keterangan_waktu' => 'Diajukan ' . date('d M Y'), 
                'total_donasi' => 0, 
                'porsi_tersalur' => 0, 
                'logo' => null,
                'deskripsi' => $deskripsiDinamis
            ];
            
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

    });
});