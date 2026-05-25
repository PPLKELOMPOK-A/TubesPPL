@extends('layouts.admin') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Foodlink Admin - Dashboard')

@section('content')
<style>
    /* --- CSS KHUSUS HALAMAN DASHBOARD ADMIN --- */
    .container { padding: 30px 50px; max-width: 1100px; margin-left: 0; width: 100%; }

    .announcement { background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 12px; padding: 25px; text-align: center; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 30px; }

    /* --- SEARCH & FILTER --- */
    .search-filter-row { display: flex; gap: 15px; margin-bottom: 30px; }
    .search-wrapper { position: relative; flex: 1; }
    .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
    .search-wrapper input { width: 100%; padding: 12px 12px 12px 45px; border: 1px solid #ddd; border-radius: 10px; outline: none; font-size: 14px; background: #fafafa; }
    .filter-btn { padding: 12px 25px; border: 1px solid #ddd; border-radius: 10px; background: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 600; color: #444; }

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
    
    .action-icons { display: flex; gap: 20px; padding: 0 10px; align-items: center; }
    .action-icons a, .action-icons i { font-size: 20px; color: #333; cursor: pointer; text-decoration: none; transition: 0.2s; }
    .action-icons a:hover, .action-icons button:hover i { color: #6B4F2A; }

    /* --- PAGINATION (Ditambahkan dari tampilan User) --- */
    .pagination-footer { display: flex; justify-content: flex-end; align-items: center; margin-top: 30px; gap: 10px; font-size: 12px; color: #888; }
    .page-node { padding: 5px 10px; border: 1px solid #E0E0E0; border-radius: 4px; text-decoration: none; color: #444; }
    .page-node.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }

    /* --- FLOATING ACTION BUTTON --- */
    .fab { position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; background: #FDE6AC; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; text-decoration: none; z-index: 99; transition: 0.3s; }
    .fab:hover { background: #fcd984; transform: translateY(-3px); }
    .fab i { font-size: 24px; color: #333; }
</style>

<div class="main-content-canvas">
    <div class="container">
        @if(session('success'))
            <div id="success-alert" style="background-color: #E6F4EA; border: 1px solid #1E8E3E; color: #1E8E3E; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-size: 14px; transition: opacity 0.5s ease;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
            
            <script>
                setTimeout(function() {
                    var alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.opacity = '0';
                        setTimeout(function() {
                            alertBox.style.display = 'none';
                        }, 500);
                    }
                }, 5000);
            </script>
        @endif

        <div class="announcement">
            Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB</strong>. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
        </div>

        <form action="{{ route('admin.dashboard') }}" method="GET" class="search-filter-row" id="filterForm">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search" value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit();">
            </div>
            
            <div class="filter-wrapper">
                <button type="button" class="filter-btn" onclick="toggleFilter()">Filter <i class="fa-solid fa-chevron-down"></i></button>
                
                <div class="filter-dropdown {{ request()->has('kategori') ? 'show' : '' }}" id="filterDropdown">
                    <div class="filter-header">-Pilihan-</div>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" name="kategori[]" value="Organisasi (Yayasan)" 
                                   onchange="document.getElementById('filterForm').submit();"
                                   {{ in_array('Organisasi (Yayasan)', request('kategori', [])) ? 'checked' : '' }}> 
                            Organisasi (Yayasan)
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="kategori[]" value="Kegiatan Keagamaan" 
                                   onchange="document.getElementById('filterForm').submit();"
                                   {{ in_array('Kegiatan Keagamaan', request('kategori', [])) ? 'checked' : '' }}> 
                            Kegiatan Keagamaan
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="kategori[]" value="Individu/Umum" 
                                   onchange="document.getElementById('filterForm').submit();"
                                   {{ in_array('Individu/Umum', request('kategori', [])) ? 'checked' : '' }}> 
                            Individu/Umum
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" name="kategori[]" value="Individu" 
                                   onchange="document.getElementById('filterForm').submit();"
                                   {{ in_array('Individu', request('kategori', [])) ? 'checked' : '' }}> 
                            Individu
                        </label>
                    </div>
                </div>
            </div>
        </form>

        @forelse($semuaDonasi as $item)
        <div class="donasi-item">
            <a href="{{ route('admin.donasi.detail', ['id' => $item->id]) }}" class="donasi-content">
                @if(!empty($item->foto_kegiatan))
                    <img src="{{ asset('storage/' . $item->foto_kegiatan) }}" class="donasi-img" alt="Foto Donasi">
                @else
                    <div class="donasi-img" style="display:flex; align-items:center; justify-content:center; color:#bbb; background-color:#f5f5f5;">
                        <i class="fa-solid fa-image fa-2x"></i>
                    </div> 
                @endif
                
                <div class="donasi-info">
                    <span class="category">{{ $item->kategori_penerima }}</span>
                    <h3>{{ $item->judul_donasi }}</h3>
                    <div class="date">{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('l, d F Y') }}</div>
                </div>
            </a>
            
            <div class="action-icons">
                <form action="{{ route('admin.donasi.delete', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data donasi ini?');" style="margin: 0; padding: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0; color: #333;">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </form>

                <a href="{{ route('admin.donasi.edit', ['id' => $item->id]) }}"><i class="fa-regular fa-pen-to-square"></i></a>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #EAEAEA;">
            Belum ada data donasi di database yang sesuai.
        </div>
        @endforelse

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

<a href="{{ route('admin.donasi.create') }}" class="fab">
    <i class="fa-solid fa-plus"></i>
</a>

<script>
    function toggleFilter() {
        document.getElementById("filterDropdown").classList.toggle("show");
    }

    // Menutup dropdown jika user melakukan klik di luar kotak filter
    window.onclick = function(event) {
        if (!event.target.closest('.filter-wrapper')) {
            var dropdowns = document.getElementsByClassName("filter-dropdown");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
@endsection