@extends('layouts.app')

@section('title', 'Foodlink - Beranda')

@section('content')
<style>
    /* --- GENERAL LAYOUT --- */
    .container { padding: 30px 50px; max-width: 1100px; width: 100%; margin-left: 0; }
    
    .announcement { background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 12px; padding: 25px; text-align: center; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 30px; }

    .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
    .search-wrapper { flex: 1; position: relative; }
    .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #E0E0E0; border-radius: 8px; font-size: 14px; outline: none; }
    .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #A0A0A0; }
    .btn-filter { padding: 12px 25px; border: 1px solid #E0E0E0; border-radius: 8px; background: white; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #444; }

    /* --- FILTER DROPDOWN UI --- */
    .filter-wrapper { position: relative; }
    .filter-dropdown { display: none; position: absolute; top: 115%; right: 0; width: 250px; background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 8px 16px rgba(0,0,0,0.1); z-index: 100; overflow: hidden; }
    .filter-dropdown.show { display: block; }
    .filter-header { background-color: #563e21; color: #ffffff; text-align: center; padding: 12px; font-size: 13px; font-weight: 600; }
    .filter-options { padding: 10px 0; }
    .filter-option { display: flex; align-items: center; padding: 12px 20px; gap: 12px; font-size: 13px; color: #444; cursor: pointer; transition: 0.2s; }
    .filter-option:hover { background-color: #f9f9f9; }
    .filter-option input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: #6B4F2A; }

    /* --- DONASI LIST --- */
    .donasi-item { display: flex; align-items: center; justify-content: space-between; padding: 25px 0; border-bottom: 1.5px solid #eee; transition: 0.2s; }
    .donasi-item:hover { background-color: #fafafa; }
    .donasi-content { display: flex; align-items: center; flex: 1; text-decoration: none; color: inherit; cursor: pointer; }
    .donasi-img { width: 110px; height: 80px; border-radius: 10px; object-fit: cover; margin-right: 25px; background-color: #f5f5f5; }
    .donasi-info { flex: 1; }
    .donasi-info h3 { font-size: 17px; font-weight: 700; color: #000; margin-bottom: 5px; }
    .donasi-info .category { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 12px; display: block; }
    .donasi-info .date { font-size: 13px; color: #999; }

    .btn-action { background-color: #6B4F2A; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; transition: 0.2s; text-decoration: none; display: inline-block; }
    .btn-action:hover { background-color: #5a4223; }
    
    /* --- PAGINATION --- */
    .pagination-footer { display: flex; justify-content: flex-end; align-items: center; margin-top: 30px; gap: 10px; font-size: 12px; color: #888; }
    .page-node { padding: 5px 10px; border: 1px solid #E0E0E0; border-radius: 4px; text-decoration: none; color: #444; }
    .page-node.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
</style>

<div class="main-content-canvas">
    <div class="container">
        
                <div class="announcement">

            Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB</strong>. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.

        </div>

        @if(session('success'))
            <div id="success-alert" class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #c3e6cb; font-weight: 500; transition: opacity 0.5s ease;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    var alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.opacity = '0';
                        setTimeout(function() { alertBox.style.display = 'none'; }, 500);
                    }
                }, 5000);
            </script>
        @endif

        <form action="{{ route('dashboard') }}" method="GET" class="action-bar" id="filterForm">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search" value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit();">
            </div>
            
            <div class="filter-wrapper">
                <button type="button" class="btn-filter" onclick="toggleFilter()">Filter <i class="fa-solid fa-chevron-down"></i></button>
                
                <div class="filter-dropdown {{ request()->has('kategori') ? 'show' : '' }}" id="filterDropdown">
                    <div class="filter-header">-Pilihan-</div>
                    <div class="filter-options">
                        @foreach(['Organisasi (Yayasan)', 'Kegiatan Keagamaan', 'Individu'] as $kat)
                        <label class="filter-option">
                            <input type="checkbox" name="kategori[]" value="{{ $kat }}"
                                   onchange="document.getElementById('filterForm').submit();"
                                   {{ in_array($kat, request('kategori', [])) ? 'checked' : '' }}> 
                            {{ $kat }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>

        @forelse($donations as $item)
        <div class="donasi-item">
            <a href="{{ route('user.donasi.detail', ['id' => $item->id]) }}" class="donasi-content">
                @if(!empty($item->foto))
                    <img src="{{ asset('storage/' . $item->foto) }}" class="donasi-img" alt="Foto">
                @else
                    <div class="donasi-img" style="background:#f5f5f5; display:flex; align-items:center; justify-content:center;">
                        <i class="fa-solid fa-image"></i>
                    </div> 
                @endif
                
                <div class="donasi-info">
                    <span class="category">{{ $item->kategori }}</span>
                    <h3>{{ $item->judul }}</h3>
                    <div class="date">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</div>
                </div>
            </a>
            <div>
                <a href="{{ route('user.donasi.detail', ['id' => $item->id]) }}" class="btn-action">Daftar Donasi</a>
            </div>
        </div>
        @empty
        <p style="text-align: center; padding: 20px;">Belum ada donasi yang tersedia.</p>
        @endforelse

        <div class="pagination-footer">
            {{ $donations->links() }}
        </div>
    </div>
</div>

<script>
    function toggleFilter() { document.getElementById("filterDropdown").classList.toggle("show"); }
    window.onclick = function(event) {
        if (!event.target.closest('.filter-wrapper')) {
            document.getElementById("filterDropdown").classList.remove('show');
        }
    }
</script>
@endsection