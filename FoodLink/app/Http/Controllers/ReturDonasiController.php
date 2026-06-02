<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturDonasi;
use App\Models\DonasiMakanan; // Ditambahkan untuk mencari data donatur dari relasi
use App\Models\User; // Ditambahkan untuk mencari data Admin & Donatur
use App\Notifications\SistemNotifikasi; // Ditambahkan untuk mengirim notifikasi

class ReturDonasiController extends Controller
{
    // MENAMPILKAN HALAMAN RETUR DONASI
    public function index()
    {
        return view('admin.retur_donasi');
    }

    // MENYIMPAN DATA RETUR DONASI
    public function store(Request $request)
    {
        // VALIDASI INPUT
        $request->validate([
            'id_donasi' => 'required',
            'nama_makanan' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'kategori' => 'required',
            'alasan' => 'required',
            'tanggal_pengajuan' => 'required|date',
            'deskripsi' => 'required',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'id_donasi.required' => 'ID Donasi wajib diisi',
            'nama_makanan.required' => 'Nama makanan wajib diisi',
            'jumlah.required' => 'Jumlah retur wajib diisi',
            'kategori.required' => 'Kategori wajib dipilih',
            'alasan.required' => 'Alasan retur wajib diisi',
            'tanggal_pengajuan.required' => 'Tanggal pengajuan wajib diisi',
            'deskripsi.required' => 'Deskripsi retur wajib diisi',
            'bukti.image' => 'File harus berupa gambar',
            'bukti.mimes' => 'Format gambar harus jpg, jpeg, atau png',
            'bukti.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        // SIMPAN FILE BUKTI JIKA ADA
        $buktiPath = null;

        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')
                ->store('bukti_retur', 'public');
        }

        // SIMPAN KE DATABASE
        ReturDonasi::create([
            'id_donasi'          => $request->id_donasi,
            'nama_makanan'       => $request->nama_makanan,
            'jumlah'             => $request->jumlah,
            'kategori'           => $request->kategori,
            'alasan'             => $request->alasan,
            'tanggal_pengajuan'  => $request->tanggal_pengajuan,
            'deskripsi'          => $request->deskripsi,
            'bukti'              => $request->bukti, // Anda mungkin perlu menyimpan $buktiPath di sini jika memakai sistem storage
        ]);

        // ==========================================
        // FITUR NOTIFIKASI: Saat Retur Terjadi
        // ==========================================
        
        // 1. Notifikasi ke Donatur
        $donasi = DonasiMakanan::find($request->id_donasi);
        if ($donasi) {
            $donatur = User::find($donasi->user_id);
            if ($donatur) {
                $pesanDonatur = "Perhatian: Terdapat kendala teknis pada donasi Anda (ID: {$request->id_donasi}) sehingga proses harus diretur. Alasan: {$request->alasan}";
                $donatur->notify(new SistemNotifikasi($pesanDonatur));
            }
        }

        // 2. Notifikasi Darurat ke Admin (Asumsi Anda memiliki kolom 'role' atau 'is_admin' di tabel users)
        $admin = User::where('role', 'admin')->first(); 
        if ($admin) {
            $pesanAdmin = "Darurat: Terdapat pengajuan Retur Donasi baru untuk Donasi ID: {$request->id_donasi}.";
            $admin->notify(new SistemNotifikasi($pesanAdmin));
        }

        // REDIRECT DENGAN PESAN SUKSES (SUDAH DIPERBAIKI)
        return redirect()
            ->route('admin.retur.index')
            ->with('success', 'Retur berhasil diajukan dan disimpan!');
    }
}