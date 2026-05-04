<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink Admin - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #ffffff; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { width: 260px; background-color: #FDF1D3; display: flex; flex-direction: column; padding: 30px 0; border-right: 1px solid #f0e0b0; }
        .nav-group { flex-grow: 1; padding: 0 20px; }
        .nav-item { display: flex; align-items: center; padding: 12px 15px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 600; gap: 15px; margin-bottom: 25px; border-radius: 8px; transition: 0.3s; }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active i { color: #FFFFFF; }

        .logout-section { padding: 0 25px; margin-bottom: 20px; }
        .logout-btn { display: flex; align-items: center; gap: 12px; color: #4A4A4A; text-decoration: none; font-weight: 600; font-size: 14px; border: none; background: none; cursor: pointer; }

        /* --- MAIN PANEL --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; background-color: #fff; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; border-bottom: 1px solid #f0f0f0; gap: 20px; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-info span { font-weight: 600; color: #6B4F2A; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }

        /* --- CONTENT --- */
        .container { padding: 30px 60px; max-width: 1000px; margin-left: 0; width: 100%; }

        .info-box { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 40px; margin-bottom: 30px; text-align: center; color: #777; font-size: 13px; line-height: 1.6; }

        .search-filter-row { display: flex; gap: 15px; margin-bottom: 30px; }
        .search-wrapper { position: relative; flex: 1; }
        .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        .search-wrapper input { width: 100%; padding: 12px 12px 12px 45px; border: 1px solid #ddd; border-radius: 10px; outline: none; font-size: 14px; background: #fafafa; }
        .filter-btn { padding: 12px 25px; border: 1px solid #ddd; border-radius: 10px; background: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 600; color: #444; }

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
        
        .action-icons { display: flex; gap: 20px; padding: 0 10px; align-items: center; } /* Tambah align-items agar sejajar */
        .action-icons a, .action-icons i { font-size: 20px; color: #333; cursor: pointer; text-decoration: none; }

        /* --- FLOATING ACTION BUTTON --- */
        .fab { position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; background: #FDE6AC; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; text-decoration: none; }
        .fab i { font-size: 24px; color: #333; }

        /* --- PAGINATION --- */
        .pagination-row { display: flex; justify-content: flex-end; align-items: center; margin-top: 40px; gap: 15px; color: #777; font-size: 13px; max-width: 1000px; }
        .page-nav { display: flex; gap: 5px; }
        .page-link { padding: 5px 10px; text-decoration: none; color: #444; border: 1px solid #ddd; border-radius: 4px; font-weight: 600; }
        .page-link.active { background: #6B4F2A; color: #fff; border-color: #6B4F2A; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <a href="{{ route('admin.dashboard') }}" class="nav-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-check-to-slot"></i> Validasi Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Chat</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-users-gear"></i> Penugasan Relawan</a>
        </div>
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-panel">
        <div class="top-bar">
            <i class="fa-regular fa-bell" style="font-size: 20px; color: #aaa;"></i>
            <div class="user-info">
                <span>Admin</span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=6B4F2A&color=fff" class="user-avatar">
            </div>
        </div>

        <div class="container">
            <!-- Alert jika ada pesan sukses -->
            @if(session('success'))
                <div style="background-color: #E6F4EA; border: 1px solid #1E8E3E; color: #1E8E3E; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="info-box">
                Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konformasi dan penjemputan oleh relawan tersedia setiap hari SENIN s.d. MINGGU Pukul 08.00 - 20.00 WIB. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
            </div>

            <!-- FORM FILTER & SEARCH -->
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

            <!-- LOOPING DATA DARI DATABASE -->
            @forelse($semuaDonasi as $item)
            <div class="donasi-item">
                <a href="{{ route('admin.donasi.detail', ['id' => $item->id]) }}" class="donasi-content">
                    @if($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" class="donasi-img" alt="Foto Donasi">
                    @else
                        <img src="https://via.placeholder.com/110x80/f5f5f5/cccccc?text=Foto" class="donasi-img" alt="Belum Ada Foto">
                    @endif
                    
                    <div class="donasi-info">
                        <h3>{{ $item->judul }}</h3>
                        <span class="category">{{ $item->kategori }}</span>
                        <span class="date">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</span>
                    </div>
                </a>
                <div class="action-icons">
                    
                    <!-- PERUBAHAN: Ikon Hapus dibungkus Form agar berfungsi -->
                    <form action="{{ route('admin.donasi.delete', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data donasi ini?');" style="margin: 0; padding: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0; color: #333;">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.donasi.edit', ['id' => $item->id]) }}" style="color: inherit;"><i class="fa-regular fa-pen-to-square"></i></a>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: #888;">
                Belum ada data donasi di database yang sesuai.
            </div>
            @endforelse

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
    </div>

    <a href="{{ route('admin.donasi.create') }}" class="fab">
        <i class="fa-solid fa-plus"></i>
    </a>

    <!-- SCRIPT UNTUK MENGATUR MUNCUL/HILANGNYA KOTAK FILTER -->
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
</body>
</html>