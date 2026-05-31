@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 p-6 flex flex-col" style="background-color: #FFE6B5;">
        <h2 class="font-bold mb-6 text-lg">Donatur</h2>
        <nav class="flex flex-col gap-3 text-gray-800">
            <a href="#" class="p-2 rounded font-semibold flex items-center gap-2 bg-[#5C4421] text-white">Beranda</a>
            <a href="#" class="p-2 rounded flex items-center gap-2 hover:bg-opacity-80">Validasi Donasi</a>
            <a href="#" class="p-2 rounded flex items-center gap-2 hover:bg-opacity-80">Chat</a>
            <a href="#" class="p-2 rounded flex items-center gap-2 hover:bg-opacity-80">Retur Donasi</a>
            <a href="#" class="p-2 rounded flex items-center gap-2 hover:bg-opacity-80">Penugasan Relawan</a>
        </nav>
        <div class="mt-auto">
            <a href="#" class="flex items-center gap-2 text-gray-700 font-semibold">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 space-y-6">
        <!-- Header -->
        <header class="mb-4">
            <h1 class="text-2xl font-bold">Detail Tracking Pengiriman</h1>
            <p class="text-gray-600">ID : FL-001</p>
        </header>

        <div class="grid grid-cols-3 gap-6">
            <!-- Map + Lokasi -->
            <div class="col-span-2 space-y-4">
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Lokasi Pengiriman</h2>
                    <iframe src="https://maps.google.com/?q=-6.1751,106.827&output=embed"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                    <ul class="mt-3 text-sm text-gray-700 space-y-1">
                        <li class="flex items-center gap-2"><span class="text-green-500">●</span> Lokasi Pengambilan: Jl. Sudirman No.52, Jakarta Selatan</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">●</span> Lokasi Saat Ini: Jl. Thamrin No.10, Jakarta Pusat</li>
                        <li class="flex items-center gap-2"><span class="text-purple-500">●</span> Tujuan Akhir: Jl. Gatot Subroto No.45, Jakarta Pusat</li>
                    </ul>
                </div>

                <!-- Timeline -->
                <div class="bg-white p-4 rounded-xl shadow">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="font-semibold">Timeline Pengiriman</h2>
                        <button class="text-sm text-blue-500 hover:underline">Refresh</button>
                    </div>
                    <ul class="text-sm text-gray-700 space-y-3">
                        <li>✅ Donasi Dikonfirmasi - 30 Mei 2025, 09:00 WIB</li>
                        <li>👤 Relawan Ditugaskan - 30 Mei 2025, 10:00 WIB</li>
                        <li>🚚 Dalam Perjalanan - 30 Mei 2025, 10:30 WIB</li>
                    </ul>
                </div>
            </div>

            <!-- Detail Donasi + Kurir -->
            <div class="space-y-4">
                <!-- Detail Donasi -->
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Detail Donasi</h2>
                    <p><strong>Nama Makanan</strong> : Nasi Kotak & Lauk Pauk</p>
                    <p><strong>Jumlah</strong> : 200 porsi</p>
                    <p><strong>Kategori</strong> : Makanan Siap Saji</p>
                    <p><strong>Tanggal Donasi</strong> : 30 Mei 2025, 08:00 WIB</p>
                    <p><strong>Estimasi Pengiriman</strong> : 30 Mei 2025, 12:00 WIB</p>
                    <p><strong>Catatan</strong> : Makanan harus sampai sebelum jam 1 siang untuk makan anak-anak</p>
                </div>

                <!-- Informasi Kurir -->
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Informasi Kurir</h2>
                    <p>Nama Kurir: Budi Santoso</p>
                    <p>No. Telepon: +62 856-7890-1234</p>
                </div>
            </div>
        </div>
    </main>
</div>