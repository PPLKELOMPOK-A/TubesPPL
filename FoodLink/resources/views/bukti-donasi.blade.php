@extends('layouts.app')

@section('title', 'Bukti Penyelesaian Donasi')

@section('styles')
<style>
    /* --- MEMAKSA KONTEN MEPEET KE KIRI & MELUAS PENUH SEPERTI MOCKUP --- */
    .page-content-container {
        width: 100%;
        max-width: 100%;
        margin-left: -70px;
        padding: 10px 40px 40px 0px;
        box-sizing: border-box;
    }

    h2 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #111111;
    }

    .sub {
        color: #888888;
        margin: 0 0 25px 0;
        font-size: 14px;
    }

    .search-wrapper {
        display: flex;
        gap: 0;
        align-items: center;
        width: 100%;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-radius: 8px;
    }

    .search {
        flex: 1;
        padding: 12px 18px;
        border-radius: 8px 0 0 8px;
        border: 1px solid #e2e8f0;
        border-right: none;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
        background: #ffffff;
    }

    .btn-search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 47px;
        width: 48px;
        background: #5b3a1e;
        color: white;
        border: 1px solid #5b3a1e;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.2s;
    }

    .btn-search-icon:hover {
        background: #422a16;
    }

    .card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        border-bottom: 1px solid #ededed;
        width: 100%;
    }

    .left {
        display: flex;
        align-items: center;
    }

    .thumb {
        width: 90px;
        height: 65px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 20px;
        background: #f5f5f5;
    }

    .info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #111111;
    }

    .info p {
        margin: 2px 0;
        color: #888888;
        font-size: 13px;
    }

    .actions {
        display: flex;
        gap: 12px;
    }

    .btn-primary {
        background: #5b3a1e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        font-size: 13px;
        box-shadow: 0 2px 4px rgba(91, 58, 30, 0.15);
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #422a16;
    }

    .btn-secondary {
        background: #ffffff;
        color: #46854d;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: #fcfdfc;
        border-color: #46854d;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        font-size: 13px;
        color: #666666;
    }

    .pages {
        display: flex;
        gap: 5px;
    }

    .pages button {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
    }
    
    .pages button.active {
        background: #5b3a1e;
        color: white;
        border-color: #5b3a1e;
    }

    .pages button:hover:not(.active) {
        background: #f8f9fa;
    }
</style>
@endsection

@section('content')
<div class="page-content-container">

    <h2>Bukti Penyelesaian Donasi</h2>
    <p class="sub">Lihat dan Verifikasi hasil distribusi donasi makanan</p>

    <form method="GET" action="{{ route('bukti.donasi') }}">
        <div class="search-wrapper">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="search"
                   placeholder="Search">
            <button type="submit" class="btn-search-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>

    @foreach($donasi as $item)
    <div class="card">

        <div class="left">
            <img src="{{ $item->foto ? asset('img/'.$item->foto) : 'https://via.placeholder.com/90x65' }}" class="thumb">

            <div class="info">
                <h4>{{ $item->judul }}</h4>
                <p>{{ $item->kategori }}</p>
                <p>{{ $item->tanggal }}</p>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('bukti-donasi.bukti', $item->id) }}" class="btn-primary">
                Lihat Bukti
            </a>
            <a href="{{ route('bukti-donasi.show', $item->id) }}" class="btn-secondary">
                Detail
            </a>
        </div>

    </div>
    @endforeach

    <div class="pagination">
        <span>1-5 dari 10</span>

        <div class="pages">
            <button class="active">1</button>
            <button>2</button>
            <button>&gt;</button>
        </div>
    </div>

</div>
@endsection
