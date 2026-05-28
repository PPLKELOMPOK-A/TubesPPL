<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Bukti Donasi</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .desc {
            color: #555;
            margin-bottom: 20px;
        }

        .main-img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .gallery img {
            width: 100%;
            border-radius: 8px;
        }

        .btn-back {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background: #6a4c1f;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="title">{{ $data['judul'] }}</div>

    <div class="desc">{{ $data['deskripsi'] }}</div>

    <!-- FOTO UTAMA -->
    <img src="{{ $data['foto_utama'] }}" class="main-img">

    <!-- GALERI -->
    <div class="gallery">
        @foreach($data['galeri'] as $foto)
            <img src="{{ $foto }}">
        @endforeach
    </div>

    <!-- KEMBALI -->
    <a href="{{ route('bukti.donasi') }}" class="btn-back">
        ← Kembali
    </a>

</div>

</body>
</html>