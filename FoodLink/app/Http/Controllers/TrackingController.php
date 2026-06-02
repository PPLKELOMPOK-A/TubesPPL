<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;
use App\Models\User; // Tambahan untuk memanggil data User (Donatur/Komunitas)
use App\Notifications\SistemNotifikasi; // Tambahan untuk mengirim Notifikasi

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

    // =========================================================================
    // FUNGSI BARU: Untuk Mengubah Status Tracking dan Mengirim Notifikasi
    // =========================================================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string' // Contoh: 'menunggu_penjemputan', 'dalam_perjalanan', 'selesai'
        ]);

        $tracking = Penugasan::where('id_penugasan', $id)
            ->orWhere('id', $id)
            ->firstOrFail();

        // Menyimpan update status ke database (sesuaikan nama kolom di DB Anda, misal: 'status' atau 'status_pengiriman')
        $tracking->status = $request->status; 
        $tracking->save();

        // ==========================================
        // FITUR NOTIFIKASI: Kirim Update ke Donatur
        // ==========================================
        // Mencari akun donatur berdasarkan namanya dari tabel penugasan
        $donatur = User::where('name', $tracking->nama_donatur)->first();

        if ($donatur) {
            if ($request->status == 'menunggu_penjemputan') {
                $donatur->notify(new SistemNotifikasi("Kurir/Relawan sedang menuju ke lokasi Anda untuk menjemput donasi (ID: {$tracking->id_penugasan})."));
            } elseif ($request->status == 'dalam_perjalanan') {
                $donatur->notify(new SistemNotifikasi("Donasi Anda (ID: {$tracking->id_penugasan}) sedang dalam perjalanan menuju lokasi penerima."));
            } elseif ($request->status == 'selesai') {
                $donatur->notify(new SistemNotifikasi("Alhamdulillah! Donasi Anda (ID: {$tracking->id_penugasan}) telah sampai dengan selamat di tangan penerima. Terima kasih atas kebaikan Anda!"));
            }
        }

        // Anda juga bisa menambahkan notifikasi untuk Komunitas/Penerima di sini jika ada datanya

        return back()->with('success', 'Status pengiriman berhasil diperbarui dan notifikasi telah dikirim ke Donatur.');
    }
}