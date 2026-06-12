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
    // 1. Validasi disesuaikan dengan field yang ada di Model $fillable
    $validated = $request->validate([
        'judul'          => 'required|string|max:255',
        'kategori'       => 'required|string|max:255',
        'tanggal'        => 'required|date',
        'foto'           => 'nullable|image',
        'deskripsi'      => 'required|string',
        'alamat'         => 'required|string',
        'nama_makanan'   => 'nullable|string',
        'donatur'        => 'nullable|string',
        'porsi'          => 'nullable|string',
        'quantity'       => 'nullable|integer',
        'food_type'      => 'nullable|string',
        'estimated_time' => 'nullable|string',
        'status'         => 'nullable|string',
    ]);

    // 2. Proses simpan foto disesuaikan ke kolom 'foto'
    if ($request->hasFile('foto')) {
        $validated['foto'] = $request
            ->file('foto')
            ->store('donation_photos', 'public');
    }

    // 3. Selipkan user_id otomatis dari admin/user yang sedang login
    $validated['user_id'] = auth()->id();
    
    // Set status default jika tidak diisi dari form
    if (!isset($validated['status'])) {
        $validated['status'] = 'menunggu';
    }

    // 4. Simpan ke database menggunakan nama field yang sudah sinkron
    $donation = Donation::create($validated);

    if (class_exists('\App\Events\DonationUpdated')) {
        broadcast(new \App\Events\DonationUpdated($donation))->toOthers();
    }

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