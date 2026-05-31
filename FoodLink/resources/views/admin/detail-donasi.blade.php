<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink Admin - Detail Donasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR (DISESUAIKAN) --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 35px; font-weight: 700; font-size: 24px; color: #6B4F2A; }
        
        .nav-group { flex-grow: 1; padding: 0 15px; /* Memberi jarak agar tidak penuh ke pinggir */ }
        
        .nav-item { 
            display: flex; 
            align-items: center; 
            padding: 12px 20px; 
            text-decoration: none; 
            color: #4A4A4A; 
            font-size: 14px; 
            font-weight: 500; 
            gap: 15px; 
            margin-bottom: 6px; 
            border-radius: 10px; /* Sudut kotak sewajarnya */
            transition: 0.2s;
        }
        
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; font-weight: 600; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active i { color: #FFFFFF; }
        
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }

        .logout-section { padding: 25px 30px; margin-top: auto; }
        .logout-btn { border: none; background: none; cursor: pointer; color: #4A4A4A; display: flex; align-items: center; gap: 15px; font-size: 14px; font-weight: 500; }

        /* --- MAIN PANEL --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; border-bottom: 1px solid #f0f0f0; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; border: 2px solid #F8E7C1; margin-left: 10px; }

        /* --- CONTAINER --- */
        .container { padding: 40px 60px; max-width: 1000px; width: 100%; margin-left: 0; margin-right: auto; }

        /* Tombol Kembali UX Standar (Atas) */
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
        
        .btn { padding: 12px 35px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; justify-content: center; min-width: 120px; }
        
        .btn-kembali { background: white; border: 1px solid #D0D0D0; color: #444; }
        .btn-hapus { background: #fdfdfd; border: 1px solid #d9534f; color: #d9534f; }
        .btn-hapus { background: white; border: 1px solid #D0D0D0; color: #444; }
        .btn-edit { background: #6B4F2A; color: white; border: none; }
        .btn-edit:hover { background-color: #563e21; }

        /* Tambahan style untuk alert sukses */
        .alert-success { background-color: #E6F4EA; border: 1px solid #1E8E3E; color: #1E8E3E; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; display: flex; align-items: center; gap: 10px; max-width: 600px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item active"><i class="fa-solid fa-house"></i> Beranda Admin</a>
            <a href="{{ route('admin.validasi.index') }}" class="nav-item"><i class="fa-solid fa-check-to-slot"></i> Validasi Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-users-gear"></i> Penugasan Relawan</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-chart-line"></i> Dashboard Laporan</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-handshake"></i> Kerjasama Mitra</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Koordinasi</a>
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
            <i class="fa-regular fa-bell"></i>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6B4F2A&color=fff" class="user-avatar">
        </div>

        <div class="container">
            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('admin.dashboard') }}" class="back-nav" title="Kembali ke Beranda">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div class="header-info">
                <h1>{{ $data->judul }}</h1>
                <span class="category">{{ $data->kategori }}</span>
                <p class="date">{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}</p>
            </div>

            <div class="image-container">
                @if(!empty($data->foto))
                    <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto Donasi">
                @else
                    <img src="https://via.placeholder.com/450x300/f5f5f5/cccccc?text=Belum+Ada+Foto" alt="Donasi">
                @endif
            </div>

            <div class="content-section">
                <h3 class="section-title">Deskripsi Kegiatan</h3>
                <p class="section-text">{{ $data->deskripsi }}</p>
            </div>

            <div class="content-section">
                <h3 class="section-title">Alamat</h3>
                <p class="section-text">{{ $data->alamat }}</p>
            </div>

            <div class="footer-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-kembali">Kembali</a>
                
                <form action="{{ route('admin.donasi.delete', ['id' => $data->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data donasi ini?');" style="margin: 0; padding: 0;">
                    @csrf
                    <button type="submit" class="btn btn-hapus">Hapus</button>
                </form>

                <a href="{{ route('admin.donasi.edit', ['id' => $data->id]) }}" class="btn btn-edit">Edit</a>
            </div>
        </div>
    </div>

</body>
</html>