<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class TipsController extends Controller
{
    /**
     * 1. Menampilkan halaman utama input nominal tips (tips.blade.php)
     * Lokasi file wajib di: resources/views/tips.blade.php
     */
    public function index()
    {
        return view('Tips.tips');
    }

    /**
     * 2. Memproses data dari halaman input dan mengoper ke halaman konfirmasi
     * Lokasi file wajib di: resources/views/tips-bayar.blade.php
     */
    public function prosesPembayaran(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ], [
            'amount.required' => 'Nominal tips harus diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Minimal tips adalah Rp. 10.000.',
        ]);

        $amount = $request->amount;
        $pesan = $request->pesan;

        return view('Tips.tips-bayar', compact('amount', 'pesan'));
    }

    /**
     * 3. Membuat Token Snap Midtrans saat tombol "Bayar Sekarang" diklik via AJAX
     */
    public function checkoutMidtrans(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'TIPS-' . time() . '-' . rand(100, 999);
        $amount = $request->final_amount;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'item_details' => [
                [
                    'id' => 'TIPS-01',
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Tips Sukarela FoodLink',
                ]
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Donatur',
                'email' => auth()->user()->email ?? 'donatur@foodlink.com',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}