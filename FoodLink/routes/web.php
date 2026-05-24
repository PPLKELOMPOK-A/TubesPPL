<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Donation;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuktiDonasiController;
use App\Http\Controllers\ReturDonasiController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ValidasiProsesDonasiController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DonasiMakananController;
use App\Http\Controllers\KegiatanDonasiController;

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

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', function (Request $request) {
            if (Auth::user()->role !== 'admin') { return redirect()->route('dashboard'); }
            $semuaDonasi = Donation::orderBy('created_at', 'desc')->get();
            return view('admin.dashboardAdmin', compact('semuaDonasi'));
        })->name('dashboard');

        Route::get('/kegiatan/baru', [KegiatanDonasiController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan/simpan', [KegiatanDonasiController::class, 'store'])->name('kegiatan.store');

        // FITUR VALIDASI PROSES DONASI (Fokus Utama PR Kamu)
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
    // Sengaja dipisah dari grup 'admin.' temanmu agar nama rutenya tetap mitra.index
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
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});