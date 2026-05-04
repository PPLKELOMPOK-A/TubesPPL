@extends('layouts.app')

@section('title', 'Foodlink - Beranda')

@section('content')
<!-- CSS Khusus untuk Halaman Beranda User -->
<style>
    .container { max-width: 1100px; width: 100%; margin-left: 0; }
    
    .announcement { background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 12px; padding: 25px; text-align: center; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 30px; }

    .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
    .search-wrapper { flex: 1; position: relative; }
    .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #E0E0E0; border-radius: 8px; font-size: 14px; outline: none; }
    .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #A0A0A0; }
    .btn-filter { padding: 0 20px; border: 1px solid #E0E0E0; border-radius: 8px; background: white; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; color: #444; }

    /* --- DONASI LIST --- */
    .donasi-item { display: flex; align-items: center; justify-content: space-between; padding: 25px 0; border-bottom: 1.5px solid #eee; transition: 0.2s; }
    .donasi-item:hover { background-color: #fafafa; }
    
    .donasi-content { display: flex; align-items: center; flex: 1; text-decoration: none; color: inherit; cursor: pointer; }
    .donasi-img { width: 110px; height: 80px; border-radius: 10px; object-fit: cover; margin-right: 25px; background-color: #f5f5f5; }
    
    .donasi-info { flex: 1; }
    .donasi-info h3 { font-size: 17px; font-weight: 700; color: #000; margin-bottom: 5px; }
    .donasi-info .category { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 12px; display: block; }
    .donasi-info .date { font-size: 13px; color: #999; }

    .btn-action { background-color: #6B4F2A; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; transition: 0.2s; }
    .btn-action:hover { background-color: #5a4223; }
    
    /* --- PAGINATION --- */
    .pagination-footer { display: flex; justify-content: flex-end; align-items: center; margin-top: 30px; gap: 10px; font-size: 12px; color: #888; }
    .page-node { padding: 5px 10px; border: 1px solid #E0E0E0; border-radius: 4px; text-decoration: none; color: #444; }
    .page-node.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
</style>

<!-- Bungkus konten dengan class dari Master Layout -->
<div class="main-content-canvas">
    <div class="container">
        
        <div class="announcement">
            Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB</strong>. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
        </div>

        {{-- KODE NOTIFIKASI SUKSES DENGAN LOGIKA HILANG 5 DETIK --}}
        @if(session('success'))
            <div id="success-alert" class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #c3e6cb; font-weight: 500; transition: opacity 0.5s ease;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    var alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.opacity = '0'; // Animasi memudar
                        setTimeout(function() {
                            alertBox.style.display = 'none'; // Menghilangkan elemen dari halaman
                        }, 500); // Tunggu animasi 0.5 detik selesai
                    }
                }, 5000); // 5000 milidetik = 5 detik
            </script>
        @endif

        <div class="action-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search">
            </div>
            <button class="btn-filter">Filter <i class="fa-solid fa-chevron-down"></i></button>
        </div>

        <!-- LOOPING DATA DARI DATABASE -->
        @forelse($donations as $item)
        <div class="donasi-item">
            <a href="{{ route('user.donasi.detail', ['id' => $item->id]) }}" class="donasi-content">
                
                {{-- Pengecekan Foto (Nama kolom sudah disesuaikan ke foto_kegiatan) --}}
                @if(!empty($item->foto_kegiatan))
                    <img src="{{ asset('storage/' . $item->foto_kegiatan) }}" class="donasi-img" alt="Foto Donasi">
                @else
                    <div class="donasi-img" style="display:flex; align-items:center; justify-content:center; color:#bbb;">
                        <i class="fa-solid fa-image fa-2x"></i>
                    </div> 
                @endif
                
                <div class="donasi-info">
                    {{-- Nama kolom sudah disesuaikan dengan database --}}
                    <span class="category">{{ $item->kategori_penerima }}</span>
                    <h3>{{ $item->judul_donasi }}</h3>
                    <div class="date">{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('l, d F Y') }}</div>
                </div>
            </a>
            
            <div>
                <a href="{{ route('user.donasi.detail', ['id' => $item->id]) }}" class="btn-action" style="text-decoration: none; display: inline-block;">Daftar Donasi</a>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #EAEAEA;">
            Belum ada donasi yang tersedia di Database.
        </div>
        @endforelse

        <!-- PAGINATION DUMMY -->
        <div class="pagination-footer">
            <span>1-5 dari 200</span>
            <a href="#" class="page-node active">1</a>
            <a href="#" class="page-node">2</a>
            <span>...</span>
            <a href="#" class="page-node">9</a>
            <a href="#" class="page-node">10</a>
            <a href="#" class="page-node"><i class="fa-solid fa-chevron-right"></i></a>
        </div>

    </div>
</div>
@endsection