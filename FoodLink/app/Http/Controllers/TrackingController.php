<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;

class TrackingController extends Controller
{
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

        // Karena data penugasan belum memiliki sistem status tracking real-time,
        // maka semua penugasan aktif dianggap sedang dalam perjalanan.
        $terkirim = 0;
        $dalamPerjalanan = $total;

        $trackings = $query
            ->orderByDesc('tanggal_penugasan')
            ->orderByDesc('created_at')
            ->paginate(5)
            ->withQueryString();

        return view('tracking.index', compact(
            'trackings',
            'total',
            'terkirim',
            'dalamPerjalanan'
        ));
    }

    public function show($id)
    {
        $tracking = Penugasan::where('id_penugasan', $id)
            ->orWhere('id', $id)
            ->firstOrFail();

        $status = $tracking->status_pengiriman
            ?? $tracking->status
            ?? 'dalam_perjalanan';

        $pickupLocation = trim((string) ($tracking->lokasi_pengambilan ?? ''));
        $deliveryLocation = trim((string) ($tracking->lokasi_pengantaran ?? ''));

        // Fokus peta utama: lokasi pengantaran, kalau kosong pakai lokasi pengambilan
        $mapFocus = $deliveryLocation !== '' ? $deliveryLocation : ($pickupLocation !== '' ? $pickupLocation : 'Indonesia');

        // Link rute Google Maps
        $directionUrl = 'https://www.google.com/maps/dir/?api=1'
            . '&origin=' . urlencode($pickupLocation !== '' ? $pickupLocation : 'Indonesia')
            . '&destination=' . urlencode($deliveryLocation !== '' ? $deliveryLocation : 'Indonesia')
            . '&travelmode=driving';

        $pickupMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($pickupLocation !== '' ? $pickupLocation : 'Indonesia');
        $deliveryMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($deliveryLocation !== '' ? $deliveryLocation : 'Indonesia');

        return view('tracking.show', [
            'tracking' => $tracking,
            'status' => $status,
            'mapFocus' => $mapFocus,
            'directionUrl' => $directionUrl,
            'pickupMapUrl' => $pickupMapUrl,
            'deliveryMapUrl' => $deliveryMapUrl,
        ]);
    }
}