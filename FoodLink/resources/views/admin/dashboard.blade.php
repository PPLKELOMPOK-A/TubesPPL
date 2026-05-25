<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink Admin - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 35px; font-weight: 700; font-size: 24px; color: #6B4F2A; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { 
            display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; gap: 15px; transition: 0.2s;
            margin-bottom: 6px; border-radius: 10px;
        }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; font-weight: 600; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }

        .logout-section { padding: 25px 30px; margin-top: auto; }
        .logout-btn { border: none; background: none; cursor: pointer; color: #4A4A4A; display: flex; align-items: center; gap: 15px; font-size: 14px; font-weight: 500; }

        /* --- MAIN PANEL --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; position: relative; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 20px; }
        .top-bar i { color: #ccc; font-size: 20px; }
        .admin-profile { display: flex; align-items: center; gap: 10px; }
        .admin-profile span { color: #6B4F2A; font-weight: 600; font-size: 14px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }

        /* --- CONTENT --- */
        .container { padding: 30px 60px; max-width: 1200px; width: 100%; margin-left: 0; }
        .announcement { background: white; border: 1px solid #eee; border-radius: 12px; padding: 40px; text-align: center; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
        .search-wrapper { flex: 1; position: relative; }
        .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; color: #666; }
        .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #aaa; }
        .btn-filter { padding: 0 20px; border: 1px solid #ddd; border-radius: 8px; background: white; color: #444; font-size: 13px; display: flex; align-items: center; gap: 10px; cursor: pointer; }

        /* --- LIST DONASI --- */
        .donation-card-wrapper { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 15px 0; }
        .donation-card { display: flex; align-items: center; gap: 25px; text-decoration: none; color: inherit; flex: 1; }
        .donation-thumb { width: 140px; height: 100px; border-radius: 10px; object-fit: cover; background: #f5f5f5; }
        .donation-info { flex: 1; }
        .donation-info h3 { font-size: 17px; color: #111; margin-bottom: 5px; font-weight: 700; }
        .donation-info .category { font-size: 13px; color: #444; margin-bottom: 8px; display: block; }
        .donation-info .meta { font-size: 13px; color: #999; }
        .admin-tools { display: flex; gap: 20px; padding-right: 20px; }
        .btn-tool { background: none; border: none; cursor: pointer; font-size: 22px; color: #444; transition: 0.2s; text-decoration: none; }
        .btn-tool:hover { color: #6B4F2A; }
        .fab-add { position: fixed; bottom: 40px; right: 40px; width: 60px; height: 60px; background-color: #F8E7C1; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); cursor: pointer; transition: 0.3s; }
        .fab-add i { font-size: 24px; color: #111; }
        .pagination-area { display: flex; justify-content: flex-end; align-items: center; margin-top: 40px; gap: 15px; font-size: 13px; color: #666; padding-bottom: 100px; }
        .page-link { padding: 5px 12px; border: 1px solid #eee; border-radius: 4px; text-decoration: none; color: #444; }
        .page-link.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="{{ route('validasi.index') }}" class="nav-item"><i class="fa-solid fa-check-to-slot"></i> Validasi Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Chat</a>
            <a href="{{ route('admin.retur.index') }}" class="nav-item"><i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-users-gear"></i> Penugasan Relawan</a>
        </div>
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">@csrf
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
            <div class="announcement">
                Pengajuan donasi makanan dapat dilakukan setiap hari melalui aplikasi. Jam operasional layanan konfirmasi dan penjemputan oleh relawan tersedia setiap hari SENIN s.d. MINGGU Pukul 08.00–20.00 WIB. Donasi yang masuk di luar jam operasional akan diproses untuk koordinasi penjemputan pada keesokan harinya mulai pukul 08.00 WIB.
            </div>

            <div class="action-bar">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search">
                </div>
                <button class="btn-filter">Filter <i class="fa-solid fa-chevron-down"></i></button>
            </div>

            @php
                // Mengambil data donasi terbaru dari session
                $sessionData = session('donasi_data');
                
                // Format tanggal dinamis dari session jika ada
                $tglDinamis = isset($sessionData['tanggal']) 
                    ? \Carbon\Carbon::parse($sessionData['tanggal'])->translatedFormat('l, d F Y') 
                    : 'Kamis, 13 Mei 2026';

                $donations = [
                    [
                        'id' => 1, 
                        'judul' => $sessionData['judul'] ?? 'Hari Anak Nasional - Panti Bunda Kasih', 
                        'org' => $sessionData['kategori'] ?? 'Organisasi (Yayasan)', 
                        'tgl' => $tglDinamis, 
                        'img' => isset($sessionData['foto']) ? asset('storage/'.$sessionData['foto']) : 'https://via.placeholder.com/140x100?text=Donasi+1', 
                        'desc' => $sessionData['deskripsi'] ?? 'Tersedia 20 paket nasi kotak ayam bakar sisa acara syukuran siang ini...', 
                        'alamat' => $sessionData['alamat'] ?? 'Jl. Bougenville Timur No. 22'
                    ],
                    ['id' => 2, 'judul' => 'Program Makan Sehat - Yayasan Peduli Sesama', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Jumat, 31 Mei 2025', 'img' => 'https://via.placeholder.com/140x100?text=Donasi+2', 'desc' => 'Program makanan bergizi untuk sesama.', 'alamat' => 'Jl. Mawar No. 10'],
                    ['id' => 3, 'judul' => 'Donasi Kasih Natal - Gereja Santo Paulus', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Sabtu, 01 Juni 2025', 'img' => 'https://via.placeholder.com/140x100?text=Donasi+3', 'desc' => 'Paket sembako dan makanan siap saji.', 'alamat' => 'Jl. Melati No. 5'],
                    ['id' => 4, 'judul' => 'Jumat Berkah - Masjid Agung', 'org' => 'Kegiatan Keagamaan', 'tgl' => 'Jumat, 06 Juni 2025', 'img' => 'https://via.placeholder.com/140x100?text=Donasi+4', 'desc' => 'Nasi kotak untuk jamaah jumat.', 'alamat' => 'Jl. Al-Ikhlas No. 1'],
                    ['id' => 5, 'judul' => 'Hari Anak Nasional - Yayasan Sejahtera', 'org' => 'Organisasi (Yayasan)', 'tgl' => 'Senin, 23 Juli 2025', 'img' => 'https://via.placeholder.com/140x100?text=Donasi+5', 'desc' => 'Pesta makanan untuk anak-anak yayasan.', 'alamat' => 'Jl. Merdeka No. 100'],
                ];
            @endphp

            @foreach($donations as $item)
            <div class="donation-card-wrapper">
                <a href="{{ route('admin.donasi.detail', [
                    'judul' => $item['judul'], 
                    'org' => $item['org'], 
                    'tgl' => $item['tgl'], 
                    'desc' => $item['desc'], 
                    'alamat' => $item['alamat'],
                    'img_raw' => $item['img']
                ]) }}" class="donation-card">
                    <img src="{{ $item['img'] }}" class="donation-thumb">
                    <div class="donation-info">
                        <h3>{{ $item['judul'] }}</h3>
                        <span class="category">{{ $item['org'] }}</span>
                        <div class="meta">{{ $item['tgl'] }}</div>
                    </div>
                </a>
                
                <div class="admin-tools">
                    <form action="#" method="POST" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-tool"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                    <a href="{{ route('admin.donasi.edit') }}" class="btn-tool"><i class="fa-solid fa-pen-to-square"></i></a>
                </div>
            </div>
            @endforeach

            <div class="pagination-area">
                <span>1-5 dari 200</span>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <span>...</span>
                <a href="#" class="page-link">10</a>
                <a href="#" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="fab-add">
            <i class="fa-solid fa-plus"></i>
        </div>
    </div>

</body>
</html>