<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink - Edit Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR & TOPBAR TETAP --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; transition: 0.2s; gap: 15px; margin-bottom: 6px; border-radius: 10px; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }

        .logout-section { padding: 0 15px; margin-top: auto; }
        .logout-btn { border: none; background: none; width: 100%; text-align: left; cursor: pointer; color: #d9534f; display: flex; align-items: center; gap: 15px; padding: 12px 20px; font-size: 14px; font-weight: 500; border-radius: 10px; transition: 0.2s; }
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }

        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; background-color: #FFFFFF; }
        .top-bar { height: 70px; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #eee; }
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        .profile-section { display: flex; align-items: center; gap: 12px; text-decoration: none; cursor: pointer; }
        .user-avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }

        /* --- CONTAINER EDIT PROFIL --- */
        .container { padding: 50px 60px; max-width: 900px; width: 100%; margin-left: 0; }

        .profile-header-title { display: flex; align-items: center; gap: 20px; margin-bottom: 45px; }
        .back-nav { color: #111; text-decoration: none; font-size: 20px; transition: 0.2s; }
        .back-nav:hover { color: #6B4F2A; transform: translateX(-3px); }
        .page-title { font-size: 24px; font-weight: 700; color: #111; }

        /* Hero Edit Foto */
        .profile-hero { display: flex; align-items: center; gap: 35px; margin-bottom: 50px; }
        .user-avatar-large { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        .btn-upload-foto { display: inline-flex; align-items: center; gap: 10px; background: #ffffff; border: 1px solid #dcdcdc; color: #111; font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.2s; }
        .btn-upload-foto i { font-size: 14px; }
        .btn-upload-foto:hover { background: #fafafa; border-color: #ccc; }

        /* Input Form Grid */
        .profile-details-list { display: flex; flex-direction: column; gap: 20px; max-width: 600px; margin-bottom: 50px; }
        .detail-row { display: grid; grid-template-columns: 150px 1fr; align-items: center; }
        .detail-label { font-size: 14px; font-weight: 500; color: #444; }
        
        .form-input { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; color: #111; font-weight: 600; outline: none; transition: 0.2s; background: #fafafa; }
        .form-input:focus { border-color: #6B4F2A; background: #ffffff; }

        /* Tombol Aksi Bawah */
        .action-buttons { display: flex; gap: 15px; }
        .btn-batal { background-color: #ffffff; color: #444; border: 1px solid #ddd; padding: 12px 30px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-batal:hover { background-color: #f5f5f5; }
        .btn-simpan { background-color: #5C4322; color: #ffffff; border: none; padding: 12px 45px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-simpan:hover { background-color: #4a351a; }
    </style>
</head>
<body>

    <div class="sidebar">
        <!-- Sidebar TANPA PROFIL SAYA -->
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('dashboard') }}" class="nav-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-hand-holding-heart"></i> Riwayat Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Riwayat Koordinasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi</a>
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
            <a href="{{ route('profil') }}" class="profile-section">
                <span style="font-size: 13px; font-weight: 600; color: #444;">{{ Auth::user()->name ?? 'Sumanto' }}</span>
                @if(!empty(Auth::user()->foto_profil))
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="user-avatar-small" alt="Foto">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Sumanto') }}&background=6B4F2A&color=fff" class="user-avatar-small" alt="Foto">
                @endif
            </a>
        </div>

        <div class="container">
            <div class="profile-header-title">
                <a href="{{ route('profil') }}" class="back-nav"><i class="fa-solid fa-arrow-left"></i></a>
                <h1 class="page-title">Edit Profil</h1>
            </div>

            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Hero Edit Foto -->
                <div class="profile-hero">
                    @if(!empty(Auth::user()->foto_profil))
                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" id="avatarPreview" class="user-avatar-large" alt="Foto">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Sumanto') }}&background=2C3E50&color=fff&size=200" id="avatarPreview" class="user-avatar-large" alt="Foto">
                    @endif
                    
                    <div class="hero-text">
                        <!-- Tombol Pilih Foto -->
                        <button type="button" class="btn-upload-foto" onclick="document.getElementById('foto_profil').click();">
                            <i class="fa-solid fa-camera"></i> Ubah Foto
                        </button>
                        <input type="file" name="foto_profil" id="foto_profil" accept="image/*" style="display:none;">
                    </div>
                </div>

                <!-- Input Data Profil -->
                <div class="profile-details-list">
                    <div class="detail-row">
                        <div class="detail-label">Nama</div>
                        <input type="text" name="name" class="form-input" value="{{ Auth::user()->name ?? 'Sumanto' }}" required>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">NIK</div>
                        <input type="text" name="nik" class="form-input" value="{{ Auth::user()->nik ?? '102042300018' }}">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <input type="email" name="email" class="form-input" value="{{ Auth::user()->email ?? 'Sumanto@gmail.com' }}" required>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Telepon</div>
                        <input type="text" name="telepon" class="form-input" value="{{ Auth::user()->telepon ?? '08999994432' }}">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Lokasi</div>
                        <input type="text" name="lokasi" class="form-input" value="{{ Auth::user()->lokasi ?? 'Kec. Gambir - Jakarta Pusat' }}">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Alamat</div>
                        <input type="text" name="alamat" class="form-input" value="{{ Auth::user()->alamat ?? 'Jl. Bougenville Timur No. 22' }}">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                    <a href="{{ route('profil') }}" class="btn-batal">Batal</a>
                </div>
            </form>

        </div>
    </div>

    <!-- Script agar foto langsung berubah (preview) saat file dipilih -->
    <script>
        const fotoInput = document.getElementById('foto_profil');
        const avatarPreview = document.getElementById('avatarPreview');

        fotoInput.onchange = evt => {
            const [file] = fotoInput.files;
            if (file) {
                avatarPreview.src = URL.createObjectURL(file);
            }
        }
    </script>
</body>
</html>