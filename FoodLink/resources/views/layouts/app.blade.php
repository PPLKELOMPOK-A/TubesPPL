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
        body { display: flex; background-color: #fdfdfd; height: 100vh; overflow: hidden; }
        .sidebar { width: 280px; background-color: #F8E7C1; display: flex; flex-direction: column; padding: 25px 0; border-right: 1px solid #e0e0e0; }
        .brand { padding: 0 30px; margin-bottom: 30px; font-weight: 700; font-size: 24px; color: #6B4F2A; letter-spacing: -0.5px; }
        .nav-group { flex-grow: 1; padding: 0 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: #4A4A4A; font-size: 14px; font-weight: 500; transition: 0.2s; gap: 15px; margin-bottom: 6px; border-radius: 10px; }
        .nav-item i { width: 20px; font-size: 18px; color: #6B4F2A; }
        .nav-item.active { background-color: #6B4F2A; color: #FFFFFF; }
        .nav-item.active i { color: #FFFFFF; }
        .nav-item:hover:not(.active) { background-color: rgba(107, 79, 42, 0.1); }
        .logout-section { padding: 0 15px; margin-top: auto; }
        .logout-btn { border: none; background: none; width: 100%; text-align: left; cursor: pointer; color: #d9534f; display: flex; align-items: center; gap: 15px; padding: 12px 20px; font-size: 14px; font-weight: 500; border-radius: 10px; transition: 0.2s; }
        .logout-btn:hover { background-color: rgba(217, 83, 79, 0.1); }
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-bar { height: 70px; background: #FFFFFF; display: flex; align-items: center; justify-content: flex-end; padding: 0 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; }
        .top-bar i { font-size: 18px; color: #888; cursor: pointer; }
        .profile-section { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #F8E7C1; }
        .container { padding: 30px 50px; max-width: 1100px; width: 100%; margin: 0 auto; }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#FBEBCE] flex flex-col justify-between border-r border-gray-200">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 px-4">Admin</h3>
                <nav class="space-y-2 text-sm font-medium">
                    <a href="{{ route('admin.validasi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.validasi.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Validasi Donasi</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-md transition text-gray-700 hover:bg-[#f3dcb5]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        <span>Chat</span>
                    </a>
                    <a href="{{ route('admin.retur.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.retur.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Retur Donasi</span>
                    </a>
                    <a href="{{ route('admin.penugasan.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.penugasan.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Penugasan Relawan</span>
                    </a>
                </nav>
            </div>
            <div class="p-6 text-sm">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-gray-700 hover:text-black font-medium w-full px-4 py-2 hover:bg-[#f3dcb5] rounded-md transition">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="container">
            @yield('content')
        </div>
    </div>
</body>
</html>
