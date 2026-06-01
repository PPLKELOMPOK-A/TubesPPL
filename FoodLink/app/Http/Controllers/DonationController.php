<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Penugasan;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Menampilkan halaman tracking donasi.
     * Data tracking diambil dari tabel penugasans agar terhubung dengan fitur penugasan relawan admin.
     */
    public function index(Request $request)
    {
        $query = Penugasan::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id_penugasan', 'like', '%' . $request->search . '%')
                    ->orWhere('id_donasi', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_donatur', 'like', '%' . $request->search . '%')
                    ->orWhere('relawan', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi_pengambilan', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi_pengantaran', 'like', '%' . $request->search . '%');
            });
        }

        $total = (clone $query)->count();

        /*
         * Karena tabel penugasans milik temanmu belum punya kolom status,
         * maka semua data penugasan yang sudah dibuat admin dianggap sedang dalam perjalanan.
         */
        $terkirim = 0;
        $dalamPerjalanan = $total;

        $donations = $query
            ->orderByDesc('tanggal_penugasan')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('tracking.tracking', compact(
            'donations',
            'total',
            'terkirim',
            'dalamPerjalanan'
        ));
    }

    public function show($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return redirect()->route('donation.tracking')
                ->with('error', 'Data tidak ditemukan');
        }

        return view('trackingdetail', compact('donation'));
    }

    /**
     * Menyimpan donasi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_donasi'        => 'required|string|max:255',
            'kategori_penerima'   => 'required|string|max:255',
            'tanggal_kegiatan'    => 'required|date',
            'foto_kegiatan'       => 'nullable|image',
            'deskripsi'           => 'required|string',
            'alamat_penyaluran'   => 'required|string',
            'status'              => 'nullable|string',
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            $validated['foto_kegiatan'] = $request
                ->file('foto_kegiatan')
                ->store('donation_photos', 'public');
        }

        $donation = Donation::create($validated);

        broadcast(new \App\Events\DonationUpdated($donation))->toOthers();

        return response()->json($donation);
    }

    /**
     * Mengembalikan semua donasi dalam format JSON.
     * Untuk polling / real-time tanpa Pusher.
     */
    public function getDonationsJson()
    {
        $donations = Donation::orderByDesc('created_at')->get();

        return response()->json($donations);
    }
}