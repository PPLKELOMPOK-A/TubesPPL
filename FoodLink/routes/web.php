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

        $donations = Donation::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

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

        // 🔒 OPTIONAL (kalau nanti mau role admin)
        // ->middleware('is_admin')

        // ===== VALIDASI DONASI =====
        Route::prefix('validasi-proses-donasi')->group(function () {

            Route::get('/', [ValidasiProsesDonasiController::class, 'index'])
                ->name('validasi.index');

            Route::post('/{id}/setujui', [ValidasiProsesDonasiController::class, 'setujui'])
                ->name('validasi.setujui');

            Route::post('/{id}/tolak', [ValidasiProsesDonasiController::class, 'tolak'])
                ->name('validasi.tolak');

            Route::post('/{id}/return', [ValidasiProsesDonasiController::class, 'returnDonasi'])
                ->name('validasi.return');

            Route::get('/disetujui', [ValidasiProsesDonasiController::class, 'disetujui'])
                ->name('validasi.disetujui');

            Route::get('/ditolak', [ValidasiProsesDonasiController::class, 'ditolak'])
                ->name('validasi.ditolak');
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

            $data = $request->only([
                'judul',
                'kategori',
                'tanggal',
                'deskripsi',
                'alamat'
            ]);

            Donation::updateOrCreate(['id' => 1], $data);

            return redirect()->route('admin.donasi.detail')
                ->with('success', 'Donasi berhasil diupdate');

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
            // 1. Simpan data awal ke Session (Memori Sementara) jika belum ada
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
                    ],
                ]);
            }

            // Ambil data dari Session dan ubah formatnya ke Object Collection
            $allMitras = collect(session('mitra_data'))->map(function($item) {
                return (object) $item;
            });

            // 2. MENDAPATKAN SEMUA FILTER
            $status = $request->query('status');
            $kategori = $request->query('kategori');
            $search = $request->query('search');

            $mitras = $allMitras;

            // Logika Filter Status
            if ($status) {
                $mitras = $mitras->where('status', $status);
            }

            // Logika Filter Kategori (Restoran, Toko, NGO, Kantin)
            if ($kategori) {
                $mitras = $mitras->where('kategori', $kategori);
            }

            // Logika Filter Search (Mencari berdasarkan Nama atau Lokasi)
            if ($search) {
                $mitras = $mitras->filter(function($item) use ($search) {
                    return stripos($item->nama_mitra, $search) !== false 
                        || stripos($item->lokasi, $search) !== false
                        || stripos($item->kategori, $search) !== false;
                });
            }

            // Hitung Angka Statistik di Kotak Atas secara Otomatis dari Total Semua Data
            $totalMitra = $allMitras->count();
            $mitraAktif = $allMitras->where('status', 'aktif')->count();
            $mitraPengajuan = $allMitras->where('status', 'pengajuan')->count();

            // 3. Kirim data ke View kerjasamamitra.blade.php
            return view('kerjasamamitra', [
                'mitras' => $mitras,
                'totalMitra' => $totalMitra, 
                'mitraAktif' => $mitraAktif,
                'mitraPengajuan' => $mitraPengajuan
            ]);
        })->name('mitra.index');

        // Route untuk MENYIMPAN Mitra Baru dari Modal
        Route::post('/kerjasama-mitra/store', function (Request $request) {
            $mitras = session('mitra_data', []);
            
            // Generate ID baru (cari ID paling besar + 1)
            $newId = count($mitras) > 0 ? max(array_column($mitras, 'id')) + 1 : 1;
            
            // Format data baru
            $newMitra = [
                'id' => $newId,
                'nama_mitra' => $request->input('nama_mitra'),
                'status' => 'pengajuan', // Otomatis masuk pengajuan
                'kategori' => $request->input('kategori'),
                'lokasi' => $request->input('lokasi'),
                'keterangan_waktu' => 'Diajukan ' . date('d M Y'),
                'total_donasi' => 0,
                'porsi_tersalur' => 0,
                'logo' => null,
            ];
            
            // Tambahkan ke array dan simpan kembali ke session
            $mitras[] = $newMitra;
            session()->put('mitra_data', $mitras);
            
            // Redirect langsung ke tab Pengajuan Baru
            return redirect()->route('mitra.index', ['status' => 'pengajuan']);
        })->name('mitra.store');

        // Route untuk memproses Tombol Setujui, Tolak, dan Aktifkan
        Route::patch('/kerjasama-mitra/{id}/status', function (Request $request, $id) {
            $statusBaru = $request->input('status');
            $mitras = session('mitra_data', []);
            
            if ($statusBaru === 'ditolak') {
                // JIKA DITOLAK: Hapus data dari array/memori secara permanen
                $mitras = array_filter($mitras, function($m) use ($id) {
                    return $m['id'] != $id;
                });
                // Reset index array
                $mitras = array_values($mitras);
                session()->put('mitra_data', $mitras);
                
                // Kembalikan ke tab pengajuan
                return redirect()->route('mitra.index', ['status' => 'pengajuan']);
            }
            
            // JIKA DISETUJUI / DIAKTIFKAN: Update statusnya
            foreach ($mitras as $key => $mitra) {
                if ($mitra['id'] == $id) {
                    $mitras[$key]['status'] = $statusBaru;
                    break;
                }
            }
            session()->put('mitra_data', $mitras);
            
            // Pindah ke tab yang sesuai dengan status baru
            return redirect()->route('mitra.index', ['status' => $statusBaru]);
        })->name('mitra.updateStatus');

        // Route untuk Lihat Profile Mitra (DESAIN BARU)
        Route::get('/kerjasama-mitra/{id}/profile', function ($id) {
            $mitras = collect(session('mitra_data', []));
            $mitra = $mitras->firstWhere('id', $id);
            
            if (!$mitra) {
                return "<div style='text-align:center; padding: 50px; font-family: sans-serif;'><h2>Data Mitra tidak ditemukan.</h2><a href='".route('mitra.index')."'>Kembali</a></div>";
            }
            
            // Logika Warna Badge Status
            $badgeColor = '#9AE6B4'; // Hijau (Aktif)
            $badgeText = '#FFFFFF';
            if($mitra['status'] == 'pengajuan') {
                $badgeColor = '#FDF4E3'; // Krem
                $badgeText = '#B08933';
            } elseif ($mitra['status'] == 'tidak_aktif') {
                $badgeColor = '#FFF4E0'; // Orange Muda
                $badgeText = '#F6AD55';
            } elseif ($mitra['status'] == 'ditolak') {
                $badgeColor = '#FEB2B2'; // Merah Muda
                $badgeText = '#FFFFFF';
            }

            // Return tampilan HTML lengkap menggunakan Tailwind CDN
            return "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Profile Mitra - " . $mitra['nama_mitra'] . "</title>
                <script src='https://cdn.tailwindcss.com'></script>
                <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap' rel='stylesheet'>
                <style>body { font-family: 'Inter', sans-serif; }</style>
            </head>
            <body class='bg-gray-50 flex items-center justify-center min-h-screen p-4'>
                <div class='w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100'>
                    
                    <!-- HEADER AREA (Krem) -->
                    <div class='bg-[#FBEBCE] h-40 relative flex items-center justify-center'>
                        <div class='absolute -bottom-12 w-28 h-28 bg-white rounded-full p-2 shadow-lg'>
                            <div class='w-full h-full bg-[#FDF4E3] rounded-full border border-[#E47F3D] flex items-center justify-center'>
                                <svg class='w-10 h-10 text-[#D9A74A]' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- KONTEN UTAMA -->
                    <div class='pt-16 pb-8 px-8 text-center'>
                        <div class='flex items-center justify-center gap-3 mb-2'>
                            <h1 class='text-3xl font-black text-gray-800'>" . $mitra['nama_mitra'] . "</h1>
                            <span style='background-color: " . $badgeColor . "; color: " . $badgeText . ";' class='text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider'>" . $mitra['status'] . "</span>
                        </div>
                        <p class='text-sm text-gray-500 font-medium mb-6'>" . $mitra['kategori'] . " · " . $mitra['lokasi'] . " · " . $mitra['keterangan_waktu'] . "</p>

                        <!-- STATISTIK DONASI -->
                        <div class='flex justify-center gap-6 mb-10'>
                            <div class='bg-[#FFF9F0] px-8 py-4 rounded-2xl border border-[#FDF4E3]'>
                                <p class='text-2xl font-bold text-[#D9A74A]'>" . $mitra['total_donasi'] . "</p>
                                <p class='text-xs font-bold text-gray-400 uppercase tracking-wide'>Total Donasi</p>
                            </div>
                            <div class='bg-[#FFF9F0] px-8 py-4 rounded-2xl border border-[#FDF4E3]'>
                                <p class='text-2xl font-bold text-[#D9A74A]'>" . $mitra['porsi_tersalur'] . "</p>
                                <p class='text-xs font-bold text-gray-400 uppercase tracking-wide'>Porsi Tersalur</p>
                            </div>
                        </div>

                        <!-- DESKRIPSI SINGKAT -->
                        <div class='text-left bg-gray-50 p-6 rounded-2xl mb-8 border border-gray-100'>
                            <h3 class='text-sm font-bold text-gray-700 mb-2'>Informasi Mitra</h3>
                            <p class='text-sm text-gray-600 leading-relaxed'>Mitra ini telah berkomitmen untuk membantu menyalurkan makanan berlebih kepada mereka yang membutuhkan melalui platform FoodLink. Kerjasama ini diharapkan dapat mengurangi food waste sekaligus membantu masalah sosial.</p>
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class='flex justify-center gap-4'>
                            <a href='" . route('mitra.index') . "' class='px-8 py-3 bg-white border-2 border-gray-200 text-gray-600 font-bold rounded-xl text-sm hover:bg-gray-50 transition shadow-sm'>
                                Kembali
                            </a>
                            <a href='https://wa.me/6281584844763?text=Halo%20" . urlencode($mitra['nama_mitra']) . ",%20kami%20dari%20Admin%20FoodLink...' target='_blank' class='px-8 py-3 bg-[#4A5568] text-white font-bold rounded-xl text-sm hover:bg-gray-600 transition shadow-sm flex items-center gap-2'>
                                <svg class='w-4 h-4' fill='currentColor' viewBox='0 0 24 24'><path d='M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'/></svg>
                                Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
        })->name('mitra.show');

    });
});