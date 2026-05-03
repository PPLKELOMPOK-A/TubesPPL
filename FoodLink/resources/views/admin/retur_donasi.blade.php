<!-- resources/views/admin/retur_donasi.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Retur Donasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            min-height: 100vh;
            background: #e8d3a5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 10px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.25s ease;
        }

        .sidebar-menu a i { width: 20px; height: 20px; }

        .sidebar-menu a:hover { background: rgba(90, 62, 27, 0.1); }

        .sidebar-menu a.active {
            background: #5a3e1b;
            color: white;
        }

        .sidebar-footer { padding: 10px; }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            background: none;
            border: none;
            padding: 14px;
            cursor: pointer;
            font-size: 15px;
            border-radius: 12px;
        }

        .logout-btn:hover { background: rgba(90, 62, 27, 0.1); }

        /* CONTENT */
        .content {
            flex: 1;
            margin-left: 230px;
        }

        /* TOPBAR */
        .topbar {
            width: 100%;
            height: 65px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar h2 {
            margin: 0;
            font-size: 18px;
            color: #2C1A0E;
        }

        .topbar-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar-icons i {
            width: 22px;
            height: 22px;
            color: #555;
            cursor: pointer;
        }

        .profile-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            overflow: hidden;
            background: #ccc;
        }

        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* CONTENT BODY */
        .content-body {
            padding: 30px 40px;
        }

        /* FORM CARD */
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px 35px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .form-title {
            font-size: 15px;
            font-weight: bold;
            color: #5a3e1b;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        /* FORM GRID 2 KOLOM */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* FULL WIDTH */
        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 12px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 7px;
            border: 1px solid #ccc;
            font-size: 13px;
            font-family: Arial;
            background: #fafafa;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #5a3e1b;
            background: #fff;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        /* UPLOAD */
        .upload-box {
            width: 100%;
            height: 360px;
            border-radius: 10px;
            background: #f0ede8;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 6px;
            position: relative;
            border: 2px dashed #c9b99a;
            cursor: pointer;
        }

        .upload-box i {
            width: 50px;
            height: 50px;
            color: #a08060;
        }

        .upload-box img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            display: none;
        }

        /* BUTTON */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-cancel {
            padding: 10px 22px;
            background: white;
            color: #5a3e1b;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-cancel:hover { background: #f5f5f5; }

        .submit-btn {
            padding: 10px 28px;
            background: #5a3e1b;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .submit-btn:hover { background: #6b4a1b; }

        /* ALERT ERROR */
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 1rem;
        }

        /* TOAST */
        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-left: 5px solid #2d7a3a;
            border-radius: 8px;
            padding: 14px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            min-width: 280px;
            animation: slideIn 0.4s ease, fadeOut 0.5s ease 3.5s forwards;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            background: #d4edda;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-icon i { width: 18px; height: 18px; color: #2d7a3a; }

        .toast-body { display: flex; flex-direction: column; gap: 2px; }

        .toast-title { font-size: 14px; font-weight: bold; color: #1a1a1a; }

        .toast-msg { font-size: 13px; color: #555; }

        .toast-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            font-size: 18px;
            line-height: 1;
            padding: 0;
        }

        .toast-close:hover { color: #333; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(60px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to   { opacity: 0; pointer-events: none; }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}">
                <i data-lucide="home"></i> Beranda
            </a>
            <a href="{{ route('validasi.index') }}">
                <i data-lucide="file-check"></i> Validasi Donasi
            </a>
            <a href="{{ url('/admin/chat') }}">
                <i data-lucide="mail"></i> Chat
            </a>
            <a href="{{ route('admin.retur.index') }}" class="active">
                <i data-lucide="corner-down-left"></i> Retur Donasi
            </a>
            <a href="{{ route('penugasan.index') }}">
                <i data-lucide="users"></i> Penugasan Relawan
            </a>
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i data-lucide="log-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- TOPBAR -->
        <div class="topbar">
            <h2>Retur Donasi</h2>
            <div class="topbar-icons">
                <i data-lucide="bell"></i>
                <div class="profile-circle">
                    <img src="https://i.pravatar.cc/100" alt="profile">
                </div>
            </div>
        </div>

        <div class="content-body">

            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-card">
                <div class="form-title">Form Pengajuan Retur Donasi</div>

                <form action="{{ route('admin.retur.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-grid">

                        <div class="form-group">
                            <label>ID Donasi</label>
                            <input type="text" name="id_donasi"
                                   placeholder="Masukkan ID Donasi"
                                   value="{{ old('id_donasi') }}">
                        </div>

                        <div class="form-group">
                            <label>Nama Makanan</label>
                            <input type="text" name="nama_makanan"
                                   placeholder="Masukkan Nama Makanan"
                                   value="{{ old('nama_makanan') }}">
                        </div>

                        <div class="form-group">
                            <label>Jumlah yang Diretur</label>
                            <input type="number" name="jumlah"
                                   placeholder="Masukkan jumlah"
                                   value="{{ old('jumlah') }}">
                        </div>

                        <div class="form-group">
                            <label>Kategori Makanan</label>
                            <select name="kategori">
                                <option value="">Pilih kategori</option>
                                <option {{ old('kategori') == 'Makanan Berat' ? 'selected' : '' }}>Makanan Berat</option>
                                <option {{ old('kategori') == 'Makanan Ringan' ? 'selected' : '' }}>Makanan Ringan</option>
                                <option {{ old('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Alasan Retur</label>
                            <input type="text" name="alasan"
                                   placeholder="Contoh: Tidak Sesuai"
                                   value="{{ old('alasan') }}">
                        </div>

                        <div class="form-group">
                            <label>Tanggal Pengajuan</label>
                            <input type="date" name="tanggal_pengajuan"
                                   value="{{ old('tanggal_pengajuan') }}">
                        </div>

                        <div class="form-group full">
                            <label>Deskripsi Retur</label>
                            <textarea name="deskripsi"
                                      placeholder="Masukkan alasan dikembalikan">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="form-group full">
                            <label>Upload Bukti</label>
                            <input type="file" name="bukti" onchange="previewImage(event)">
                            <div class="upload-box">
                                <i data-lucide="upload-cloud" id="uploadIcon"></i>
                                <img id="preview">
                            </div>
                        </div>

                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('admin.retur.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="submit-btn">Retur</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</div>

{{-- TOAST NOTIFIKASI --}}
@if(session('success'))
<div class="toast" id="toast">
    <div class="toast-icon">
        <i data-lucide="check"></i>
    </div>
    <div class="toast-body">
        <div class="toast-title">Berhasil!</div>
        <div class="toast-msg">{{ session('success') }}</div>
    </div>
    <button class="toast-close" onclick="document.getElementById('toast').remove()">&#x2715;</button>
</div>
@endif

<script>
    lucide.createIcons();

    function previewImage(event) {
        const preview = document.getElementById('preview');
        const icon = document.getElementById('uploadIcon');
        if (event.target.files.length > 0) {
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
            icon.style.display = 'none';
        }
    }

    setTimeout(() => {
        const toast = document.getElementById('toast');
        if (toast) toast.remove();
    }, 4000);
</script>

</body>
</html>