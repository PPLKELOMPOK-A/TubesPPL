<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User; // Ditambahkan untuk memanggil data Admin
use App\Notifications\SistemNotifikasi; // Ditambahkan untuk notifikasi

class ReviewController extends Controller
{
    public function index()
    {
        return view('review.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);

        Review::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'rating' => $request->rating,
            'review' => $request->review,
            'feedback' => $request->feedback,
        ]);

        // ==========================================
        // FITUR NOTIFIKASI: Beri tahu Admin ada ulasan
        // ==========================================
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $pesan = "Ulasan Baru! {$request->nama} memberikan penilaian {$request->rating} Bintang. Kategori: {$request->kategori}.";
            $admin->notify(new SistemNotifikasi("Ulasan Baru", "{$request->nama} memberikan penilaian {$request->rating} Bintang.", "review"));
        }

        return redirect()->route('review.success');
    }
}