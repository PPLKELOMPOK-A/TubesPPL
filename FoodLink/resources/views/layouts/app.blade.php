<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodLink Admin')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#FBEBCE] flex flex-col justify-between border-r border-gray-200">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 px-4">Admin</h3>

            <nav class="space-y-2 text-sm font-medium">

                <a href="{{ route('validasi.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition 
                   {{ request()->routeIs('validasi.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                    <span>Validasi Donasi</span>
                </a>

                <a href="#"
                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition text-gray-700 hover:bg-[#f3dcb5]">
                    <span>Chat</span>
                </a>

                <a href="{{ route('admin.retur.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition 
                   {{ request()->routeIs('admin.retur.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                    <span>Retur Donasi</span>
                </a>

                <a href="{{ route('penugasan.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition 
                   {{ request()->routeIs('penugasan.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                    <span>Penugasan Relawan</span>
                </a>

                <a href="{{ route('mitra.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-md transition 
                   {{ request()->routeIs('mitra.*') ? 'bg-[#5A3D2B] text-white' : 'text-gray-700 hover:bg-[#f3dcb5]' }}">
                    <span>Kerja Sama Mitra</span>
                </a>

            </nav>
        </div>

        <!-- LOGOUT -->
        <div class="p-6 text-sm">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 rounded-md hover:bg-[#f3dcb5] transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-8 bg-white">

        <!-- TOP RIGHT PROFILE -->
        <div class="flex justify-end items-center mb-8 pb-4 border-b">
            <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=Admin"
                     class="w-full h-full object-cover">
            </div>
        </div>

        <!-- CONTENT -->
        @yield('content')

    </main>

</div>

</body>
</html>