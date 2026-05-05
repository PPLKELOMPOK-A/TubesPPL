<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        return view('admin.dashboard');
    }
}