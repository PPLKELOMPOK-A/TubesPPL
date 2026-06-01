app blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodLink Admin')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html,
body {
    width: 100%;
    height: 100%;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden;
    position: relative;
    top: 0;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #FFF9EE;
}

.alert {
    font-family: 'Montserrat', sans-serif;
}

main {
    background: #FFF9EE;
    margin: 0;
    padding: 0;
}

main::-webkit-scrollbar {
    width: 8px;
}

main::-webkit-scrollbar-thumb {
    background: #c8b28b;
    border-radius: 10px;
}
</style>
</head>

<body class="bg-[#FFF9EE] text-gray-800 antialiased !m-0 !p-0 block">

<div class="flex w-full h-full fixed inset-0 overflow-hidden">
    <aside class="w-64 bg-[#F8E7C1] flex flex-col justify-between border-r border-gray-200 flex-shrink-0 !mt-0">
        <div class="p-6">
            <h3 class="text-2xl font-bold text-[#6B4F2A] mb-8 px-4 tracking-tight">Foodlink</h3>

            <nav class="space-y-1.5 text-sm font-medium">
                
                {{-- CEK JIKA USER LOGIN SEBAGAI ADMIN --}}
                @if(Auth::check() && Auth::user()->role == 'admin')
                    
                    <a href="/admin/dashboard"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/dashboard*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-house text-base w-5 text-center {{ request()->is('admin/dashboard*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Beranda Admin</span>
                    </a>

                    <a href="/admin/validasi-proses-donasi"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/validasi-proses-donasi*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-check-to-slot text-base w-5 text-center {{ request()->is('admin/validasi-proses-donasi*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Validasi Donasi</span>
                    </a>

                    <a href="/admin/retur-donasi"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/retur-donasi*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-arrow-rotate-left text-base w-5 text-center {{ request()->is('admin/retur-donasi*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Retur Donasi</span>
                    </a>

                   <a href="/admin/penugasan"
   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/penugasan*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
    <i class="fa-solid fa-users-gear text-base w-5 flex-shrink-0 text-center {{ request()->is('admin/penugasan*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
    <span class="whitespace-nowrap">Penugasan Relawan</span>
</a>

                    <a href="/admin/mitra"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/mitra*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-handshake text-base w-5 text-center {{ request()->is('admin/mitra*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Kerja Sama Mitra</span>
                    </a>
                    
                    <a href="/admin/report"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/report*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-chart-pie text-base w-5 text-center {{ request()->is('admin/report*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Dashboard Laporan</span>
                    </a>

                {{-- JIKA USER BIASA YANG LOGIN --}}
                @else
                    <a href="/dashboard"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('dashboard*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-house text-base w-5 text-center {{ request()->is('dashboard*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Beranda</span>
                    </a>

                    <a href="/riwayat-donasi"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('riwayat-donasi*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-handholding-heart text-base w-5 text-center {{ request()->is('riwayat-donasi*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Riwayat Donasi</span>
                    </a>

                    <a href="{{ route('review.index') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('review*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-star text-base w-5 text-center {{ request()->is('review*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Rating & Review</span>
                    </a>

                    <a href="{{ route('admin.komunitas.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/komunitas*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                        <i class="fa-solid fa-users text-base w-5 text-center {{ request()->is('admin/komunitas*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                        <span>Komunitas</span>
                    </a>
                @endif

                <hr class="border-gray-300 my-4 opacity-50">

                {{-- MENU GLOBAL (BISA DIAKSES KEDUA ROLE) --}}
                <a href="/admin/koordinasi"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/koordinasi*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                    <i class="fa-solid fa-comments text-base w-5 text-center {{ request()->is('admin/koordinasi*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                    <span>Riwayat Koordinasi</span>
                </a>

                <a href="/admin/chat"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/chat*') ? 'bg-[#6B4F2A] text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-[rgba(107,79,42,0.1)]' }}">
                    <i class="fa-solid fa-message text-base w-5 text-center {{ request()->is('admin/chat*') ? 'text-white' : 'text-[#6B4F2A]' }}"></i>
                    <span>Chat</span>
                </a>

            </nav>
        </div>

        <div class="p-6 text-sm font-medium">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-[#d9534f] hover:bg-[rgba(217,83,79,0.1)] transition duration-200">
                    <i class="fa-solid fa-right-from-bracket text-base w-5 text-center"></i>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-full min-h-0 overflow-hidden bg-[#FFF9EE] !mt-0">
        
        <header class="w-full h-[70px] bg-white flex items-center justify-end px-10 gap-6 border-b border-gray-100 z-40 shadow-sm flex-shrink-0 !mt-0 !pt-0">
            <button class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-regular fa-bell text-lg"></i>
            </button>

            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-gray-700">
                    {{ Auth::check() ? Auth::user()->name : 'Admin Foodlink' }}
                </span>

                <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-[#F8E7C1]">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::check() ? urlencode(Auth::user()->name) : 'Admin' }}&background=6B4F2A&color=fff"
                         class="w-full h-full object-cover"
                         alt="Avatar">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto bg-[#FFF9EE] !m-0 !p-0">
            <div class="px-12 py-8">
                @yield('content')
            </div>
        </main>

    </div>
</div>

@stack('scripts')

</body>
</html>