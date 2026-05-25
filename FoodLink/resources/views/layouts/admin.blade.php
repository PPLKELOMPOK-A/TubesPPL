<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodlink Admin')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

                    <a href="{{ route('mitra.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('mitra.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Kerja Sama Mitra</span>
                    </a>

                    <a href="{{ route('dropbox.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md transition {{ request()->routeIs('dropbox.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>Drop Box</span>
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

        <div class="flex-1 overflow-y-auto w-full">
            <div class="container mx-auto py-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>