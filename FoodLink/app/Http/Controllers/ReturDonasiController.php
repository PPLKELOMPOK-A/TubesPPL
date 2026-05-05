<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturDonasi;

class ReturDonasiController extends Controller
{
    public function index()
    {
        return view('admin.retur_donasi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_donasi' => 'required',
            'nama_makanan' => 'required',
            'jumlah' => 'required|numeric',
            'kategori' => 'required',
            'alasan' => 'required',
            'tanggal_pengajuan' => 'required|date',
            'bukti' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_retur', 'public');
        }

        ReturDonasi::create([
            'id_donasi' => $request->id_donasi,
            'nama_makanan' => $request->nama_makanan,
            'jumlah' => $request->jumlah,
            'kategori' => $request->kategori,
            'alasan' => $request->alasan,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'deskripsi' => $request->deskripsi,
            'bukti' => $buktiPath
        ]);

        return back()->with('success', 'Retur berhasil diajukan');
    }
}