<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipsController extends Controller
{
    /**
     * Menampilkan halaman awal fitur Tips (Pilih Nominal)
     */
    public function index()
    {
        return view('tips');
    }

    /**
     * Menerima data dari halaman awal, lalu membawanya ke halaman Metode Pembayaran
     */
    public function prosesPembayaran(Request $request)
    {
        // 1. Validasi input agar nominal wajib diisi dan berupa angka
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'pesan'  => 'nullable|string|max:255'
        ]);

        // 2. Ambil data nominal dan pesan dari form halaman pertama
        $amount = $request->amount;
        $pesan = $request->pesan;

        // 3. Alihkan halaman ke 'tips-bayar.blade.php' sambil membawa data tadi
        return view('tips-bayar', compact('amount', 'pesan'));
    }
}