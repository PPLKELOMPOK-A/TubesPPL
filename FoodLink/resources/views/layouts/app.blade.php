<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodlink')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #fdfdfd; }
        .sidebar { width: 260px; background-color: #FBEBCE; padding: 20px; border-right: 1px solid #e9e2d4; }
        .brand { font-weight: 700; font-size: 22px; color: #6B4F2A; margin-bottom: 18px; }
        .nav { display: flex; flex-direction: column; gap: 8px; }
        .nav a { text-decoration: none; color: #4A4A4A; padding: 10px 14px; border-radius: 8px; display: flex; align-items: center; gap: 10px; }
        .nav a:hover { background: #f6ead3; color: #3b2f2f; }
        .main { display: flex; min-height: 100vh; }
        .content-wrap { flex: 1; padding: 28px 40px; max-width: 1100px; margin: 0 auto; }
        .profile-small { margin-left: auto; }
    </style>
    @yield('styles')
</head>
<body>
    <div class="main">
        <aside class="sidebar">
            <div class="brand">Foodlink</div>
            <nav class="nav">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <a href="{{ route('riwayat-donasi.index') }}">Riwayat Donasi</a>
                <a href="{{ route('bukti.donasi') }}">Bukti Donasi</a>
                <a href="#">Riwayat Koordinasi</a>
                <a href="#">Retur Donasi</a>
                
                <a href="{{ route('dropbox.index') }}">Drop Box</a>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin-top: 12px;">
                    @csrf
                    <button type="submit" style="background:none;border:none;padding:10px 14px;border-radius:8px;cursor:pointer;text-align:left;color:#4A4A4A;">Keluar Akun</button>
                </form>
            </nav>
        </aside>

        <div class="content-wrap">
            @yield('content')
        </div>
    </div>
</body>
</html>