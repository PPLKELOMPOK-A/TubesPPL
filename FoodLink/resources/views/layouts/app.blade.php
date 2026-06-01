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
        
        /* --- SIDEBAR --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; flex-shrink: 0; }
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; transition: 0.2s; gap: 15px; margin-bottom: 6px; border-radius: 10px; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; text-align: center; }
        
        /* Status Active Sidebar */
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }
        
        /* Logout Button */
        .logout-section { padding: 0 15px; margin-top: auto; }
        .logout-btn { border: none; background: none; width: 100%; text-align: left; cursor: pointer; color: #d9534f; display: flex; align-items: center; gap: 15px; padding: 12px 20px; font-size: 14px; font-weight: 500; border-radius: 10px; transition: 0.2s; }
        .logout-btn i { width: 20px; font-size: 18px; text-align: center; }
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }

        /* --- MAIN PANEL & TOP BAR --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; background: #FFF9EE; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; flex-shrink: 0; position: sticky; top: 0; z-index: 10; }
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        
        /* --- PROFIL YANG BISA DIKLIK --- */
        .profile-section { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            text-decoration: none; /* Hilangkan garis bawah link */
            cursor: pointer; 
            transition: opacity 0.2s ease;
        }
        .profile-section:hover { 
            opacity: 0.7; /* Efek memudar sedikit saat di-hover */
        }
        
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }
        
        /* Reset padding canvas agar rapi di dalam main-panel */
        .main-content-canvas { padding: 40px 50px; }
        
        /* Helper untuk Notifikasi */
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-family: 'Montserrat', sans-serif; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* --- EFEK HOVER LONCENG NOTIFIKASI --- */
        .notif-bell {
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notif-bell i {
            transition: transform 0.2s ease, color 0.2s ease;
        }
        .notif-bell:hover i {
            transform: scale(1.15);
            color: #6B4F2A; /* Warna coklat khas brand */
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            
            {{-- CEK APAKAH USER SUDAH LOGIN DAN MEMILIKI ROLE ADMIN --}}
            @if(Auth::check() && Auth::user()->role == 'admin')
                
                {{-- MENU KHUSUS ADMIN --}}
                <a href="{{ url('/admin/dashboard') }}" class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda Admin
                </a>
                
                {{-- Validasi Donasi --}}
                <a href="{{ route('admin.validasi.index') }}" class="nav-item {{ Request::is('admin/validasi-proses-donasi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-check-to-slot"></i> Validasi Donasi
                </a>
                
                <a href="{{ route('admin.retur.index') }}" class="nav-item {{ request()->routeIs('admin.retur.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi
                </a>

                <a href="{{ route('admin.penugasan.index') }}" class="nav-item {{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i> Penugasan Relawan
                </a>
                
                <a href="{{ route('admin.report.index') }}" class="nav-item {{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard Laporan
                </a>

                {{-- MENU KERJASAMA MITRA --}}
                <a href="{{ route('mitra.index') }}" class="nav-item {{ Request::is('admin/kerjasama-mitra*') ? 'active' : '' }}">
                    <i class="fa-solid fa-handshake"></i> Kerjasama Mitra
                </a>

                

            {{-- JIKA YANG LOGIN ADALAH USER BIASA --}}
            @else
                
                {{-- MENU KHUSUS USER --}}
                <a href="{{ url('/dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
                <a href="{{ route('riwayat-donasi.index') }}" class="nav-item {{ Request::is('riwayat-donasi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-heart"></i> Riwayat Donasi
                </a>
                <a href="{{ route('donation.tracking') }}" class="nav-item {{ Request::is('tracking*') ? 'active' : '' }}">
                    <i class="fa-solid fa-location-dot"></i> Tracking
                </a>
            @endif

            {{-- MENU GLOBAL (BISA DIAKSES ADMIN & USER) --}}
            <a href="#" class="nav-item">
                <i class="fa-solid fa-comments"></i> Riwayat Koordinasi
            </a>
            <a href="{{ route('chat.user') }}" class="nav-item {{ Request::is('chat*') ? 'active' : '' }}">
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
            
            {{-- ICON LONCENG NOTIFIKASI --}}
            <a href="{{ route('notifikasi.index') }}" class="notif-bell" title="Pusat Notifikasi">
                <i class="fa-regular fa-bell"></i>
            </a>

            {{-- BAGIAN PROFIL YANG SUDAH DIUBAH MENJADI LINK --}}
            <a href="{{ route('profil.index') }}" class="profile-section" title="Edit Profil">
                <span style="font-size: 13px; font-weight: 600; color: #444;">
                    {{ Auth::user() ? Auth::user()->name : 'User' }}
                </span>
                
                @if(Auth::check() && !empty(Auth::user()->foto_profil))
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="user-avatar" alt="User Avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user() ? Auth::user()->name : 'User') }}&background=6B4F2A&color=fff" class="user-avatar" alt="User Avatar">
                @endif
            </a>
        </div>

        @yield('content')
        
    </div>

    @stack('scripts')

</body>
</html>