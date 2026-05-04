<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Bukti - {{ $donasi->judul }}</title>
    <!-- Ikon untuk Sidebar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            margin: 0;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR CUSTOM (Sesuai Gambar) --- */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #e6d1a3; /* Warna krem sesuai gambar */
            padding: 40px 15px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            box-sizing: border-box;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .sidebar-menu li {
            margin-bottom: 15px;
            width: 100%;
        }

        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #5b3a1e; /* Warna cokelat teks menu biasa */
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 12px;
            box-sizing: border-box;
            width: 100%; /* Menambah lebar tombol ke kanan */
            line-height: 1.2; /* Menjaga jarak antar baris teks jika tetap terpaksa turun */
        }

        /* Menu Aktif (Sesuai Gambar) */
        .sidebar-menu li.active a {
            background: #5b3a1e; /* Cokelat gelap sesuai gambar */
            color: #ffffff !important; /* Teks putih bersih */
            font-weight: bold;
            /* Tambahkan box-shadow halus agar terlihat lebih 'on' */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .sidebar-menu li a:hover {
            background: rgba(91, 58, 30, 0.01);
        }

        .logout {
            padding: 20px;
            color: #5b3a1e;
            font-weight: bold;
            text-decoration: none;
            margin-top: auto;
        }

        /* --- CONTENT AREA (Kode Asli) --- */
        .content {
            flex: 1;
            padding: 40px;
            max-width: 900px;
        }

        .title { font-size: 28px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .subtitle { color: #7f8c8d; margin-bottom: 30px; font-size: 15px; }
        .gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .gallery img { width: 100%; height: 250px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .info-box { background: white; padding: 25px; border-radius: 12px; border-left: 6px solid #5b3a1e; box-shadow: 0 2px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .info-box h3 { margin-top: 0; color: #5b3a1e; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .info-item { margin: 12px 0; font-size: 15px; display: flex; align-items: center; }
        .status-box { display: flex; align-items: center; gap: 8px; margin-bottom: 15px; color: #28a745; font-weight: bold; }
        .status-final { margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ddd; font-weight: bold; }
        .badge { background: #28a745; color: white; padding: 5px 15px; border-radius: 50px; font-size: 13px; margin-left: 10px; }
        .btn-kembali { display: inline-flex; align-items: center; background: #5b3a1e; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; transition: 0.3s; }
    </style>
</head>
<body>

<div class="container">

    <!-- Struktur HTML Sidebar -->
<div class="sidebar">
    <ul class="sidebar-menu">
        <!-- Hapus tag <i> (ikon) agar bersih sesuai gambar 1 -->
        <li><a href="#">Beranda</a></li>
        <li class="active"><a href="{{ route('bukti-donasi.index') }}">Bukti Penyelesaian Donasi</a></li>
        <li><a href="#">Riwayat Koordinasi</a></li>
        <li><a href="#">Bukti Donasi</a></li>
    </ul>
    <a href="#" class="logout">Logout</a>
</div>

    <!-- Content -->
    <div class="content">
        <div class="title">{{ $donasi->judul }}</div>
        <div class="subtitle">Lihat dan verifikasi hasil distribusi donasi makanan Anda</div>

        <div class="gallery">
            @if($donasi->galeri && count($donasi->galeri) > 0)
                @foreach($donasi->galeri as $foto)
                    <img src="{{ asset('images/'.$foto) }}" alt="Bukti Donasi">
                @endforeach
            @else
                <p style="color: gray; font-style: italic;">Tidak ada foto bukti tersedia.</p>
            @endif
        </div>

        <div class="info-box">
            <h3>Informasi Penyelesaian Donasi</h3>
            <div class="status-box">
                <span>✔ Status: <b>Selesai</b></span>
            </div>

            <div class="info-item">📅 &nbsp; <b>Tanggal Donasi:</b> &nbsp; 19 April, 2024</div>
            <div class="info-item">🎯 &nbsp; <b>Tujuan Donasi:</b> &nbsp; Gerakan Peduli Anak</div>
            <div class="info-item">🍚 &nbsp; <b>Jenis Makanan:</b> &nbsp; Beras, Minyak, Telur, dll</div>
            <div class="info-item">📝 &nbsp; <b>Catatan:</b> &nbsp; Donasi untuk anak yatim</div>

            <div class="status-final">
                ✔ Status Penyelesaian:
                <span class="badge">Selesai Dikirim</span>
            </div>
        </div>

        <a href="{{ route('bukti-donasi.index') }}" class="btn-kembali">
            <span>←</span> Kembali 
        </a>
    </div>
</div>

</body>
</html>