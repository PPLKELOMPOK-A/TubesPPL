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

    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan' => 'required',
            'id_donasi' => 'required',
            'nama_donatur' => 'required',
            'relawan' => 'required',
            'lokasi_pengambilan' => 'required',
            'lokasi_pengantaran' => 'required',
            'tanggal_penugasan' => 'required|date',
        ]);

        Penugasan::create($request->all());

        return redirect('/admin/penugasan')->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Penugasan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function edit($id)
    {
        $data = Penugasan::latest()->get();
        $edit = Penugasan::findOrFail($id);

        return view('penugasan', [
            'data' => $data,
            'edit' => $edit
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Penugasan::findOrFail($id);

        $item->update($request->all());

        return redirect('/admin/penugasan')->with('success', 'Data berhasil diupdate');
    }
}