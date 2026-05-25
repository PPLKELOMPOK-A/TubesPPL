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

        .sidebar {
            width: 280px;
            background-color: #F8E7C1;
            display: flex;
            flex-direction: column;
            padding: 25px 0;
            border-right: 1px solid #e0e0e0;
            flex-shrink: 0;
        }

        .brand {
            padding: 0 30px;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 24px;
            color: #6B4F2A;
        }

        .nav-group { flex-grow: 1; padding: 0 15px; }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: #4A4A4A;
            font-size: 14px;
            font-weight: 500;
            gap: 15px;
            border-radius: 10px;
            margin-bottom: 6px;
        }

        .nav-item i { width: 20px; color: #6B4F2A; }

        .nav-item:hover { background: rgba(107,79,42,0.1); }

        .nav-item.active {
            background-color: #6B4F2A;
            color: #fff;
        }

        .nav-item.active i { color: #fff; }

        .logout-section {
            padding: 15px;
            margin-top: auto;
        }

        .logout-btn {
            width: 100%;
            padding: 12px;
            border: none;
            background: none;
            color: #d9534f;
            text-align: left;
            cursor: pointer;
            border-radius: 10px;
        }

        .logout-btn:hover {
            background: rgba(217,83,79,0.1);
        }

        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background: #FFF9EE;
        }

        .top-bar {
            height: 70px;
            background: #fff;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 40px;
            border-bottom: 1px solid #eee;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-left: 10px;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="nav-group">

        <div class="brand">Foodlink</div>

        {{-- ADMIN --}}
        @if(Auth::check() && Auth::user()->role == 'admin')

            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa fa-home"></i> Dashboard
            </a>

            <a href="{{ route('admin.validasi.index') }}" class="nav-item {{ request()->routeIs('admin.validasi.*') ? 'active' : '' }}">
                <i class="fa fa-check"></i> Validasi Donasi
            </a>

            <a href="{{ route('admin.retur.index') }}" class="nav-item {{ request()->routeIs('admin.retur.*') ? 'active' : '' }}">
                <i class="fa fa-rotate-left"></i> Retur Donasi
            </a>

            <a href="{{ route('admin.penugasan.index') }}" class="nav-item {{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}">
                <i class="fa fa-users"></i> Penugasan
            </a>

            <a href="{{ route('chat.user') }}" class="nav-item {{ request()->routeIs('chat.user') ? 'active' : '' }}">
                <i class="fa fa-comments"></i> Chat
            </a>

        {{-- USER --}}
        @else

            <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fa fa-home"></i> Dashboard
            </a>

            <a href="{{ route('donation.tracking') }}" class="nav-item {{ request()->is('tracking*') ? 'active' : '' }}">
                <i class="fa fa-location-dot"></i> Tracking
            </a>

            <a href="{{ route('komunitas.index') }}" class="nav-item {{ request()->is('komunitas*') ? 'active' : '' }}">
                <i class="fa fa-users"></i> Komunitas
            </a>

            {{-- AMAN: cek route ada atau tidak --}}
            @if(Route::has('bukti.donasi'))
                <a href="{{ route('bukti.donasi') }}" class="nav-item">
                    <i class="fa fa-file"></i> Bukti Donasi
                </a>
            @endif

            {{-- review belum ada di web.php → amanin --}}
            @if(Route::has('review.index'))
                <a href="{{ route('review.index') }}" class="nav-item">
                    <i class="fa fa-star"></i> Rating & Review
                </a>
            @endif

            <a href="{{ route('chat.user') }}" class="nav-item">
                <i class="fa fa-comments"></i> Chat
            </a>

        @endif

    </div>

    {{-- LOGOUT --}}
    <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">
                <i class="fa fa-sign-out"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- MAIN --}}
<div class="main-panel">

    <div class="top-bar">
        <span>{{ Auth::user()->name ?? 'User' }}</span>

        <img class="user-avatar"
             src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}">
    </div>

    @yield('content')

</div>

</body>
</html>