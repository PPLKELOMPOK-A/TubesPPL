<?php

// app/Http/Controllers/KegiatanDonasiController.php

namespace App\Http\Controllers;

use App\Models\KegiatanDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanDonasiController extends Controller
{
    // Menampilkan halaman form buat kegiatan (Sesuai kesepakatan folder sebelumnya)
    public function create()
    {
        // Pastikan file blade-nya bernama 'buat-kegiatan.blade.php' di dalam folder 'resources/views/admin/'
        return view('admin.create');
    }

    // Memproses data yang di-submit
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'judul_donasi'      => 'required|string|max:255',
            'kategori_penerima' => 'required|string',
            'tanggal_kegiatan'  => 'required|date',
            'deskripsi'         => 'required|string',
            'alamat_penyaluran' => 'required|string|max:255',
            'foto_kegiatan'     => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Maksimal 5MB
        ]);

        // 2. Upload Foto
        if ($request->hasFile('foto_kegiatan')) {
            // Simpan ke storage/app/public/kegiatan_foto
            $path = $request->file('foto_kegiatan')->store('kegiatan_foto', 'public');
            $validatedData['foto_kegiatan'] = $path;
        }

        // 3. Masukkan ke Database
        KegiatanDonasi::create($validatedData);

        // 4. Kembali ke form dengan pesan sukses
        return redirect()->back()->with('success', 'Kegiatan donasi berhasil diposting!');
    }
}