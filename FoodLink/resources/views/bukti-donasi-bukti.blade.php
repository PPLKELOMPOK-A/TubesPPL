@extends('layouts.app')

@section('title', 'Foodlink - Detail Penyelesaian Donasi')

@section('content')
<style>
    /* Container utama dibuat lebar dan merapat ke kiri */
    .content-container {
        padding: 40px 50px;
        max-width: 1000px; 
        margin-left: 0; 
        margin-right: auto;
        box-sizing: border-box;
    }

    /* Tombol Kembali */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6B4F2A;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 25px;
        transition: color 0.2s;
    }

    .btn-back:hover {
        color: #523B1F;
    }

    /* Card Box Utama */
    .detail-card {
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        border: 1px solid #E2E8F0;
        width: 100%;
    }

    /* Bagian Header Card */
    .card-header {
        background-color: #F8E7C1;
        padding: 26px 35px;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 22px;
        color: #6B4F2A;
        font-weight: 700;
        margin: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Badge Status */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .badge-success {
        background-color: #DEF7EC;
        color: #03543F;
    }

    /* Body Card */
    .card-body {
        padding: 20px 35px;
    }

    /* Struktur Baris Baru: Menggunakan Block-Flex untuk Mengunci Elemen Agar Tidak Tabrakan */
    .info-row {
        display: flex;
        padding: 22px 0;
        border-bottom: 1px solid #EDF2F7;
        width: 100%;
        clear: both; /* Memastikan tidak ada float liar */
    }

    .info-row:last-child {
        border-bottom: none;
    }

    /* Mengunci lebar kolom label kiri */
    .info-label-column {
        flex: 0 0 280px; /* Lebar absolut 280px agar ruang judul teks sangat lapang */
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #718096;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .info-label-column i {
        color: #6B4F2A;
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    /* Kolom nilai data kanan mengambil sisa seluruh ruang card */
    .info-value-column {
        flex: 1; 
        font-size: 16px;
        color: #2D3748;
        font-weight: 600;
        line-height: 1.6;
        display: flex;
        align-items: center;
    }

    /* Box Deskripsi */
    .description-box {
        background-color: #F8FAFC;
        padding: 18px;
        border-radius: 8px;
        border-left: 4px solid #6B4F2A;
        color: #4A5568;
        font-style: italic;
        font-weight: 500;
        width: 100%;
        box-sizing: border-box;
    }
</style>

<div class="content-container">
    
    <a href="{{ route('bukti.donasi') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Bukti
    </a>

    <div class="detail-card">
        <div class="card-header">
            <h2>Informasi Penyelesaian Donasi</h2>
            <span class="badge-status badge-success">
                <i class="fa-solid fa-circle-check"></i> {{ $donasi->status }}
            </span>
        </div>
        
        <div class="card-body">
            
            <div class="info-row">
                <div class="info-label-column">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Tanggal Donasi</span>
                </div>
                <div class="info-value-column">
                    {{ $donasi->created_at ? $donasi->created_at->format('d F Y') : '-' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label-column">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                    <span>Penerima</span>
                </div>
                <div class="info-value-column">
                    {{ $donasi->kategori_penerima }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label-column">
                    <i class="fa-solid fa-utensils"></i>
                    <span>Kategori Makanan</span>
                </div>
                <div class="info-value-column">
                    {{ $donasi->kategori_makanan }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label-column">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Lokasi Dropbox</span>
                </div>
                <div class="info-value-column">
                    {{ $donasi->lokasi_dropbox ?? 'Dropbox Pusat - Jl. Merdeka' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label-column">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Wilayah Penyaluran</span>
                </div>
                <div class="info-value-column">
                    {{ $donasi->wilayah ?? 'Bandung Tengah' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label-column">
                    <i class="fa-solid fa-clock"></i>
                    <span>Waktu Layak</span>
                </div>
                <div class="info-value-column">
                    {{ $donasi->waktu_layak ?? '6 - 12 Jam' }}
                </div>
            </div>

            <div class="info-row" style="align-items: flex-start;">
                <div class="info-label-column" style="padding-top: 5px;">
                    <i class="fa-solid fa-comment-dots"></i>
                    <span>Deskripsi</span>
                </div>
                <div class="info-value-column">
                    <div class="description-box">
                        {{ $donasi->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection