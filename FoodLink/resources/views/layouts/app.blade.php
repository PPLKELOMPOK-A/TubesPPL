<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodlink - Dashboard')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Montserrat:wght@400;500;700&family=Manrope:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; flex-shrink: 0; }
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; transition: 0.2s; gap: 15px; margin-bottom: 6px; border-radius: 10px; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; text-align: center; }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }
        
        .logout-section { padding: 0 15px; margin-top: auto; }
        .logout-btn { border: none; background: none; width: 100%; text-align: left; cursor: pointer; color: #d9534f; display: flex; align-items: center; gap: 15px; padding: 12px 20px; font-size: 14px; font-weight: 500; border-radius: 10px; transition: 0.2s; }
        .logout-btn i { width: 20px; font-size: 18px; text-align: center; }
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }

        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; background: #FFF9EE; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; flex-shrink: 0; position: sticky; top: 0; z-index: 10; }
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        .profile-section { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }
        .main-content-canvas { padding: 40px 50px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-family: 'Montserrat', sans-serif; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>

    @yield('styles')
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            
            @if(Auth::check() && Auth::user()->role == 'admin')

                <a href="{{ url('/admin/dashboard') }}" class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda Admin
                </a>
                <a href="{{ route('admin.validasi.index') }}" class="nav-item {{ Request::is('admin/validasi-proses-donasi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-check-to-slot"></i> Validasi Donasi
                </a>
                <a href="{{ route('retur.index') }}" class="nav-item {{ request()->routeIs('retur.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi
                </a>
                <a href="#" class="nav-item">
                    <i class="fa-solid fa-users-gear"></i> Penugasan Relawan
                </a>
                <a href="{{ route('admin.report.index') }}" class="nav-item {{ Request::is('admin/report') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard Laporan
                </a>
                <a href="{{ route('mitra.index') }}" class="nav-item {{ Request::is('admin/kerjasama-mitra*') ? 'active' : '' }}">
                    <i class="fa-solid fa-handshake"></i> Kerjasama Mitra
                </a>
                <a href="{{ route('dropbox.index') }}" class="nav-item {{ Request::is('admin/drop-box*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i> Drop Box
                </a>

            @else

                <a href="{{ url('/dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
                <a href="{{ route('riwayat-donasi.index') }}" class="nav-item {{ Request::is('riwayat-donasi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-heart"></i> Riwayat Donasi
                </a>
                <a href="{{ route('bukti.donasi') }}" class="nav-item {{ Request::is('bukti-donasi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice"></i> Bukti Donasi
                </a>
                <a href="{{ url('/tips') }}" class="nav-item {{ Request::is('tips*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar"></i> Tips
                </a>
                <a href="{{ route('donation.tracking') }}" class="nav-item {{ Request::is('tracking*') ? 'active' : '' }}">
                    <i class="fa-solid fa-location-dot"></i> Tracking
                </a>

            @endif

            <a href="#" class="nav-item">
                <i class="fa-solid fa-comments"></i> Riwayat Koordinasi
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-comments"></i> Chat
            </a>

        </div>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar Akun
                </button>
            </form>
        </div>
    </div>

    <div class="main-panel">
        <div class="top-bar">
            <i class="fa-regular fa-bell"></i>
            <div class="profile-section">
                <span style="font-size: 13px; font-weight: 600; color: #444;">
                    {{ Auth::user() ? Auth::user()->name : 'User' }}
                </span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user() ? Auth::user()->name : 'User' }}&background=6B4F2A&color=fff" class="user-avatar" alt="User Avatar">
            </div>
        </div>

        @yield('content')
    </div>

    @stack('scripts')

</body>
</html>