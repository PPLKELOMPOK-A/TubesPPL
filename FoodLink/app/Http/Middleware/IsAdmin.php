<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login DAN memiliki role 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Biarkan request lewat ke rute tujuan aslinya
            return $next($request); 
        }

        // Jika bukan admin, tendang kembali ke halaman beranda/dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Akses ditolak! Halaman ini khusus Admin.');
    }
}