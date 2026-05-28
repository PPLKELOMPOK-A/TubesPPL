<?php

// app/Http/Controllers/DonasiMakananController.php

namespace App\Http\Controllers;

use App\Models\DonasiMakanan;
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
        $validatedData = $request->validate([
            'judul_donasi'      => 'required|string|max:255', // <-- TAMBAHAN: Kolom Judul Donasi
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

        if ($request->hasFile('foto_makanan')) {
            $path = $request->file('foto_makanan')->store('donasi_foto', 'public');
            $validatedData['foto_makanan'] = $path;
        }

        // Set default status saat pertama kali dibuat
        $validatedData['status'] = 'Pending';

        DonasiMakanan::create($validatedData);

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

        $validatedData = $request->validate([
            'judul_donasi'      => 'required|string|max:255', // <-- TAMBAHAN: Kolom Judul Donasi bisa diedit
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