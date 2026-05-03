<?php

namespace App\Http\Controllers;

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
        return view('donasi.create'); // Pastikan file blade ada di resources/views/donasi/create.blade.php
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
            'foto_makanan'      => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Maks 5MB
        ]);

        // 2. Proses upload foto ke folder storage/app/public/donasi_foto
        if ($request->hasFile('foto_makanan')) {
            $path = $request->file('foto_makanan')->store('donasi_foto', 'public');
            $validatedData['foto_makanan'] = $path;
        }

        // 3. Simpan data ke database
        DonasiMakanan::create($validatedData);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Donasi makanan berhasil diajukan! Menunggu kurasi admin.');
    }
}