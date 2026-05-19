<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodLink - Sistem Mengolah Makanan Sisa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Mengambil palet warna dari UI mockup FoodLink */
        .bg-foodlink-cream { background-color: #F8EEDF; } /* Warna background krem UI */
        .text-foodlink-brown { color: #4A3022; } /* Warna teks dan tombol cokelat UI */
        .bg-foodlink-brown { background-color: #4A3022; }
        .bg-foodlink-brown-hover { background-color: #382419; }
    </style>
</head>
<body class="bg-foodlink-cream antialiased text-gray-800">

    <nav class="container mx-auto px-6 py-5 flex justify-between items-center">
        <div class="flex items-center">
            <span class="text-2xl font-bold text-foodlink-brown tracking-tight">FoodLink.</span>
        </div>
        <div class="hidden md:flex space-x-8">
            <a href="#tentang" class="text-foodlink-brown font-medium hover:opacity-70 transition">Tentang Kami</a>
            <a href="#fitur" class="text-foodlink-brown font-medium hover:opacity-70 transition">Fitur</a>
            <a href="#komunitas" class="text-foodlink-brown font-medium hover:opacity-70 transition">Komunitas</a>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-foodlink-brown font-semibold hover:underline">Masuk</a>
            <a href="{{ route('register') }}" class="bg-foodlink-brown text-white px-5 py-2 rounded-md font-medium hover:bg-foodlink-brown-hover transition shadow-sm">Daftar Akun</a>
        </div>
    </nav>

    <header class="container mx-auto px-6 py-16 md:py-24 flex flex-col-reverse md:flex-row items-center">
        <div class="md:w-1/2 mt-10 md:mt-0">
            <span class="text-sm font-bold text-orange-600 tracking-wider uppercase mb-2 block">Mendukung SDGs Poin 2: Zero Hunger</span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-foodlink-brown leading-tight mb-6">
                Selamatkan Makanan, <br>Sebarkan Kebaikan.
            </h1>
            <p class="text-lg text-gray-700 mb-8 max-w-lg leading-relaxed">
                FoodLink hadir untuk menjembatani pihak yang memiliki kelebihan makanan dengan mereka yang membutuhkan. Mari bersama mengurangi food waste dan tingkatkan ketahanan pangan.
            </p>
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('login') }}" class="bg-foodlink-brown text-center text-white px-8 py-3 rounded-md text-lg font-medium hover:bg-foodlink-brown-hover transition shadow-lg">
                    Mulai Berdonasi
                </a>
            </div>
        </div>
        <div class="md:w-1/2 flex justify-center">
            <img src="https://images.unsplash.com/photo-1593113565694-c7f71624b5d2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Food Donation" class="rounded-xl shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500">
        </div>
    </header>

    <section id="fitur" class="py-20 bg-white bg-opacity-40">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-foodlink-brown mb-4">Bagaimana FoodLink Bekerja?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Proses donasi yang terstruktur, transparan, dan mudah dipantau oleh semua pihak.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-foodlink-brown mb-3">Input Donasi Mudah</h3>
                    <p class="text-gray-600 leading-relaxed">Donatur dapat menginput detail makanan, foto, dan lokasi. Admin akan memverifikasi kelayakannya secara cepat.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-foodlink-brown mb-3">Tracking Pengiriman</h3>
                    <p class="text-gray-600 leading-relaxed">Pantau status donasi Anda secara real-time. Mulai dari penugasan relawan, penjemputan, hingga makanan tiba di tangan penerima.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-foodlink-brown mb-3">Drop Box Tersebar</h3>
                    <p class="text-gray-600 leading-relaxed">Tersedia alternatif pengumpulan donasi fisik di berbagai lokasi strategis yang dapat diakses dengan mudah oleh donatur.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-foodlink-brown text-white py-8">
        <div class="container mx-auto px-6 text-center md:flex md:justify-between md:items-center">
            <p class="text-sm text-gray-300">© 2026 FoodLink. Kelompok A PPL - SDGs 2.</p>
            <div class="mt-4 md:mt-0 flex justify-center space-x-6 text-sm text-gray-300">
                <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white">Syarat Ketentuan</a>
                <a href="#" class="hover:text-white">Hubungi Kami</a>
            </div>
        </div>
    </footer>

</body>
</html>