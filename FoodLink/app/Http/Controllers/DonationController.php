<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Menampilkan halaman tracking donasi
     */
    public function index()
    {
        $donations = Donation::orderByDesc('created_at')->paginate(10);

        $total = Donation::count();
        $terkirim = Donation::where('status', 'terkirim')->count();
        $dalamPerjalanan = Donation::where('status', 'dalam_perjalanan')->count();

        return view('tracking.tracking', compact('donations', 'total', 'terkirim', 'dalamPerjalanan'));
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

        // Simpan ke database
        $donation = Donation::create($validated);

        // Broadcast event untuk real-time (jika menggunakan Pusher)
        broadcast(new \App\Events\DonationUpdated($donation))->toOthers();

        return response()->json($donation);
    }

    /**
     * Mengembalikan semua donasi dalam format JSON
     * Untuk polling / real-time tanpa Pusher
     */
  public function getDonationsJson()
{
    $donations = Donation::orderByDesc('created_at')->get();
    return response()->json($donations);
}
}