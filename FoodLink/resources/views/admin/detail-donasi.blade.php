@extends('layouts.app')

@section('title', 'Foodlink Admin - Detail Donasi')

@section('content')
<style>
    /* --- CONTAINER DETAIL --- */
    .container-detail { padding: 40px 60px; max-width: 1000px; width: 100%; margin-left: 0; margin-right: auto; }

    /* Tombol Bulat Kembali */
    .back-nav { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #eee; color: #444; text-decoration: none; margin-bottom: 20px; transition: 0.2s; border: none; cursor: pointer; }
    .back-nav:hover { background: #e0e0e0; color: #000; }

    /* Info Header */
    .header-info { margin-bottom: 30px; }
    .header-info h1 { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 5px; }
    .header-info .category { font-size: 14px; color: #6B4F2A; font-weight: 600; margin-bottom: 5px; display: block; }
    .header-info .date { font-size: 14px; color: #999; }

    /* Image Box */
    .image-container { width: 450px; height: 300px; border-radius: 12px; overflow: hidden; border: 1px solid #eee; margin-bottom: 30px; background-color: #f5f5f5; }
    .image-container img { width: 100%; height: 100%; object-fit: cover; }

    /* Content Typography */
    .content-section { margin-bottom: 25px; max-width: 600px; }
    .section-title { font-size: 16px; font-weight: 700; color: #444; margin-bottom: 10px; }
    .section-text { font-size: 14px; color: #666; line-height: 1.6; }

    /* Actions Area */
    .footer-actions { display: flex; justify-content: flex-start; align-items: center; gap: 15px; margin-top: 40px; padding-bottom: 50px; }
    
    /* Base Button Admin Style */
    .btn-admin { padding: 12px 40px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; min-width: 130px; }
    
    .btn-admin-hapus { background: #ffffff; border: 1px solid #d9534f; color: #d9534f; }
    .btn-admin-hapus:hover { background: #d9534f; color: white; }
    
    .btn-admin-edit { background-color: #6B4F2A; color: white; border: none; }
    .btn-admin-edit:hover { background-color: #563e21; }
</style>

<div class="main-content-canvas">
    <div class="container-detail">
        
        <a href="{{ route('admin.dashboard') }}" class="back-nav" title="Kembali ke Beranda Admin">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div style="background-color: #E6F4EA; border: 1px solid #1E8E3E; color: #1E8E3E; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; display: flex; align-items: center; gap: 10px; max-width: 600px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="header-info">
            <h1>{{ $data->judul_donasi ?? $data->judul }}</h1>
            <span class="category">{{ $data->kategori_penerima ?? $data->kategori }}</span>
            <p class="date">
                {{ isset($data->tanggal_kegiatan) || isset($data->tanggal) ? \Carbon\Carbon::parse($data->tanggal_kegiatan ?? $data->tanggal)->translatedFormat('l, d F Y') : '-' }}
            </p>
        </div>

        <div class="image-container">
            @if(!empty($data->foto_kegiatan))
                <img src="{{ asset('storage/' . $data->foto_kegiatan) }}" alt="Foto Donasi">
            @elseif(!empty($data->foto))
                <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto Donasi">
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
            <p class="section-text">{{ $data->alamat_penyaluran ?? $data->alamat }}</p>
        </div>

        <div class="footer-actions">
            <form action="{{ route('admin.donasi.delete', ['id' => $data->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus donasi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-admin btn-admin-hapus">Hapus</button>
            </form>
            
            <a href="{{ route('admin.donasi.edit', ['id' => $data->id]) }}" class="btn-admin btn-admin-edit">Edit</a>
        </div>

    </div>
</div>
@endsection