@extends('layouts.app')

@section('title', 'Detail Bukti Donasi')

@section('content')
<style>
    /* Menggunakan penamaan class yang lebih spesifik agar tidak bentrok dengan layout utama */
    .bukti-container { 
        max-width: 800px; 
        margin: 0 auto; 
        background: white; 
        padding: 30px; 
        border-radius: 15px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.08); 
    }
    .header-bukti { 
        display: flex; 
        align-items: center; 
        margin-bottom: 30px; 
        border-bottom: 1px solid #eee; 
        padding-bottom: 15px; 
    }
    .btn-back { text-decoration: none; color: #333; font-size: 1.5rem; margin-right: 20px; }
    .title-section-bukti h2 { margin: 0; color: #2c3e50; font-size: 1.5rem; }
    .status-badge-bukti { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    
    .content-card-bukti { display: grid; grid-template-columns: 1fr; gap: 20px; }
    .detail-info { background: #fffaf5; border-radius: 10px; padding: 20px; border: 1px solid #ffe8d6; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #ddd; padding-bottom: 8px; }
    .info-row span:first-child { color: #7f8c8d; font-size: 0.9rem; }
    .info-row span:last-child { font-weight: 600; color: #2c3e50; text-align: right; }

    .gallery-title { margin-top: 30px; color: #2c3e50; border-left: 5px solid #8d6e63; padding-left: 10px; font-size: 1.2rem; font-weight: bold; }
    .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 15px; }
    .gallery-item { border-radius: 10px; overflow: hidden; height: 200px; border: 1px solid #eee; }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
    
    .description-box { margin-top: 20px; line-height: 1.6; color: #555; background: #fdfdfd; padding: 15px; border-radius: 8px; border: 1px solid #eee; }
</style>

<div class="main-content-canvas">
    <div class="bukti-container">
        <div class="header-bukti">
            <a href="javascript:history.back()" class="btn-back"><i class="fas fa-chevron-left"></i></a>
            <div class="title-section-bukti">
                <span class="status-badge-bukti">Selesai Disalurkan</span>
                <h2>Donasi ke {{ $donasi->kategori_penerima ?? 'Penerima' }}</h2>
            </div>
        </div>

        <div class="content-card-bukti">
            <div class="detail-info">
                <div class="info-row">
                    <span>ID Transaksi</span>
                    <span>#TRX-00{{ $donasi->id ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span>Tanggal Penyaluran</span>
                    <span>{{ $donasi->created_at ? \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span>Tujuan Penyaluran</span>
                    <span>{{ $donasi->kategori_penerima ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span>Jenis Donasi</span>
                    <span>{{ $donasi->kategori_makanan ?? '-' }}</span>
                </div>
            </div>

            <div class="description-box">
                <strong>Catatan Penyaluran:</strong><br>
                {{ $donasi->deskripsi ?? 'Tidak ada catatan.' }}
            </div>

            <h3 class="gallery-title">Dokumentasi Penyaluran</h3>
            <div class="gallery-grid">
                @if(!empty($donasi->foto_makanan))
                    <div class="gallery-item">
                        <img src="{{ asset('storage/' . $donasi->foto_makanan) }}" alt="Bukti Foto" onerror="this.src='https://via.placeholder.com/400x300?text=Foto+Dokumentasi'">
                    </div>
                @else
                    <p style="color: #999; font-style: italic; font-size: 14px;">Belum ada dokumentasi yang diunggah.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection