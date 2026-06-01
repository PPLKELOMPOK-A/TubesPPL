<?php

namespace App\Http\Controllers;

use App\Models\DonasiMakanan;
use App\Models\User; // <-- TAMBAHKAN INI UNTUK MENCARI ADMIN
use App\Notifications\SistemNotifikasi; // <-- TAMBAHKAN INI UNTUK MENGIRIM NOTIFIKASI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <-- PENTING: Tambahkan Auth untuk mengambil user_id
use App\Models\DropBox;

class DonasiMakananController extends Controller
{
    // Menampilkan form Blade
    // Menampilkan form Blade
    public function create()
    {
        // Mengambil semua data lokasi dropbox dari database
        // (Kamu juga bisa memfilter dengan ->where('status', 'tersedia') jika diperlukan)
        $dropboxes = DropBox::all(); 
        
        return view('donasi.create', compact('dropboxes')); 
    }

    // Memproses data form
    public function store(Request $request)
    {
        // 1. HAPUS validasi 'judul_donasi' karena tidak ada di form maupun tabel
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

        // Set default status saat pertama kali dibuat
        $validatedData['status'] = 'Pending';
        
        // 2. TAMBAHKAN user_id dari user yang sedang login
        $validatedData['user_id'] = Auth::id();

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

    // Menampilkan form edit
    public function edit($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);
        
        // Ubah huruf menjadi kecil semua & hapus spasi berlebih agar tidak error
        $statusBersih = strtolower(trim($donasi->status ?? ''));

        // Proteksi: Izinkan jika status pending/menunggu
        if (!in_array($statusBersih, ['menunggu validasi', 'pending', 'menunggu kurasi', ''])) {
            return redirect()->route('riwayat-donasi.index')->with('error', 'Donasi sudah diproses dan tidak dapat diedit.');
        }

        return view('donasi.edit', compact('donasi'));
    }

    // Memproses update data donasi
    public function update(Request $request, $id)
    {
        $donasi = DonasiMakanan::findOrFail($id);
        
        // Ubah huruf menjadi kecil semua
        $statusBersih = strtolower(trim($donasi->status ?? ''));

        if (!in_array($statusBersih, ['menunggu validasi', 'pending', 'menunggu kurasi', ''])) {
            return redirect()->route('riwayat-donasi.index')->with('error', 'Aksi ditolak. Donasi sudah diproses.');
        }

        // 3. HAPUS juga validasi 'judul_donasi' di fungsi update
        $validatedData = $request->validate([
            'kategori_makanan'  => 'required|string',
            'waktu_layak'       => 'required|string',
            'deskripsi'         => 'nullable|string',
            'foto_makanan'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('foto_makanan')) {
            if ($donasi->foto_makanan) {
                Storage::disk('public')->delete($donasi->foto_makanan);
            }
            $path = $request->file('foto_makanan')->store('donasi_foto', 'public');
            $validatedData['foto_makanan'] = $path;
        }

        $donasi->update($validatedData);

        return redirect()->route('riwayat-donasi.index')->with('success', 'Data donasi berhasil diperbarui.');
    }

    // Membatalkan (Menghapus) donasi
    public function cancel($id)
    {
        $donasi = DonasiMakanan::findOrFail($id);
        
        // Ubah huruf menjadi kecil semua
        $statusBersih = strtolower(trim($donasi->status ?? ''));

        if (in_array($statusBersih, ['menunggu validasi', 'pending', 'menunggu kurasi', ''])) {
            if ($donasi->foto_makanan) {
                Storage::disk('public')->delete($donasi->foto_makanan);
            }
            
            $donasi->delete();
            return redirect()->route('riwayat-donasi.index')->with('success', 'Donasi berhasil dibatalkan.');
        }

        return redirect()->route('riwayat-donasi.index')->with('error', 'Donasi sudah diproses dan tidak dapat dibatalkan.');
    }
    
}