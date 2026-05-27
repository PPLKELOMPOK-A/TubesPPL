<?php

// app/Http/Controllers/DonasiMakananController.php

namespace App\Http\Controllers;

use App\Models\DonasiMakanan;
use App\Models\User; // <-- TAMBAHKAN INI UNTUK MENCARI ADMIN
use App\Notifications\SistemNotifikasi; // <-- TAMBAHKAN INI UNTUK MENGIRIM NOTIFIKASI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonasiMakananController extends Controller
{
    // Menampilkan form Blade
    public function create()
    {
        return view('donasi.create'); 
    }

    // Memproses data form
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'nama_donatur'      => 'required|string|max:255',
            'no_telp'           => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'kategori_penerima' => 'required|string',
            'kategori_wilayah'  => 'required|string',
            'lokasi_dropbox'    => 'required|string',
            'kategori_makanan'  => 'required|string',
            'waktu_layak'       => 'required|string',
            'deskripsi'         => 'nullable|string',
            'foto_makanan'      => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', 
        ]);

        // 2. Proses upload foto
        if ($request->hasFile('foto_makanan')) {
            $path = $request->file('foto_makanan')->store('donasi_foto', 'public');
            $validatedData['foto_makanan'] = $path;
        }

        // 3. Simpan data ke database
        DonasiMakanan::create($validatedData);

        /* ========================================================
           4. PROSES PENGIRIMAN NOTIFIKASI KE ADMIN
           ======================================================== */
        
        // Cari semua akun yang memiliki role 'admin'
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new SistemNotifikasi(
                'Donasi Baru Masuk', 
                'Ada donasi makanan baru yang memerlukan verifikasi dan kurasi dari tim admin gudang pusat.', 
                'donasi',
                [
                    'Nama Donatur'      => $validatedData['nama_donatur'],
                    'No. Telepon'       => $validatedData['no_telp'],
                    'Email'             => $validatedData['email'],
                    'Kategori Makanan'  => $validatedData['kategori_makanan'],
                    'Waktu Layak Konsumsi' => $validatedData['waktu_layak'],
                    'Lokasi Dropbox'    => $validatedData['lokasi_dropbox'],
                    'Kategori Wilayah'  => $validatedData['kategori_wilayah'],
                    'Kategori Penerima' => $validatedData['kategori_penerima'],
                    'Deskripsi Tambahan' => $validatedData['deskripsi'] ?? 'Tidak ada deskripsi',
                ] // <-- Array detail dikirim di sini
            ));
        }
        /* ======================================================== */

        // 5. Redirect kembali dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Berhasil! Donasi baru telah ditambahkan dan sedang menunggu kurasi.');
    }
}