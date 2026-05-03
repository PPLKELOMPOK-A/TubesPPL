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

        /* --- SIDEBAR --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 35px; font-weight: 700; font-size: 24px; color: #6B4F2A; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; gap: 15px; margin-bottom: 6px; border-radius: 10px; }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active i { color: #FFFFFF; }

        /* --- MAIN PANEL --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; border-bottom: 1px solid #f0f0f0; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; border: 2px solid #F8E7C1; margin-left: 10px; }

        .container { padding: 40px 80px; max-width: 900px; width: 100%; margin: 0 auto; }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #444; margin-bottom: 8px; }
        
        input[type="text"], input[type="date"], select, textarea {
            width: 100%; padding: 12px 15px; border: 1px solid #D0D0D0; border-radius: 8px; font-size: 14px; color: #555; outline: none; background-color: white;
        }

        /* PHOTO SECTION */
        .image-edit-section { display: flex; align-items: flex-start; gap: 20px; margin-top: 10px; }
        .image-box { width: 400px; height: 240px; border-radius: 10px; border: 1px solid #eee; overflow: hidden; background-color: #f5f5f5; }
        .image-box img { width: 100%; height: 100%; object-fit: cover; }
        .btn-edit-foto { background: white; border: 1px solid #D0D0D0; padding: 10px 18px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; }

        /* --- TANGGAL (FIX PASTI BISA) --- */
        .date-wrapper { position: relative; width: 100%; }
        .date-wrapper i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #888; pointer-events: none; }

        /* --- FOOTER BUTTONS --- */
        .footer-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 40px; padding-bottom: 50px; }
        .btn-base { padding: 12px 35px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; }
        .btn-kembali { background-color: white; color: #444; border: 1px solid #D0D0D0; }
        .btn-kembali:hover { background-color: #f5f5f5; }
        .btn-simpan { background-color: #6B4F2A; color: white; border: none; }
        .btn-simpan:hover { background-color: #563e21; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-check-to-slot"></i> Validasi Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-comments"></i> Chat</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-users-gear"></i> Penugasan Relawan</a>
        </div>
        <div class="logout-section" style="padding: 0 15px; margin-top: auto;">
             <button class="logout-btn" style="border:none; background:none; color:red; cursor:pointer;"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
        </div>
    </div>

    <div class="main-panel">
        <div class="top-bar">
            <i class="fa-regular fa-bell"></i>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6B4F2A&color=fff" class="user-avatar">
        </div>

        <div class="container">
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" value="Hari Anak Nasional - Panti Bunda Kasih">
                </div>

                <div class="form-group">
                    <label>Kategori Penerima</label>
                    <select>
                        <option selected>Organisasi (Yayasan)</option>
                        <option>Kegiatan Keagamaan</option>
                        <option>Individu/Umum</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <div class="date-wrapper">
                        <input type="text" 
                               id="date-input" 
                               value="Kamis, 13 Mei 2026" 
                               onfocus="(this.type='date')" 
                               onblur="formatDateBack(this)" 
                               placeholder="Pilih Tanggal">
                        <i class="fa-regular fa-calendar-days"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="image-edit-section">
                        <div class="image-box">
                            <img id="img-preview" src="https://via.placeholder.com/400x240/f5f5f5/cccccc?text=Foto+Donasi" alt="Preview">
                        </div>
                        <input type="file" id="file-upload" hidden accept="image/*">
                        <button type="button" class="btn-edit-foto" onclick="document.getElementById('file-upload').click()">
                            <i class="fa-regular fa-pen-to-square"></i> Edit Foto
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Kegiatan</label>
                    <textarea rows="4">Tersedia 20 paket nasi kotak ayam bakar sisa acara syukuran siang ini. Kondisi masih sangat baik, bersih, dan higienis. Lengkap dengan sayur urap dan sambal. Mohon segera diambil untuk dibagikan ke anak-anak panti sebelum jam 8 malam agar kualitas rasa tetap terjaga</textarea>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" value="Jl. Bougenville Timur No. 22">
                </div>

                <div class="footer-actions">
                    <a href="{{ route('admin.donasi.detail') }}" class="btn-base btn-kembali">Kembali</a>
                    <button type="submit" class="btn-base btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk mengembalikan format teks saat kalender ditutup
        function formatDateBack(input) {
            if (!input.value) {
                input.type = 'text';
                input.value = 'Kamis, 13 Mei 2026'; // Fallback
                return;
            }
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateObj = new Date(input.value);
            const formatted = dateObj.toLocaleDateString('id-ID', options);
            input.type = 'text';
            input.value = formatted;
        }

        // Preview Foto
        const fileUpload = document.getElementById('file-upload');
        fileUpload.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { document.getElementById('img-preview').src = e.target.result; }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>