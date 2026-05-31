<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek role user
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Ambil data donasi user ini
        $donations = Donation::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard', compact('donations'));
    }
}