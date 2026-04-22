<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bukti Penyelesaian Donasi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Beranda</h3>
        <ul>
            <li>Riwayat Donasi</li>
            <li class="active">Bukti Donasi</li>
        </ul>
        <div class="logout">Logout</div>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Bukti Penyelesaian Donasi</h2>
        <p class="sub">Lihat dan verifikasi distribusi donasi makanan</p>

        <!-- Search -->
        <input type="text" class="search" placeholder="Search">

        <div id="listView">

        <!-- List -->
        @foreach($data as $item)
        <div class="card">
            <img src="{{ asset('images/'.$item['foto']) }}" class="thumb">

            <div class="info">
                <h4>{{ $item['judul'] }}</h4>
                <p>{{ $item['kategori'] }}</p>
                <small>{{ $item['tanggal'] }}</small>
            </div>

            <div class="action">
    <button class="btn-primary">Lihat Bukti</button>

    <a href="{{ route('bukti.donasi.detail', $loop->index) }}">
        <button class="btn-secondary">Detail</button>
    </a>
</div>
        @endforeach

        <!-- Pagination -->
        <div class="pagination">
            <span>1-5 dari 200</span>
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