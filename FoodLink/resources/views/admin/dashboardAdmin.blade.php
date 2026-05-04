@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utama Anda, misal: layouts.admin jika dipisah --}}

@section('title', 'Foodlink Admin - Dashboard')

@section('content')
<style>
    /* --- CSS KHUSUS HALAMAN DASHBOARD ADMIN --- */
    .container { padding: 30px 60px; max-width: 1000px; margin-left: 0; width: 100%; }

    .info-box { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 40px; margin-bottom: 30px; text-align: center; color: #777; font-size: 13px; line-height: 1.6; }

    .search-filter-row { display: flex; gap: 15px; margin-bottom: 30px; }
    .search-wrapper { position: relative; flex: 1; }
    .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
    .search-wrapper input { width: 100%; padding: 12px 12px 12px 45px; border: 1px solid #ddd; border-radius: 10px; outline: none; font-size: 14px; background: #fafafa; }
    .filter-btn { padding: 12px 25px; border: 1px solid #ddd; border-radius: 10px; background: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 600; color: #444; }

    /* --- DONASI LIST --- */
    .donasi-item { display: flex; align-items: center; justify-content: space-between; padding: 25px 0; border-bottom: 1.5px solid #eee; transition: 0.2s; }
    .donasi-item:hover { background-color: #fafafa; }
    
    .donasi-content { display: flex; align-items: center; flex: 1; text-decoration: none; color: inherit; cursor: pointer; }
    .donasi-img { width: 110px; height: 80px; border-radius: 10px; object-fit: cover; margin-right: 25px; background-color: #f5f5f5; }
    
    .donasi-info { flex: 1; }
    .donasi-info h3 { font-size: 17px; font-weight: 700; color: #000; margin-bottom: 5px; }
    .donasi-info .category { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 12px; display: block; }
    .donasi-info .date { font-size: 13px; color: #999; }
    
    .action-icons { display: flex; gap: 20px; padding: 0 10px; }
    .action-icons a, .action-icons i { font-size: 20px; color: #333; cursor: pointer; text-decoration: none; }

    /* --- FLOATING ACTION BUTTON --- */
    .fab { position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; background: #FDE6AC; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; text-decoration: none; z-index: 99; }
    .fab i { font-size: 24px; color: #333; }

    /* --- PAGINATION --- */
    .pagination-row { display: flex; justify-content: flex-end; align-items: center; margin-top: 40px; gap: 15px; color: #777; font-size: 13px; max-width: 1000px; }
    .page-nav { display: flex; gap: 5px; }
    .page-link { padding: 5px 10px; text-decoration: none; color: #444; border: 1px solid #ddd; border-radius: 4px; font-weight: 600; }
    .page-link.active { background: #6B4F2A; color: #fff; border-color: #6B4F2A; }
</style>

<div class="container">
    <div class="info-box">
        Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konformasi dan penjemputan oleh relawan tersedia setiap hari SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
    </div>

    <div class="search-filter-row">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search">
        </div>
        <button class="filter-btn">Filter <i class="fa-solid fa-chevron-down"></i></button>
    </div>

    <!-- List Item 1 (Utama - Dinamis terhubung ke Session dengan ID 1) -->
    <div class="donasi-item">
        <a href="{{ route('admin.donasi.detail', ['id' => 1]) }}" class="donasi-content">
            @if(!empty($semuaDonasi[1]['foto']))
                <img src="{{ asset('storage/' . $semuaDonasi[1]['foto']) }}" class="donasi-img" alt="Foto Donasi">
            @else
                <img src="https://via.placeholder.com/110x80/f5f5f5/cccccc?text=Foto" class="donasi-img" alt="Belum Ada Foto">
            @endif
            
            <div class="donasi-info">
                <h3>{{ $semuaDonasi[1]['judul'] ?? 'Hari Anak Nasional - Panti Bunda Kasih' }}</h3>
                <span class="category">{{ $semuaDonasi[1]['kategori'] ?? 'Organisasi (Yayasan)' }}</span>
                <span class="date">{{ isset($semuaDonasi[1]['tanggal']) ? \Carbon\Carbon::parse($semuaDonasi[1]['tanggal'])->translatedFormat('l, d F Y') : 'Kamis, 30 Mei 2025' }}</span>
            </div>
        </a>
        <div class="action-icons">
            <i class="fa-regular fa-trash-can"></i>
            <a href="{{ route('admin.donasi.edit', ['id' => 1]) }}" style="color: inherit;"><i class="fa-regular fa-pen-to-square"></i></a>
        </div>
    </div>

    <!-- List Item 2 (Membawa ID 2) -->
    <div class="donasi-item">
        <a href="{{ route('admin.donasi.detail', ['id' => 2]) }}" class="donasi-content">
            <img src="https://via.placeholder.com/110x80/f5f5f5/cccccc?text=Foto" class="donasi-img" alt="Foto Donasi">
            <div class="donasi-info">
                <h3>Program Makan Sehat - Yayasan Peduli Sesama</h3>
                <span class="category">Organisasi (Yayasan)</span>
                <span class="date">Kamis, 30 Mei 2025</span>
            </div>
        </a>
        <div class="action-icons">
            <i class="fa-regular fa-trash-can"></i>
            <a href="{{ route('admin.donasi.edit', ['id' => 2]) }}" style="color: inherit;"><i class="fa-regular fa-pen-to-square"></i></a>
        </div>
    </div>

    <!-- List Item 3 (Membawa ID 3) -->
    <div class="donasi-item">
        <a href="{{ route('admin.donasi.detail', ['id' => 3]) }}" class="donasi-content">
            <img src="https://via.placeholder.com/110x80/f5f5f5/cccccc?text=Foto" class="donasi-img" alt="Foto Donasi">
            <div class="donasi-info">
                <h3>Donasi Kasih Natal - Gereja Santo Paulus</h3>
                <span class="category">Organisasi (Yayasan)</span>
                <span class="date">Kamis, 30 Mei 2025</span>
            </div>
        </a>
        <div class="action-icons">
            <i class="fa-regular fa-trash-can"></i>
            <a href="{{ route('admin.donasi.edit', ['id' => 3]) }}" style="color: inherit;"><i class="fa-regular fa-pen-to-square"></i></a>
        </div>
    </div>

    <!-- List Item 4 (Membawa ID 4) -->
    <div class="donasi-item">
        <a href="{{ route('admin.donasi.detail', ['id' => 4]) }}" class="donasi-content">
            <img src="https://via.placeholder.com/110x80/f5f5f5/cccccc?text=Foto" class="donasi-img" alt="Foto Donasi">
            <div class="donasi-info">
                <h3>Jumat Berkah - Masjid Agung</h3>
                <span class="category">Kegiatan Keagamaan</span>
                <span class="date">Kamis, 30 Mei 2025</span>
            </div>
        </a>
        <div class="action-icons">
            <i class="fa-regular fa-trash-can"></i>
            <a href="{{ route('admin.donasi.edit', ['id' => 4]) }}" style="color: inherit;"><i class="fa-regular fa-pen-to-square"></i></a>
        </div>
    </div>

    <!-- List Item 5 (Membawa ID 5) -->
    <div class="donasi-item">
        <a href="{{ route('admin.donasi.detail', ['id' => 5]) }}" class="donasi-content">
            <img src="https://via.placeholder.com/110x80/f5f5f5/cccccc?text=Foto" class="donasi-img" alt="Foto Donasi">
            <div class="donasi-info">
                <h3>Hari Anak Nasional - Yayasan Sejahtera</h3>
                <span class="category">Organisasi (Yayasan)</span>
                <span class="date">Kamis, 30 Mei 2025</span>
            </div>
        </a>
        <div class="action-icons">
            <i class="fa-regular fa-trash-can"></i>
            <a href="{{ route('admin.donasi.edit', ['id' => 5]) }}" style="color: inherit;"><i class="fa-regular fa-pen-to-square"></i></a>
        </div>
    </div>

    <div class="pagination-row">
        <span>1-5 dari 200</span>
        <div class="page-nav">
            <a href="#" class="page-link"><i class="fa-solid fa-chevron-left"></i></a>
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <span>...</span>
            <a href="#" class="page-link">9</a>
            <a href="#" class="page-link">10</a>
            <a href="#" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
</div>

<a href="{{ route('admin.kegiatan.create') }}" class="fab">
    <i class="fa-solid fa-plus"></i>
</a>
@endsection