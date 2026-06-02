<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 35px; font-weight: 700; font-size: 24px; color: #6B4F2A; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; gap: 15px; transition: 0.2s; margin-bottom: 6px; border-radius: 10px; }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; font-weight: 600; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }
        .logout-section { padding: 25px 30px; margin-top: auto; }
        .logout-btn { border: none; background: none; cursor: pointer; color: #4A4A4A; display: flex; align-items: center; gap: 15px; font-size: 14px; font-weight: 500; }
        
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; position: relative; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 20px; }
        .admin-profile { display: flex; align-items: center; gap: 10px; }
        .admin-profile span { color: #6B4F2A; font-weight: 600; font-size: 14px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }
        .container { padding: 30px 60px; max-width: 1200px; width: 100%; }
        .announcement { background: white; border: 1px solid #eee; border-radius: 12px; padding: 30px; text-align: center; color: #666; font-size: 13px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        
        /* --- REVISI STRUKTUR SEARCH BAR DAN FILTER WARP --- */
        .action-bar { display: flex; gap: 15px; margin-bottom: 30px; width: 100%; height: 45px; }
        .search-wrapper { flex: 1; position: relative; height: 100%; }
        .search-wrapper input { width: 100%; height: 100%; padding: 0 15px 0 45px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-size: 14px; background: #fafafa; }
        .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        
        .filter-wrapper { position: relative; height: 100%; }
        .btn-filter { height: 100%; padding: 0 25px; border: 1px solid #ddd; border-radius: 8px; background: white; display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #444; font-size: 14px; transition: 0.2s; }
        .btn-filter:hover { background: #f9f9f9; }
        
        .filter-dropdown { display: none; position: absolute; top: 115%; right: 0; width: 250px; background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 8px 16px rgba(0,0,0,0.1); z-index: 100; overflow: hidden; }
        .filter-dropdown.show { display: block; }
        .filter-header { background-color: #563e21; color: #ffffff; text-align: center; padding: 12px; font-size: 13px; font-weight: 600; }
        .filter-options { padding: 10px 0; }
        .filter-option { display: flex; align-items: center; padding: 12px 20px; gap: 12px; font-size: 13px; color: #444; cursor: pointer; transition: 0.2s; text-align: left; }
        .filter-option:hover { background-color: #f9f9f9; }
        .filter-option input[type="checkbox"], .filter-option input[type="radio"] { width: 16px; height: 16px; cursor: pointer; accent-color: #6B4F2A; }

        .donation-card-wrapper { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 15px 0; }
        .donation-card { display: flex; align-items: center; gap: 25px; text-decoration: none; color: inherit; flex: 1; }
        .donation-thumb { width: 140px; height: 100px; border-radius: 10px; object-fit: cover; background: #f5f5f5; }
        .donation-info h3 { font-size: 17px; margin-bottom: 5px; font-weight: 700; color: #000; }
        .donation-info .category { font-size: 13px; color: #6B4F2A; font-weight: 600; margin-bottom: 5px; display: block; }
        .donation-info .meta { font-size: 13px; color: #999; }
        
        .admin-tools { display: flex; gap: 15px; }
        .btn-tool { background: none; border: none; cursor: pointer; font-size: 18px; color: #888; transition: 0.2s; }
        .btn-tool:hover { color: #ef5350; }
        .fab-add { position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; background-color: #F8E7C1; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-decoration: none; }
        .pagination-area { display: flex; justify-content: flex-end; align-items: center; margin: 30px 0 100px; gap: 10px; }
        .page-link { padding: 6px 12px; border: 1px solid #eee; border-radius: 6px; text-decoration: none; color: #444; font-size: 13px; }
        .page-link.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="{{ route('riwayat-donasi.index') }}" class="nav-item">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Donasi
            </a>
            <a href="{{ route('bukti.donasi') }}" class="nav-item">
                <i class="fa-solid fa-file-invoice"></i> Bukti Donasi
            </a>
            <a href="{{ route('tips.index') }}" class="nav-item">
                <i class="fa-solid fa-lightbulb"></i> Beri Tips
            </a>
            <a href="{{ route('donation.tracking') }}" class="nav-item">
                <i class="fa-solid fa-location-dot"></i> Tracking
            </a>
            <a href="{{ route('review.index') }}" class="nav-item">
                <i class="fa-solid fa-star"></i> Rating & Review
            </a>
            <a href="{{ route('komunitas.index') }}" class="nav-item">
                <i class="fa-solid fa-users"></i> Komunitas
            </a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Chat</a>
        </div>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Keluar Akun</button>
            </form>
        </div>
    </div>

    <div class="main-panel">
        <div class="top-bar">
            <i class="fa-regular fa-bell" style="font-size: 18px; color: #4A4A4A; cursor: pointer;"></i>
            <div class="admin-profile">
                <span>{{ Auth::user()->name ?? 'User' }}</span>
                <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name=User&background=6B4F2A&color=fff' }}" class="user-avatar">
            </div>
        </div>

        <div class="container">
            <div class="announcement">
                Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00–20.00 WIB</strong>. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
            </div>

            <form action="/dashboard" method="GET" class="action-bar" id="filterForm">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit();">
                </div>
                
                <div class="filter-wrapper">
                    <button type="button" class="btn-filter" onclick="toggleFilter()">Filter <i class="fa-solid fa-chevron-down" style="font-size: 12px;"></i></button>
                    
                    <div class="filter-dropdown {{ (request()->has('kategori') || request()->has('waktu')) ? 'show' : '' }}" id="filterDropdown">
                        <div class="filter-header">- Pilihan Filter -</div>
                        <div class="filter-options">
                            
                            <div style="padding: 6px 20px 4px 20px; font-weight: 800; font-size: 11px; color: #80756C; letter-spacing: 0.5px; text-transform: uppercase;">Kategori Penerima</div>
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

                            <hr style="border: 0; border-top: 1px solid rgba(209, 196, 185, 0.3); margin: 10px 0;">

                            <div style="padding: 4px 20px 6px 20px; font-weight: 800; font-size: 11px; color: #80756C; letter-spacing: 0.5px; text-transform: uppercase;">Urutan Waktu</div>
                            <label class="filter-option">
                                <input type="radio" name="waktu" value="terbaru" 
                                       onchange="document.getElementById('filterForm').submit();"
                                       {{ request('waktu') == 'terbaru' ? 'checked' : '' }}> 
                                Terbaru (Baru dibuat)
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="waktu" value="terlama" 
                                       onchange="document.getElementById('filterForm').submit();"
                                       {{ request('waktu') == 'terlama' ? 'checked' : '' }}> 
                                Terlama (Postingan awal)
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            @forelse($donations ?? [] as $item)
                <div class="donation-card-wrapper">
                    <a href="{{ route('user.donasi.detail', $item->id) }}" class="donation-card">
                        @if(!empty($item->foto_kegiatan))
                            <img src="{{ asset('storage/' . $item->foto_kegiatan) }}" class="donation-thumb" alt="Foto Donasi">
                        @else
                            <div class="donation-thumb" style="display:flex; align-items:center; justify-content:center; color:#bbb; background-color:#f5f5f5; font-size: 11px; font-weight: bold;">[ NO IMG ]</div> 
                        @endif
                        <div class="donation-info">
                            <span class="category">{{ $item->kategori_penerima ?? 'Umum' }}</span>
                            <h3>{{ $item->judul_donasi ?? 'Judul Donasi' }}</h3>
                            <div class="meta"><i class="fa-regular fa-calendar"></i> {{ isset($item->tanggal_kegiatan) ? \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('l, d F Y') : '-' }}</div>
                        </div>
                    </a>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #EAEAEA;">
                    Belum ada data donasi yang tersedia.
                </div>
            @endforelse

            <div class="pagination-area">
                <span>Menampilkan {{ method_exists($donations, 'count') ? $donations->count() : 0 }} data</span>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>

        <a href="{{ route('donasi.create') }}" class="fab-add" style="background-color: #FDE6AC;">
            <i class="fa-solid fa-plus" style="color: #333; font-size: 20px;"></i>
        </a>
    </div>

    <script>
        function toggleFilter() {
            document.getElementById("filterDropdown").classList.toggle("show");
        }

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
</body>
</html>