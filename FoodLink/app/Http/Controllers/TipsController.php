<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipsController extends Controller
{
    public function index()
    {
        return view('Tips.tips');
    }

    public function prosesPembayaran(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'pesan'  => 'nullable|string|max:255'
        ]);

        $amount = $request->amount;
        $pesan = $request->pesan;

        return view('Tips.tips-bayar', compact('amount', 'pesan'));
    }
}