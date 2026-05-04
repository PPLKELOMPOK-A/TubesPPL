<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR USER (KEMBALI KE VERSI USER AWAL) --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        
        .nav-group { flex-grow: 1; padding: 0 15px; }
        
        .nav-item { 
            display: flex; 
            align-items: center; 
            padding: 12px 20px; 
            text-decoration: none; 
            color: #4A4A4A; 
            font-size: 14px; 
            font-weight: 500; 
            transition: 0.2s; 
            gap: 15px; 
            margin-bottom: 6px; 
            border-radius: 10px; 
        }
        
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }

        .logout-section { padding: 0 15px; margin-top: auto; }
        
        .logout-btn { 
            border: none; 
            background: none; 
            width: 100%; 
            text-align: left; 
            cursor: pointer; 
            color: #d9534f; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            padding: 12px 20px; 
            font-size: 14px; 
            font-weight: 500; 
            border-radius: 10px;
            transition: 0.2s;
        }
        
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }

        /* --- MAIN CONTENT --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; }
        
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        
        .profile-section { display: flex; align-items: center; gap: 12px; transition: 0.2s; }
        .profile-section:hover { opacity: 0.8; } /* Sedikit efek hover agar terasa bisa diklik */
        
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }

        /* KONTEN MERAPAT KE KIRI */
        .container { padding: 30px 50px; max-width: 1100px; width: 100%; margin-left: 0; }
        
        .announcement { background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 12px; padding: 25px; text-align: center; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 30px; }

        .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
        
        .search-wrapper { flex: 1; position: relative; }
        
        .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #E0E0E0; border-radius: 8px; font-size: 14px; outline: none; }
        
        .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #A0A0A0; }
        
        .btn-filter { padding: 12px 25px; border: 1px solid #E0E0E0; border-radius: 8px; background: white; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #444; }

        /* --- FILTER DROPDOWN UI --- */
        .filter-wrapper { position: relative; }
        
        .filter-dropdown {
            display: none; 
            position: absolute;
            top: 115%;
            right: 0;
            width: 250px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 100;
            overflow: hidden;
        }
        .filter-dropdown.show { display: block; }
        .filter-header {
            background-color: #563e21; 
            color: #ffffff;
            text-align: center;
            padding: 12px;
            font-size: 13px;
            font-weight: 600;
        }
        .filter-options { padding: 10px 0; }
        .filter-option {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            gap: 12px;
            font-size: 13px;
            color: #444;
            cursor: pointer;
            transition: 0.2s;
        }
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

        .btn-action { background-color: #6B4F2A; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; }
        
        /* --- PAGINATION --- */
        .pagination-footer { display: flex; justify-content: flex-end; align-items: center; margin-top: 30px; gap: 10px; font-size: 12px; color: #888; }
        .page-node { padding: 5px 10px; border: 1px solid #E0E0E0; border-radius: 4px; text-decoration: none; color: #444; }
        .page-node.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="#" class="nav-item active">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-hand-holding-heart"></i> Riwayat Donasi
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-comments"></i> Riwayat Koordinasi
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi
            </a>
        </div>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar Akun
                </button>
            </form>
        </div>
    </div>

    <div class="main-panel">
        <div class="top-bar">
            <i class="fa-regular fa-bell"></i>
            
            <!-- PERUBAHAN: Tag <div> diganti jadi <a> dan ditambahkan href agar bisa diklik -->
            <a href="{{ route('profil') }}" class="profile-section" style="text-decoration: none; cursor: pointer;">
                <span style="font-size: 13px; font-weight: 600; color: #444;">{{ Auth::user()->name ?? 'User' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=6B4F2A&color=fff" class="user-avatar" alt="User">
            </a>
        </div>

        <div class="container">
            <div class="announcement">
                Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB</strong>. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
            </div>

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
                        </div>
                    </div>
                </div>
            </form>

            @forelse($donations as $item)
            <div class="donasi-item">
                <a href="{{ route('user.donasi.detail', ['id' => $item->id]) }}" class="donasi-content">
                    @if(!empty($item->foto))
                        <img src="{{ asset('storage/' . $item->foto) }}" class="donasi-img" alt="Foto Donasi">
                    @else
                        <div class="donasi-img"></div> 
                    @endif
                    
                    <div class="donasi-info">
                        <span class="category">{{ $item->kategori }}</span>
                        <h3>{{ $item->judul }}</h3>
                        <div class="date">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</div>
                    </div>
                </a>
                
                <div>
                    <button class="btn-action">Daftar Donasi</button>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: #888;">
                Belum ada donasi yang tersedia di Database.
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