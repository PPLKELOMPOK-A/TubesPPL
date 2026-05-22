@extends('layouts.app')

@section('title', 'Foodlink - Beranda')

@section('styles')
<style>
    .announcement { background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 12px; padding: 25px; text-align: center; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 30px; }

    .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
    
    .search-wrapper { flex: 1; position: relative; }
    
    .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #E0E0E0; border-radius: 8px; font-size: 14px; outline: none; }
    
    .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #A0A0A0; }
    
    .btn-filter { padding: 0 20px; border: 1px solid #E0E0E0; border-radius: 8px; background: white; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; color: #444; }

    .donation-card { display: flex; align-items: center; background: white; padding: 15px 0; border-bottom: 1px solid #F0F0F0; gap: 20px; }
    
    .donation-thumb { width: 110px; height: 80px; border-radius: 8px; object-fit: cover; background-color: #f5f5f5; }
    
    .donation-detail { flex: 1; }
    
    .donation-detail .category { font-size: 12px; color: #6B4F2A; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; display: block; }
    
    .donation-detail h3 { font-size: 16px; color: #1A1A1A; margin-bottom: 4px; font-weight: 600; }
    
    .donation-detail .meta { font-size: 12px; color: #999; }

    .btn-action { background-color: #6B4F2A; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; }
    
    .pagination-footer { display: flex; justify-content: flex-end; align-items: center; margin-top: 30px; gap: 10px; font-size: 12px; color: #888; }
    
    .page-node { padding: 5px 10px; border: 1px solid #E0E0E0; border-radius: 4px; text-decoration: none; color: #444; }
    
    .page-node.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
</style>
@endsection

@section('content')
            <div class="announcement">
                Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB</strong>. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
            </div>

            <div class="action-bar">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search">
                </div>
                <button class="btn-filter">Filter <i class="fa-solid fa-chevron-down"></i></button>
            </div>

            @php
                $donations = [
                    ['judul' => 'Hari Anak Nasional - Panti Bunda Kasih', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Kamis, 30 Mei 2025'],
                    ['judul' => 'Program Makan Sehat - Yayasan Peduli Sesama', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Kamis, 30 Mei 2025'],
                    ['judul' => 'Donasi Kasih Natal - Gereja Santo Paulus', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Kamis, 30 Mei 2025'],
                    ['judul' => 'Jumat Berkah - Masjid Agung Jakarta', 'org' => 'Kegiatan Keagamaan', 'tgl' => 'Kamis, 30 Mei 2025'],
                    ['judul' => 'Santunan Anak Yatim - Yayasan Sejahtera', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Kamis, 30 Mei 2025'],
                ];
            @endphp

            @foreach($donations as $item)
            <div class="donation-card">
                <div class="donation-thumb"></div> 
                <div class="donation-detail">
                    <span class="category">{{ $item['org'] }}</span>
                    <h3>{{ $item['judul'] }}</h3>
                    <div class="meta">{{ $item['tgl'] }}</div>
                </div>
                <button class="btn-action">Daftar Donasi</button>
            </div>
            @endforeach

            <div class="pagination-footer">
                <span>1-5 dari 200</span>
                <a href="#" class="page-node active">1</a>
                <a href="#" class="page-node">2</a>
                <span>...</span>
                <a href="#" class="page-node">9</a>
                <a href="#" class="page-node">10</a>
                <a href="#" class="page-node"><i class="fa-solid fa-chevron-right"></i></a>
            </div>

@endsection