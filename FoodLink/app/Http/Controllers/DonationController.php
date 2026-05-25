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

        return view('tracking', compact('donations', 'total', 'terkirim', 'dalamPerjalanan'));
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
        // Validasi input
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori'       => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'foto'           => 'nullable|image',
            'deskripsi'      => 'required|string',
            'alamat'         => 'required|string',
            'status'         => 'nullable|string',
            'quantity'       => 'nullable|string',
            'food_type'      => 'nullable|string',
            'estimated_time' => 'nullable|integer',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('donation_photos', 'public');
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