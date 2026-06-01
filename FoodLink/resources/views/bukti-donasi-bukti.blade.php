@extends('layouts.app')

@section('title', 'Detail Bukti Donasi')

@section('styles')
<style>
    .title { font-size: 28px; font-weight: bold; color: #2c3e50; margin-bottom: 30px; }
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
    <div class="title">Bukti Penyelesaian Donasi</div>

    <div class="info-box">
        <h3>Informasi Penyelesaian Donasi</h3>
        <div class="status-box">
            ✔ Status: <b>{{ ucfirst($donasi->status ?? 'Selesai') }}</b>
        </div>
        <div class="info-item">📅 &nbsp; <b>Tanggal Donasi:</b> &nbsp; {{ $donasi->created_at ? \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('d F Y') : '-' }}</div>
        <div class="info-item">🏷️ &nbsp; <b>Kategori Penerima:</b> &nbsp; {{ $donasi->kategori_penerima ?? '-' }}</div>
        <div class="info-item">🍚 &nbsp; <b>Kategori Makanan:</b> &nbsp; {{ $donasi->kategori_makanan ?? '-' }}</div>
        <div class="info-item">📍 &nbsp; <b>Lokasi Dropbox:</b> &nbsp; {{ $donasi->lokasi_dropbox ?? '-' }}</div>
        <div class="info-item">🗺️ &nbsp; <b>Wilayah:</b> &nbsp; {{ $donasi->kategori_wilayah ?? '-' }}</div>
        <div class="info-item">⏰ &nbsp; <b>Waktu Layak:</b> &nbsp; {{ $donasi->waktu_layak ?? '-' }}</div>
        <div class="info-item">📝 &nbsp; <b>Deskripsi:</b> &nbsp; {{ $donasi->deskripsi ?? '-' }}</div>
        <div class="status-final">
            ✔ Status Penyelesaian:
            <span class="badge">{{ ucfirst($donasi->status ?? 'Selesai') }}</span>
        </div>
    </div>

    <a href="{{ route('bukti.donasi') }}" class="btn-kembali">
        ← Kembali
    </a>
</div>
@endsection