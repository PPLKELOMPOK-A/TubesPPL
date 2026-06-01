<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return view('review.index');
    }

    public function store(Request $request)
    {
        $request->validate([

            'nama' => 'required',
            'kategori' => 'required',
            'rating' => 'required',
            'review' => 'required',

        ]);

        Review::create([

            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'rating' => $request->rating,
            'review' => $request->review,
            'feedback' => $request->feedback,

        ]);

        return redirect()->route('review.success');
    }
}