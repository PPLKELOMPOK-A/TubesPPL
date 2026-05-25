<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodlink Admin')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex min-h-screen w-full">
        <aside class="w-64 bg-[#FBEBCE] flex flex-col justify-between border-r border-gray-200 shrink-0">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 px-4">Admin</h3>
                <nav class="space-y-2 text-sm font-medium">
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-home w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.validasi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.validasi.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-check w-5 text-center"></i>
                        <span>Validasi Donasi</span>
                    </a>

                    <a href="{{ route('chat.user') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('chat.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-comments w-5 text-center"></i>
                        <span>Chat</span>
                    </a>

                    <a href="{{ route('admin.retur.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.retur.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-rotate-left w-5 text-center"></i>
                        <span>Retur Donasi</span>
                    </a>

                    <a href="{{ route('admin.penugasan.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('admin.penugasan.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-users w-5 text-center"></i>
                        <span>Penugasan Relawan</span>
                    </a>

                    <a href="{{ route('mitra.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('mitra.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-handshake w-5 text-center"></i>
                        <span>Kerja Sama Mitra</span>
                    </a>

                    <a href="{{ route('dropbox.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('dropbox.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <i class="fa fa-box-archive w-5 text-center"></i>
                        <span>Drop Box</span>
                    </a>

                </nav>
            </div>
            <div class="p-6 text-sm">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-red-600 hover:text-red-800 font-bold w-full px-4 py-2 hover:bg-red-100 rounded-md transition">
                        <i class="fa fa-sign-out w-5 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 overflow-y-auto w-full">
            <div class="container mx-auto py-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>