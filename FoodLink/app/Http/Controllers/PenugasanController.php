<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;
use App\Models\User; // Ditambahkan untuk mencari data kurir/relawan
use App\Notifications\SistemNotifikasi; // Ditambahkan untuk mengirim notifikasi

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
            'id_penugasan'       => 'required|unique:penugasans,id_penugasan',
            'id_donasi'          => 'required',
            'nama_donatur'       => 'required',
            'relawan'            => 'required',
            'lokasi_pengantaran' => 'required',
            'tanggal_penugasan'  => 'required|date',
        ], [
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

        // ==========================================
        // FITUR NOTIFIKASI: Kirim ke Relawan/Kurir
        // ==========================================
        try {
            // Mencari user berdasarkan nama yang diinputkan di form
            $kurir = User::where('name', $request->relawan)->first();
            
            if ($kurir) {
                $kurir->notify(new SistemNotifikasi(
                    "Tugas Baru", 
                    "Anda ditugaskan untuk mengambil/mengantar donasi (ID: {$request->id_penugasan}).", 
                    "penugasan"
                ));
            }
        } catch (\Exception $e) {
            \Log::error('Notifikasi Penugasan Baru Gagal: ' . $e->getMessage());
        }

        return redirect()->route('admin.penugasan.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $data = Penugasan::findOrFail($id);
        
        // ==========================================
        // FITUR NOTIFIKASI: Pembatalan Tugas
        // ==========================================
        try {
            $kurir = User::where('name', $data->relawan)->first();
            
            if ($kurir) {
                $pesan = "Penugasan (ID: {$data->id_penugasan}) yang diberikan kepada Anda telah dibatalkan oleh Admin.";
                $kurir->notify(new SistemNotifikasi("Pembatalan Tugas", $pesan, "penugasan"));
            }
        } catch (\Exception $e) {
            \Log::error('Notifikasi Pembatalan Tugas Gagal: ' . $e->getMessage());
        }

        $data->delete();

        return redirect()->route('admin.penugasan.index')->with('success', 'Data berhasil dihapus');
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
            'tanggal_penugasan'  => 'required',
        ]);

        $item = Penugasan::findOrFail($id);

        // Simpan nama lama untuk dicari dan diganti di Drop Box
        $relawanLama = $item->relawan;
        $relawanBaru = $request->relawan;

        $item->update([
            'id_penugasan'       => $request->id_penugasan,
            'id_donasi'          => $request->id_donasi ?? $item->id_donasi,
            'nama_donatur'       => $request->nama_donatur,
            'relawan'            => $relawanBaru,
            'lokasi_pengambilan' => $request->lokasi_pengambilan ?? '-',
            'lokasi_pengantaran' => $request->lokasi_pengantaran,
            'tanggal_penugasan'  => $request->tanggal_penugasan,
        ]);

        // =========================================================
        // LOGIKA OTOMATIS: UBAH NAMA RELAWAN DI FITUR DROP BOX JUGA
        // =========================================================
        if ($relawanLama !== $relawanBaru) {
            $box = \App\Models\DropBox::find($item->id_donasi);
            
            if ($box) {
                // 1. Ganti nama di animasi motor & status aktif
                $task = $box->active_task;
                if ($task) {
                    $task['petugas'] = $relawanBaru;
                    $box->active_task = $task;
                    
                    if (strpos($box->keterangan_status, $relawanLama) !== false) {
                        $box->keterangan_status = str_replace($relawanLama, $relawanBaru, $box->keterangan_status);
                    }
                }

                // 2. Ganti nama di Riwayat (History) Drop Box
                $history = $box->history ?? [];
                foreach ($history as $key => $hist) {
                    if (strpos($hist, $relawanLama) !== false) {
                        $history[$key] = str_replace($relawanLama, $relawanBaru, $hist);
                    }
                }
                $box->history = $history;
                
                $box->save();
            }

            // =======================================================
            // FITUR NOTIFIKASI: Beri tahu kedua relawan (Lama & Baru)
            // =======================================================
            try {
                $kurirLama = User::where('name', $relawanLama)->first();
                if ($kurirLama) {
                    $kurirLama->notify(new SistemNotifikasi(
                        "Perhatian", 
                        "Tugas donasi (ID: {$request->id_penugasan}) telah dialihkan ke relawan lain.", 
                        "penugasan"
                    ));
                }

                $kurirBaru = User::where('name', $relawanBaru)->first();
                if ($kurirBaru) {
                    $kurirBaru->notify(new SistemNotifikasi(
                        "Tugas Baru", 
                        "Anda ditugaskan untuk menggantikan relawan sebelumnya pada donasi (ID: {$request->id_penugasan}).", 
                        "penugasan"
                    ));
                }
            } catch (\Exception $e) {
                \Log::error('Notifikasi Update Tugas Gagal: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.penugasan.index')->with('success', 'Data berhasil diupdate dan disinkronkan dengan Drop Box');
    }
}