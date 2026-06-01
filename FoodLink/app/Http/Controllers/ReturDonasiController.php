<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturDonasi;

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
            'bukti'              => $request->bukti,
        ]);

        // REDIRECT DENGAN PESAN SUKSES (SUDAH DIPERBAIKI)
        return redirect()
            ->route('admin.retur.index')
            ->with('success', 'Retur berhasil diajukan dan disimpan!');
    }
}