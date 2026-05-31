<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink Admin - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* CSS Tetap Sama Seperti Sebelumnya */
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
        .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
        .search-wrapper { flex: 1; position: relative; }
        .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #aaa; }
        .btn-filter { padding: 0 20px; border: 1px solid #ddd; border-radius: 8px; background: white; display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .donation-card-wrapper { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 15px 0; }
        .donation-card { display: flex; align-items: center; gap: 25px; text-decoration: none; color: inherit; flex: 1; }
        .donation-thumb { width: 140px; height: 100px; border-radius: 10px; object-fit: cover; background: #f5f5f5; }
        .donation-info h3 { font-size: 17px; margin-bottom: 5px; }
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

    <!-- Sidebar Modular -->
    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="{{ route('validasi.index') }}" class="nav-item {{ request()->routeIs('validasi.index') ? 'active' : '' }}">
                <i class="fa-solid fa-check-to-slot"></i> Validasi Donasi
            </a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Chat</a>
            <a href="{{ route('admin.retur.index') }}" class="nav-item {{ request()->routeIs('admin.retur.index') ? 'active' : '' }}">
                <i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi
            </a>
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
            <i class="fa-regular fa-bell"></i>
            <div class="admin-profile">
                <span>Admin</span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=6B4F2A&color=fff" class="user-avatar">
            </div>
        </div>

        <div class="container">
            <!-- Pengumuman Statis -->
            <div class="announcement">
                Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari <strong>SENIN s.d. MINGGU Pukul 08.00–20.00 WIB</strong>.
            </div>

            <!-- Toolbar Pencarian -->
            <div class="action-bar">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Cari donasi...">
                </div>
                <button class="btn-filter">Filter <i class="fa-solid fa-chevron-down"></i></button>
            </div>

            <!-- Loop Data Donasi -->
            @forelse($donations as $item)
                <div class="donation-card-wrapper">
                    <a href="{{ route('admin.donasi.detail', ['id' => $item['id']]) }}" class="donation-card">
                        <img src="{{ $item['img'] }}" class="donation-thumb" alt="Thumbnail">
                        <div class="donation-info">
                            <span class="category">{{ $item['org'] }}</span>
                            <h3>{{ $item['judul'] }}</h3>
                            <div class="meta"><i class="fa-regular fa-calendar"></i> {{ $item['tgl'] }}</div>
                        </div>
                    </a>
                    
                    <div class="admin-tools">
                        <a href="{{ route('admin.donasi.edit', $item['id']) }}" class="btn-tool" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.donasi.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Hapus donasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-tool" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="announcement">Belum ada data donasi terbaru.</div>
            @endforelse

            <!-- Navigasi Halaman -->
            <div class="pagination-area">
                <span>Menampilkan {{ count($donations) }} data</span>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>

        <a href="{{ route('admin.donasi.create') }}" class="fab-add">
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>

</body>
</html>