@extends('layouts.app')

@section('title', 'Foodlink - Detail Donasi')

@section('content')
<style>
    /* --- CONTAINER DETAIL --- */
    .container-detail { padding: 40px 60px; max-width: 1000px; width: 100%; margin-left: 0; margin-right: auto; }

    .back-nav { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #eee; color: #444; text-decoration: none; margin-bottom: 20px; transition: 0.2s; }
    .back-nav:hover { background: #e0e0e0; color: #000; }

    .header-info { margin-bottom: 30px; }
    .header-info h1 { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 5px; }
    .header-info .category { font-size: 14px; color: #6B4F2A; font-weight: 600; margin-bottom: 5px; display: block; }
    .header-info .date { font-size: 14px; color: #999; }

    .image-container { width: 450px; height: 300px; border-radius: 12px; overflow: hidden; border: 1px solid #eee; margin-bottom: 30px; background-color: #f5f5f5; }
    .image-container img { width: 100%; height: 100%; object-fit: cover; }

    .content-section { margin-bottom: 25px; max-width: 600px; }
    .section-title { font-size: 16px; font-weight: 700; color: #444; margin-bottom: 10px; }
    .section-text { font-size: 14px; color: #666; line-height: 1.6; }

    .footer-actions { display: flex; justify-content: flex-start; gap: 15px; margin-top: 40px; padding-bottom: 50px; }
    
    .btn-action-detail { background-color: #6B4F2A; color: white; border: none; padding: 12px 40px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
    .btn-action-detail:hover { background-color: #563e21; }
</style>

<div class="main-content-canvas">
    <div class="container-detail">
        
        <a href="{{ route('dashboard') }}" class="back-nav" title="Kembali ke Beranda">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="header-info">
            <h1>{{ $data->judul_donasi }}</h1>
            <span class="category">{{ $data->kategori_penerima }}</span>
            <p class="date">{{ \Carbon\Carbon::parse($data->tanggal_kegiatan)->translatedFormat('l, d F Y') }}</p>
        </div>

        <div class="image-container">
            @if(!empty($data->foto_kegiatan))
                <img src="{{ asset('storage/' . $data->foto_kegiatan) }}" alt="Foto Donasi">
            @else
                <img src="https://via.placeholder.com/450x300/f5f5f5/cccccc?text=Belum+Ada+Foto" alt="Donasi">
            @endif
        </div>

        <div class="content-section">
            <h3 class="section-title">Deskripsi Kegiatan</h3>
            <p class="section-text">{{ $data->deskripsi }}</p>
        </div>

        <div class="content-section">
            <h3 class="section-title">Alamat</h3>
            <p class="section-text">{{ $data->alamat_penyaluran }}</p>
        </div>

        <div class="footer-actions">
            <a href="#
            " class="btn-action-detail">Daftar Donasi</a>
        </div>

    </div>
</div>
@endsection