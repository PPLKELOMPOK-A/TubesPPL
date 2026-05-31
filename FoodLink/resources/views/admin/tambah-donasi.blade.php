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
            <h2>Buat Posting Donasi</h2>
            <br>
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
                        <option value="Individu">Individu</option>
                    </select>
                </div>
                <button class="btn-filter">Filter <i class="fa-solid fa-chevron-down"></i></button>
            </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="date-container">
                        <input type="date" name="tanggal" id="real-date" onchange="updateDateDisplay(this.value)" required>
                        <input type="text" id="date-display" placeholder="Pilih Tanggal" readonly>
                        <i class="fa-regular fa-calendar-days calendar-icon"></i>
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
                    <textarea name="deskripsi" rows="4" required placeholder="Jelaskan detail makanan yang didonasikan..."></textarea>
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