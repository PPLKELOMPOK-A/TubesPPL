<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;

class PenugasanController extends Controller
{
    public function index()
    {
        $data = Penugasan::latest()->get();
        return view('admin.penugasan', compact('data'));
    }

    public function create()
    {
        return view('admin.penugasan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan'       => 'required',
            'nama_donatur'       => 'required',
            'relawan'            => 'required',
            'lokasi_pengantaran' => 'required',
            'tanggal_penugasan'  => 'required|date',
        ]);

        Penugasan::create([
            'id_penugasan'       => $request->id_penugasan,
            'id_donasi'          => $request->id_donasi,
            'nama_donatur'       => $request->nama_donatur,
            'relawan'            => $request->relawan,
            'lokasi_pengambilan' => $request->lokasi_pengambilan ?? '-',
            'lokasi_pengantaran' => $request->lokasi_pengantaran,
            'tanggal_penugasan'  => $request->tanggal_penugasan,
        ]);

        return redirect('/admin/penugasan')->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Penugasan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    // ✅ DIUBAH: sekarang mengarah ke halaman edit terpisah
    public function edit($id)
    {
        $penugasan = Penugasan::findOrFail($id);
        return view('admin.penugasan.edit', compact('penugasan'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_penugasan'       => 'required',
            'nama_donatur'       => 'required',
            'relawan'            => 'required',
            'lokasi_pengantaran' => 'required',
            'tanggal_penugasan'  => 'required|date',
        ]);

        $item = Penugasan::findOrFail($id);

        $item->update([
            'id_penugasan'       => $request->id_penugasan,
            'id_donasi'          => $request->id_donasi ?? 1,
            'nama_donatur'       => $request->nama_donatur,
            'relawan'            => $request->relawan,
            'lokasi_pengambilan' => $request->lokasi_pengambilan ?? '-',
            'lokasi_pengantaran' => $request->lokasi_pengantaran,
            'tanggal_penugasan'  => $request->tanggal_penugasan,
        ]);

        return redirect('/admin/penugasan')->with('success', 'Data berhasil diupdate');
    }
}