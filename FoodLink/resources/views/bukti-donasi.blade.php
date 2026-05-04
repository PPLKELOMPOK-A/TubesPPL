<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Penyelesaian Donasi</title>

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background: #f5f5f5;
        }

        .container {
            display: flex;
        }

        /* Sidebar */
        .sidebar { width: 240px; background: #ffeecd; padding: 20px; border-right: 1px solid #e0e0e0; position: fixed; height: 100%; }
        .sidebar-menu { list-style: none; padding: 0; margin-top: 30px; }
        .sidebar-menu li { padding: 12px 15px; margin-bottom: 5px; border-radius: 8px; cursor: pointer; color: #5b3a1e; display: flex; align-items: center; gap: 10px; }
        .sidebar-menu li.active { background: #5b3a1e; color: white; }
        .logout { position: absolute; bottom: 30px; left: 20px; color: #5b3a1e; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 10px; }

        /* Sidebar */
        .sidebar {
            width: 260px; /* Sedikit lebih lebar agar lega */
            height: 100vh;
            background: #e6d1a3;
            padding: 30px 20px;
            display: flex;
            flex-direction: column; /* Mengatur susunan vertikal */
            position: sticky;
            top: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .sidebar h3 {
            background: #5b3a1e;
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            margin: 0 0 20px 0;
            font-size: 18px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1; /* Membuat daftar menu mengambil ruang yang tersedia */
        }

        .sidebar li {
            margin-bottom: 8px;
        }

        .sidebar li a, .sidebar li b {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: #5b3a1e;
            border-radius: 8px;
            transition: 0.3s;
            font-size: 14px;
            cursor: pointer;
        }

        /* Styling untuk menu yang sedang aktif */
        .sidebar li b {
            background: rgba(91, 58, 30, 0.1); /* Warna highlight halus */
            border-left: 4px solid #5b3a1e;
            padding-left: 11px; /* Kompensasi border */
        }

        .sidebar li a:hover {
            background: rgba(91, 58, 30, 0.05);
        }

        /* Logout diletakkan di paling bawah dengan rapi */
        .logout {
            padding: 15px;
            border-top: 1px solid rgba(0,0,0,0.1);
            color: #5b3a1e;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logout:hover {
            color: #d63031; /* Berubah merah saat hover */
        }

        /* Content */
        .content {
            flex: 1;
            padding: 30px;
        }

        h2 {
            margin-bottom: 5px;
        }

        .sub {
            color: gray;
            margin-bottom: 20px;
        }

        .search {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        /* Card List */
        .card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
        }

        .left {
            display: flex;
            align-items: center;
        }

        .thumb {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }

        .info h4 {
            margin: 0;
            font-size: 16px;
        }

        .info p {
            margin: 2px 0;
            color: gray;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-primary {
            background: #5b3a1e;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #eee;
            color: green;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 13px;
        }

        .pages button {
            margin: 0 3px;
        }

    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><i class="fas fa-home"></i> Beranda</li>
            <li class="active"><i class="fas fa-history"></i> Bukti Penyelesaian Donasi</li>
            <li><i class="fas fa-handshake"></i> Riwayat Koordinasi</li>
            <li><i class="fas fa-file-alt"></i> Bukti Donasi</li>
        </ul>
        <div class="logout"><i class="fas fa-sign-out-alt"></i> Logout</div>
    </div>

    <!-- Content -->
    <div class="content">

        <h2>Bukti Penyelesaian Donasi</h2>
        <p class="sub">Lihat dan Verifikasi hasil distribusi donasi makanan</p>

        <input type="text" class="search" placeholder="Search">

        <!-- LIST -->
        @foreach($donasi as $item)
        <div class="card">

            <div class="left">
                <!-- Gunakan helper asset() untuk mengarah ke folder public/storage -->
                <img src="{{ asset('storage/' . $item->foto) }}" 
                alt="Foto Donasi" 
                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                onerror="this.src='https://via.placeholder.com/80'">

                <div class="info">
                    <h4>{{ $item->judul }}</h4>
                    <p>{{ $item->kategori }}</p>
                    <p>{{ $item->tanggal }}</p>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('bukti-donasi.bukti', $item->id) }}" class="btn-primary">
                    Lihat Bukti
                </a>

                
                <a href="{{ route('bukti-donasi.show',$item->id) }}" class="btn btn-secondary">
                    Detail
                </a>

                
            </div>

        </div>
        @endforeach

        <!-- Pagination -->
        <div class="pagination">
            <span>1-5 dari 10</span>

            <div class="pages">
                <button>1</button>
                <button>2</button>
                <button>></button>
            </div>
        </div>

    </div>
</div>

</body>
</html>