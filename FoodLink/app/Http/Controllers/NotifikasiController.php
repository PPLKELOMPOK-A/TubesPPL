<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\SistemNotifikasi;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        
        if ($filter === 'unread') {
            $notifications = $user->unreadNotifications;
        } elseif (in_array($filter, ['donasi', 'relawan', 'sistem', 'status donasi', 'komunitas'])) {
            $notifications = $user->notifications->filter(function($notif) use ($filter) {
                return isset($notif->data['category']) && strtolower($notif->data['category']) === $filter;
            });
        } else {
            $notifications = $user->notifications;
        }

        return view('notifikasi', compact('notifications', 'filter'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    /**
     * Menandai satu notifikasi sebagai dibaca lalu mengarahkan ke halaman detail.
     */
    public function markSingleAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // Alihkan langsung ke halaman detail notifikasi
        return redirect()->route('notifikasi.show', $id);
    }

    /**
     * Menampilkan halaman detail notifikasi.
     */
    public function show($id)
    {
        $notification = \Illuminate\Support\Facades\Auth::user()->notifications()->findOrFail($id);

        $title = $notification->data['title'] ?? 'Pemberitahuan';
        $message = $notification->data['message'] ?? '';
        $category = $notification->data['category'] ?? 'Sistem';
        $details = $notification->data['details'] ?? []; 
        $time = $notification->created_at;

        return view('notifikasi-detail', compact('notification', 'title', 'message', 'category', 'details', 'time'));
    }
}