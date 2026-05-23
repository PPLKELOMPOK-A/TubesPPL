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
        
        // =========================================================
        // FITUR KERJA SAMA MITRA
        // =========================================================
        
        Route::get('/kerjasama-mitra', function (Request $request) {
            $defaultMitras = collect([
                (object)['id' => 1, 'nama_mitra' => 'Restoran Sederhana', 'kategori' => 'Restoran', 'lokasi' => 'Jakarta Selatan', 'status' => 'aktif', 'keterangan_waktu' => 'Bergabung Jan 2025', 'total_donasi' => '142', 'porsi_tersalur' => '1.2k', 'deskripsi' => 'Restoran Sederhana menyajikan berbagai masakan Padang autentik. Berkomitmen penuh untuk tidak membuang sisa makanan layak konsumsi setiap harinya.'],
                (object)['id' => 2, 'nama_mitra' => 'Yayasan Peduli Pangan', 'kategori' => 'NGO', 'lokasi' => 'Jakarta Timur', 'status' => 'pengajuan', 'keterangan_waktu' => 'Diajukan 20 Mar 2026', 'total_donasi' => 0, 'porsi_tersalur' => 0, 'deskripsi' => 'NGO lokal yang berfokus pada distribusi makanan gratis untuk panti asuhan di area Jakarta Timur.'],
                (object)['id' => 3, 'nama_mitra' => 'Kantin Kampus UI', 'kategori' => 'Kantin', 'lokasi' => 'Depok', 'status' => 'tidak_aktif', 'keterangan_waktu' => 'Terakhir aktif Nov 2025', 'total_donasi' => '23', 'porsi_tersalur' => '180', 'deskripsi' => 'Kumpulan tenant di kantin kampus yang secara rutin mendonasikan bahan baku berlebih sebelum masa expired.'],
            ]);

            if (!session()->has('mitras_data_v6')) {
                session()->put('mitras_data_v6', $defaultMitras->all());
            }

            $allMitras = collect(session()->get('mitras_data_v6'));
            $mitras = $allMitras;

            if ($request->filled('kategori')) { $mitras = $mitras->where('kategori', $request->kategori); }
            if ($request->filled('status')) { $mitras = $mitras->where('status', $request->status); }
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $mitras = $mitras->filter(fn($item) => str_contains(strtolower($item->nama_mitra), $search));
            }

            $totalMitra = $allMitras->count();
            $mitraAktif = $allMitras->where('status', 'aktif')->count();
            $mitraPengajuan = $allMitras->where('status', 'pengajuan')->count();

            return view('admin.kerjasamamitra', compact('totalMitra', 'mitraAktif', 'mitraPengajuan', 'mitras'));
        })->name('mitra.index');

        Route::post('/kerjasama-mitra/store', function (Request $request) {
            $mitras = collect(session()->get('mitras_data_v6', []));
            
            $mitras->push((object)[
                'id' => time(), 
                'nama_mitra' => $request->nama_mitra, 
                'kategori' => $request->kategori,
                'lokasi' => $request->lokasi, 
                'status' => 'pengajuan', 
                'keterangan_waktu' => 'Baru saja',
                'total_donasi' => 0, 
                'porsi_tersalur' => 0,
                'deskripsi' => 'Mitra ini bergerak di bidang ' . $request->kategori . ' dan berlokasi di ' . $request->lokasi . '. Sedang menunggu proses verifikasi oleh Admin.'
            ]);
            
            session()->put('mitras_data_v6', $mitras->all());
            return redirect()->route('admin.mitra.index');
        })->name('mitra.store');

        Route::patch('/kerjasama-mitra/{id}/status', function (Request $request, $id) {
            $mitras = collect(session()->get('mitras_data_v6', []));
            
            if ($request->status === 'ditolak') {
                $mitras = $mitras->reject(function($item) use ($id) {
                    return $item->id == $id;
                });
            } else {
                $mitras = $mitras->map(function($item) use ($id, $request) {
                    if ($item->id == $id) { 
                        $item->status = $request->status; 
                        
                        if ($request->status == 'aktif') {
                            $item->keterangan_waktu = 'Baru saja bergabung';
                            $item->deskripsi = 'Mitra ini bergerak di bidang ' . $item->kategori . ' dan berlokasi di ' . $item->lokasi . '. Telah resmi bergabung dan siap mendistribusikan donasi.';
                        }
                    }
                    return $item;
                });
            }
            
            session()->put('mitras_data_v6', $mitras->all());
            return back();
        })->name('mitra.updateStatus');

        // =========================================================
        // FITUR DROP BOX
        // =========================================================
        Route::get('/dropbox', function (Request $request) {
            $dropboxes = collect([
                (object)[
                    'id' => 1, 'nama' => 'Drop Box Sudirman', 'lokasi' => 'Lobby Gedung Sudirman Tower', 
                    'mitra' => 'Sudirman Tower', 'status' => 'tersedia', 'kapasitas' => 30, 'update' => 'Hari ini, 08:30'
                ],
                (object)[
                    'id' => 2, 'nama' => 'Drop Box Matraman', 'lokasi' => 'Minimarket Segar, Jl. Matraman Raya', 
                    'mitra' => 'Toko Segar', 'status' => 'hampir_penuh', 'kapasitas' => 78, 'update' => 'Hari ini, 10:15'
                ],
                (object)[
                    'id' => 3, 'nama' => 'Drop Box Cempaka Putih', 'lokasi' => 'Puskesmas Cempaka Putih, Jak-Pus', 
                    'mitra' => 'Puskesmas CP', 'status' => 'penuh', 'kapasitas' => 100, 'update' => 'Hari ini, 09:00'
                ],
            ]);

            $totalLokasi = 8;
            $tersedia = 5;
            $hampirPenuh = 2;
            $penuh = 1;

            // PERBAIKAN: Menambahkan awalan admin. pada pemanggilan view
            return view('admin.DropBox', compact('dropboxes', 'totalLokasi', 'tersedia', 'hampirPenuh', 'penuh'));
        })->name('dropbox.index');

    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});