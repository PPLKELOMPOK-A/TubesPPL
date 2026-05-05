<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink - Detail Donasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR USER --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; transition: 0.2s; gap: 15px; margin-bottom: 6px; border-radius: 10px; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        
        /* Menu Active */
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }

        .logout-section { padding: 0 15px; margin-top: auto; }
        .logout-btn { border: none; background: none; width: 100%; text-align: left; cursor: pointer; color: #d9534f; display: flex; align-items: center; gap: 15px; padding: 12px 20px; font-size: 14px; font-weight: 500; border-radius: 10px; transition: 0.2s; }
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }

        /* --- MAIN PANEL USER --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; }
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        .profile-section { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }

        /* --- CONTAINER DETAIL --- */
        .container { padding: 40px 60px; max-width: 1000px; width: 100%; margin-left: 0; margin-right: auto; }

        .back-nav { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #eee; color: #444; text-decoration: none; margin-bottom: 20px; transition: 0.2s; }
        .back-nav:hover { background: #e0e0e0; color: #000; }

        .header-info { margin-bottom: 30px; }
        .header-info h1 { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 5px; }
        .header-info .category { font-size: 14px; color: #6B4F2A; font-weight: 600; margin-bottom: 5px; display: block; }
        .header-info .date { font-size: 14px; color: #999; }

        .image-container { width: 450px; height: 300px; border-radius: 12px; overflow: hidden; border: 1px solid #eee; margin-bottom: 30px; background-color: #f5f5f5; }
        .image-container img { width: 100%; height: 100%; object-fit: cover; }

        .content-section { margin-bottom: 25px; max-width: 600px; }
        .section-title { font-size: 16px; font-weight: 700; color: #444; margin-bottom: 10px; }
        .section-text { font-size: 14px; color: #666; line-height: 1.6; }

        .footer-actions { display: flex; justify-content: flex-start; gap: 15px; margin-top: 40px; padding-bottom: 50px; }
        
        .btn-action { background-color: #6B4F2A; color: white; border: none; padding: 12px 40px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; }
        .btn-action:hover { background-color: #563e21; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('dashboard') }}" class="nav-item active">
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
            <div class="profile-section">
                <span style="font-size: 13px; font-weight: 600; color: #444;">{{ Auth::user()->name ?? 'User' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=6B4F2A&color=fff" class="user-avatar" alt="User">
            </div>
        </div>

        <div class="container">
            <a href="{{ route('dashboard') }}" class="back-nav" title="Kembali ke Beranda">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div class="header-info">
                <!-- DIUBAH: Menggunakan ->judul -->
                <h1>{{ $data->judul }}</h1>
                <span class="category">{{ $data->kategori }}</span>
                <p class="date">{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}</p>
            </div>

            <div class="image-container">
                <!-- DIUBAH: Menggunakan ->foto -->
                @if(!empty($data->foto))
                    <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto Donasi">
                @else
                    <img src="https://via.placeholder.com/450x300/f5f5f5/cccccc?text=Belum+Ada+Foto" alt="Donasi">
                @endif
            </div>

            <div class="content-section">
                <h3 class="section-title">Deskripsi Kegiatan</h3>
                <!-- DIUBAH: Menggunakan ->deskripsi -->
                <p class="section-text">{{ $data->deskripsi }}</p>
            </div>

            <div class="content-section">
                <h3 class="section-title">Alamat</h3>
                <!-- DIUBAH: Menggunakan ->alamat -->
                <p class="section-text">{{ $data->alamat }}</p>
            </div>

            <div class="footer-actions">
                <button class="btn-action">Daftar Donasi</button>
            </div>
        </div>
    </div>

</body>
</html>