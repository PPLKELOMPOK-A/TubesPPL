<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodlink')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* === MASTER LAYOUT STYLES === */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        
        .nav-group { flex-grow: 1; padding: 0 15px; /* Memberikan jarak agar tidak full ke pinggir */ }
        
        .nav-item { 
            display: flex; 
            align-items: center; 
            padding: 12px 20px; 
            text-decoration: none; 
            color: #4A4A4A; 
            font-size: 14px; 
            font-weight: 500; 
            transition: 0.2s; 
            gap: 15px; 
            margin-bottom: 6px; 
            border-radius: 10px; /* Bentuk kotak yang elegan */
        }
        
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        
        /* Status Active (Kotak Melayang) */
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        
        /* Status Hover (Kotak Melayang) */
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }

        .logout-section { padding: 0 15px; margin-top: auto; }
        
        .logout-btn { 
            border: none; 
            background: none; 
            width: 100%; 
            text-align: left; 
            cursor: pointer; 
            color: #d9534f; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            padding: 12px 20px; 
            font-size: 14px; 
            font-weight: 500; 
            border-radius: 10px;
            transition: 0.2s;
        }
        
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }

        /* --- MAIN CONTENT --- */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; }
        
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        
        .profile-section { display: flex; align-items: center; gap: 12px; }
        
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }

        .container { padding: 30px 50px; max-width: 1100px; width: 100%; margin: 0 auto; }
    </style>
    
    @yield('styles')
</head>
<body>

    <!-- SIDEBAR MASTER -->
    <div class="sidebar">
        <div class="nav-group">
            <div class="brand">Foodlink</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="{{ route('riwayat-donasi.index') }}" class="nav-item {{ request()->routeIs('riwayat-donasi.*') ? 'active' : '' }}">
                <i class="fa-solid fa-hand-holding-heart"></i> Riwayat Donasi
            </a>
            <a href="{{ route('bukti.donasi') }}" class="nav-item {{ request()->routeIs('bukti.donasi*', 'bukti-donasi.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i> Bukti Donasi
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-comments"></i> Riwayat Koordinasi
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-arrow-rotate-left"></i> Retur Donasi
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

    <!-- MAIN PANEL -->
    <div class="main-panel">
        <!-- TOP BAR -->
        <div class="top-bar">
            <i class="fa-regular fa-bell"></i>
            <div class="profile-section">
                <span style="font-size: 13px; font-weight: 600; color: #444;">{{ Auth::user()->name }}</span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6B4F2A&color=fff" class="user-avatar" alt="User">
            </div>
        </div>

        <!-- CONTENT SECTION -->
        <div class="container">
            @yield('content')
        </div>
    </div>

</body>
</html>