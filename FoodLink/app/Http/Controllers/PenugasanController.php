<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;

class PenugasanController extends Controller
{
    public function index()
    {
        // Menggunakan latest() untuk mengurutkan dari data yang paling baru dibuat
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
            // Tambahkan 'unique:penugasans,id_penugasan' di bawah ini
            'id_penugasan'       => 'required|unique:penugasans,id_penugasan',
            'id_donasi'          => 'required',
            'nama_donatur'       => 'required',
            'relawan'            => 'required',
            'lokasi_pengantaran' => 'required',
            'tanggal_penugasan'  => 'required|date',
        ], [
            // Opsional: Custom pesan error dalam bahasa Indonesia
            'id_penugasan.unique' => 'ID Penugasan ini sudah digunakan, silakan gunakan ID lain!',
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
        $data = Penugasan::findOrFail($id);
        $data->delete();

        // 3. REVISI REDIRECT: Lebih aman diarahkan langsung ke index penugasan 
        // daripada back() untuk menghindari crash jika di-refresh setelah hapus data
        return redirect('/admin/penugasan')->with('success', 'Data berhasil dihapus');
    }

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
            'id_donasi'          => $request->id_donasi ?? $item->id_donasi, // REVISI: Jika kosong, gunakan data lama (jangan hardcode angka 1)
            'nama_donatur'       => $request->nama_donatur,
            'relawan'            => $request->relawan,
            'lokasi_pengambilan' => $request->lokasi_pengambilan ?? '-',
            'lokasi_pengantaran' => $request->lokasi_pengantaran,
            'tanggal_penugasan'  => $request->tanggal_penugasan,
        ]);

        return redirect('/admin/penugasan')->with('success', 'Data berhasil diupdate');
    }
}