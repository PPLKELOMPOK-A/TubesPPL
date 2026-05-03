<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink Admin - Edit Donasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR (SESUAI REVISI) --- */
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

        /* --- MAIN CONTENT --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; border-bottom: 1px solid #f0f0f0; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; border: 2px solid #F8E7C1; margin-left: 15px; }

        /* --- CONTAINER --- */
        .container { 
            padding: 40px 60px; 
            max-width: 1000px; 
            width: 100%; 
            margin-left: 0; 
            margin-right: auto; 
        }

        .form-group { margin-bottom: 25px; max-width: 700px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #444; margin-bottom: 8px; }
        
        input[type="text"], input[type="date"], select, textarea {
            width: 100%; padding: 12px 15px; border: 1px solid #D0D0D0; border-radius: 8px; font-size: 14px; color: #555; outline: none; background: white;
        }

        /* TANGGAL CUSTOM */
        .date-container { position: relative; }
        #date-display { cursor: pointer; background: white; }
        #real-date { position: absolute; opacity: 0; width: 100%; height: 100%; left: 0; top: 0; cursor: pointer; z-index: 2; }
        .calendar-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #888; pointer-events: none; z-index: 1; }

        /* Image Edit Section */
        .image-container { display: flex; align-items: flex-start; gap: 20px; margin-top: 10px; max-width: 700px; }
        .preview-box { width: 400px; height: 250px; border-radius: 12px; border: 1px solid #eee; overflow: hidden; background: #f5f5f5; }
        .preview-box img { width: 100%; height: 100%; object-fit: cover; }
        .btn-edit-foto { background: white; border: 1px solid #D0D0D0; padding: 10px 15px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; }

        /* Actions */
        .footer-actions { 
            display: flex; 
            justify-content: flex-start; 
            gap: 15px; 
            margin-top: 40px; 
            padding-bottom: 50px;
            max-width: 700px;
        }
        .btn-base { padding: 12px 40px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; transition: 0.3s; min-width: 130px; display: inline-flex; align-items: center; justify-content: center; }
        .btn-kembali { background: white; color: #444; border: 1px solid #D0D0D0; }
        .btn-simpan { background-color: #6B4F2A; color: white; border: none; }
        .btn-simpan:hover { background-color: #563e21; }

        #file-input { display: none; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="{{ route('validasi.index') }}" class="nav-item active"><i class="fa-solid fa-check-to-slot"></i> Validasi Donasi</a>
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
            <img src="https://ui-avatars.com/api/?name=Admin&background=6B4F2A&color=fff" class="user-avatar">
        </div>

        <div class="container">
            <form action="{{ route('admin.donasi.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" value="{{ $data['judul'] }}">
                </div>

                <div class="form-group">
                    <label>Kategori Penerima</label>
                    <select name="kategori">
                        <option value="Organisasi (Yayasan)" {{ $data['kategori'] == 'Organisasi (Yayasan)' ? 'selected' : '' }}>Organisasi (Yayasan)</option>
                        <option value="Kegiatan Keagamaan" {{ $data['kategori'] == 'Kegiatan Keagamaan' ? 'selected' : '' }}>Kegiatan Keagamaan</option>
                        <option value="Individu" {{ $data['kategori'] == 'Individu' ? 'selected' : '' }}>Individu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="date-container">
                        <input type="date" name="tanggal" id="real-date" onchange="updateDateDisplay(this.value)" value="{{ $data['tanggal'] }}">
                        <input type="text" id="date-display" value="{{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('l, d F Y') }}" readonly>
                        <i class="fa-regular fa-calendar-days calendar-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="image-container">
                        <div class="preview-box">
                            <img id="img-preview" src="{{ $data['foto'] ? asset('storage/' . $data['foto']) : 'https://via.placeholder.com/400x250/f5f5f5/cccccc?text=Foto+Donasi' }}" alt="Preview">
                        </div>
                        <input type="file" id="file-input" name="foto" accept="image/*">
                        <button type="button" class="btn-edit-foto" onclick="document.getElementById('file-input').click();">
                            <i class="fa-regular fa-pen-to-square"></i> Edit Foto
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Kegiatan</label>
                    <textarea name="deskripsi" rows="4">{{ $data['deskripsi'] }}</textarea>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" value="{{ $data['alamat'] }}">
                </div>

                <div class="footer-actions">
                    <a href="javascript:void(0)" onclick="history.back();" class="btn-base btn-kembali">Kembali</a>
                    <button type="submit" class="btn-simpan btn-base">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('file-input');
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { document.getElementById('img-preview').src = e.target.result; }
                reader.readAsDataURL(file);
            }
        });

        function updateDateDisplay(val) {
            if(!val) return;
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const d = new Date(val);
            document.getElementById('date-display').value = d.toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>