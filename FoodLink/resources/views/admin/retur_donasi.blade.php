<!DOCTYPE html>
<html>
<head>
    <title>Retur Donasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f5f5;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: #e6cfa3;
            height: 100vh;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            color: black;
        }

        .sidebar a.active {
            background: #5a3e1b;
            color: white;
            border-radius: 5px;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input, select, textarea {
            width: 300px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        textarea {
            width: 500px;
        }

        .upload-box {
            width: 500px;
            height: 150px;
            border-radius: 10px;
            background: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        button {
            margin-top: 20px;
            padding: 10px 30px;
            background: #5a3e1b;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <a class="active">Beranda</a>
        <a>Validasi Donasi</a>
        <a>Chat</a>
        <a>Retur Donasi</a>
        <a>Penugasan Relawan</a>
        <a>Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Retur Donasi</h2>

        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif
        
<form action="{{ route('admin.retur.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>ID Donasi</label><br>
                <input type="text" name="id_donasi" placeholder="Masukkan ID Donasi">
            </div>

            <div class="form-group">
                <label>Nama Makanan</label><br>
                <input type="text" name="nama_makanan" placeholder="Masukkan Nama Makanan">
            </div>

            <div class="form-group">
                <label>Jumlah yang diretur</label><br>
                <input type="number" name="jumlah" placeholder="Masukkan jumlah">
            </div>

            <div class="form-group">
                <label>Kategori Makanan</label><br>
                <select name="kategori">
                    <option value="">Pilih kategori</option>
                    <option>Makanan Berat</option>
                    <option>Makanan Ringan</option>
                    <option>Minuman</option>
                </select>
            </div>

            <div class="form-group">
                <label>Alasan retur</label><br>
                <input type="text" name="alasan" placeholder="Contoh: Tidak sesuai">
            </div>

            <div class="form-group">
                <label>Tanggal Pengajuan</label><br>
                <input type="date" name="tanggal_pengajuan">
            </div>

            <div class="form-group">
                <label>Deskripsi Retur</label><br>
                <textarea name="deskripsi" placeholder="Masukkan alasan detail"></textarea>
            </div>

            <div class="form-group">
                <label>Upload Bukti</label><br>
                <input type="file" name="bukti">
                <div class="upload-box">
                    ⬆️ Upload Bukti
                </div>
            </div>

            <button type="submit">Retur</button>

        </form>
    </div>

</div>

</body>
</html>