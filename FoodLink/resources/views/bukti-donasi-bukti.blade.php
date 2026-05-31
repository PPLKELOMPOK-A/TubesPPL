@extends('layouts.app')

@section('title', 'Lihat Bukti - ' . $donasi->judul)

@section('styles')
<style>
    .title { font-size: 28px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
    .subtitle { color: #7f8c8d; margin-bottom: 30px; font-size: 15px; }
    .gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin-bottom: 30px; }
    .gallery img { width: 100%; height: 250px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .info-box { background: white; padding: 25px; border-radius: 12px; border-left: 6px solid #5b3a1e; box-shadow: 0 2px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
    .info-box h3 { margin-top: 0; color: #5b3a1e; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
    .info-item { margin: 12px 0; font-size: 15px; display: flex; align-items: center; }
    .status-box { display: flex; align-items: center; gap: 8px; margin-bottom: 15px; color: #28a745; font-weight: bold; }
    .status-final { margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ddd; font-weight: bold; }
    .badge { background: #28a745; color: white; padding: 5px 15px; border-radius: 50px; font-size: 13px; margin-left: 10px; }
    .btn-kembali { display: inline-flex; align-items: center; background: #5b3a1e; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; transition: 0.3s; width: fit-content; }
</style>
@endsection

@section('content')
<div style="padding: 30px 40px;">
    <div class="title">{{ $donasi->judul }}</div>
    <div class="subtitle">Lihat dan verifikasi hasil distribusi donasi makanan Anda</div>

    <div class="gallery">
        @if($donasi->foto)
            <img src="{{ asset('img/' . $donasi->foto) }}" alt="Bukti Donasi" onerror="this.src='https://via.placeholder.com/300x250?text=Foto+Tidak+Tersedia'">
        @else
            <p style="color: gray; font-style: italic;">Tidak ada foto bukti tersedia.</p>
        @endif
    </div>

    <div class="info-box">
        <h3>Informasi Penyelesaian Donasi</h3>
        <div class="status-box">
            <span>✔ Status: <b>{{ ucfirst($donasi->status ?? 'Selesai') }}</b></span>
        </div>
        <div class="info-item">📅 &nbsp; <b>Tanggal Donasi:</b> &nbsp; {{ $donasi->tanggal ?? '-' }}</div>
        <div class="info-item">🏷️ &nbsp; <b>Kategori:</b> &nbsp; {{ $donasi->kategori ?? '-' }}</div>
        <div class="info-item">🍚 &nbsp; <b>Nama Makanan:</b> &nbsp; {{ $donasi->nama_makanan ?? '-' }}</div>
        <div class="info-item">📝 &nbsp; <b>Deskripsi:</b> &nbsp; {{ $donasi->deskripsi ?? '-' }}</div>
        <div class="info-item">📍 &nbsp; <b>Alamat:</b> &nbsp; {{ $donasi->alamat ?? '-' }}</div>
        <div class="status-final">
            ✔ Status Penyelesaian:
            <span class="badge">Selesai Dikirim</span>
        </div>
    </div>

    <a href="{{ route('bukti.donasi') }}" class="btn-kembali">
        <span>←</span> Kembali
    </a>
</div>
@endsection