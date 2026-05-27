<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodlink Admin - Tambah Donasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; }
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
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 20px; border-bottom: 1px solid #f0f0f0; }
        .top-bar i { color: #ccc; font-size: 20px; }
        .admin-profile { display: flex; align-items: center; gap: 10px; }
        .admin-profile span { color: #6B4F2A; font-weight: 600; font-size: 14px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }

        /* --- CONTENT & FORM --- */
        .container { padding: 40px 60px; max-width: 800px; width: 100%; margin-left: 0; }
        .container h2 { font-size: 24px; color: #111; margin-bottom: 30px; }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 10px; }
        
        .form-group input[type="text"], 
        .form-group select, 
        .form-group textarea {
            width: 100%; padding: 14px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; color: #444; background: #fff; outline: none; transition: 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #6B4F2A; }

        /* Custom Date Container */
        .date-container { position: relative; display: flex; align-items: center; }
        .date-container input[type="date"] {
            position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;
        }
        .date-container input[type="text"] {
            width: 100%; padding: 14px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; color: #444; background: #fff; cursor: pointer;
        }
        .date-container .calendar-icon { position: absolute; right: 15px; color: #888; z-index: 1; }

        /* Image Upload Box */
        .image-container { display: flex; flex-direction: column; gap: 15px; align-items: flex-start; }
        .preview-box { width: 400px; height: 250px; border-radius: 12px; overflow: hidden; border: 1px solid #ddd; background: #f9f9f9; display: flex; justify-content: center; align-items: center; }
        .preview-box img { width: 100%; height: 100%; object-fit: cover; }
        #file-input { display: none; }
        
        /* Buttons */
        .btn-edit-foto { background: white; border: 1px solid #ddd; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; color: #444; cursor: pointer; display: flex; gap: 8px; align-items: center; }
        .btn-edit-foto:hover { background: #f5f5f5; }

        .footer-actions { display: flex; gap: 15px; margin-top: 40px; padding-bottom: 60px; }
        .btn-base { padding: 14px 30px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: 0.2s; }
        .btn-kembali { background: white; border: 1px solid #ddd; color: #444; }
        .btn-kembali:hover { background: #f9f9f9; }
        .btn-simpan { background: #6B4F2A; border: none; color: white; }
        .btn-simpan:hover { background: #563e21; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="{{ route('admin.validasi.index') }}" class="nav-item"><i class="fa-solid fa-check-to-slot"></i> Validasi Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Chat</a>
            <a href="{{ route('admin.retur.index') }}" class="nav-item"><i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi</a>
            <a href="{{ route('admin.penugasan.index') }}" class="nav-item"><i class="fa-solid fa-users-gear"></i> Penugasan Relawan</a>
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
            <h2>Buat Posting Donasi</h2>
            
            <form action="{{ route('admin.donasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" required placeholder="Masukkan judul donasi">
                </div>

                <div class="form-group">
                    <label>Kategori Penerima</label>
                    <select name="kategori" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        <option value="Organisasi (Yayasan)">Organisasi (Yayasan)</option>
                        <option value="Kegiatan Keagamaan">Kegiatan Keagamaan</option>
                        <option value="Individu/Umum">Individu/Umum</option>
                        <option value="Individu">Individu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="date-container">
                        <input type="date" name="tanggal" id="real-date" onchange="updateDateDisplay(this.value)" required>
                        <input type="text" id="date-display" placeholder="Pilih Tanggal" readonly>
                        <i class="fa-regular fa-calendar-days calendar-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Unggah Foto</label>
                    <div class="image-container">
                        <div class="preview-box">
                            <img id="img-preview" src="https://via.placeholder.com/400x250/f5f5f5/cccccc?text=Pratinjau+Foto" alt="Preview">
                        </div>
                        <input type="file" id="file-input" name="foto" accept="image/*">
                        <button type="button" class="btn-edit-foto" onclick="document.getElementById('file-input').click();">
                            <i class="fa-regular fa-image"></i> Pilih Foto
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Kegiatan</label>
                    <textarea name="deskripsi" rows="5" required placeholder="Jelaskan detail makanan yang didonasikan..."></textarea>
                </div>

                <div class="form-group">
                    <label>Alamat Pengambilan</label>
                    <input type="text" name="alamat" required placeholder="Masukkan alamat lengkap">
                </div>

                <div class="footer-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn-base btn-kembali">Batal</a>
                    <button type="submit" class="btn-simpan btn-base">Simpan Postingan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logika untuk pratinjau gambar saat diunggah
        const fileInput = document.getElementById('file-input');
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { document.getElementById('img-preview').src = e.target.result; }
                reader.readAsDataURL(file);
            }
        });

        // Logika untuk mengubah format tampilan tanggal di input form
        function updateDateDisplay(val) {
            if(!val) return;
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const d = new Date(val);
            document.getElementById('date-display').value = d.toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>