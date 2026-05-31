<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna
     */
    public function show()
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Mengarahkan ke file blade profil (sesuaikan folder & nama filenya)
        return view('profile.show', compact('user'));
    }
}