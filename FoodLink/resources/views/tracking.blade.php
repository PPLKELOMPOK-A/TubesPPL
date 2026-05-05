@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 p-6 flex flex-col" style="background-color: #FFE6B5;">
    <h2 class="font-bold mb-6 text-lg">Donatur</h2>
    <nav class="flex flex-col gap-3 text-gray-800">
        <a href="#" class="p-2 rounded font-semibold flex items-center gap-2 bg-[#5C4421] text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6M5 10v10h14V10"/>
            </svg>
            Beranda
        </a>
        <a href="#" class="p-2 rounded hover:bg-opacity-80 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m2 0a2 2 0 00-2-2H9a2 2 0 000 4h6a2 2 0 002-2z"/>
            </svg>
            Validasi Donasi
        </a>
        <a href="#" class="p-2 rounded hover:bg-opacity-80 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18v-6H3v6z"/>
            </svg>
            Chat
        </a>
        <a href="#" class="p-2 rounded hover:bg-opacity-80 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m2 0a2 2 0 00-2-2H9a2 2 0 000 4h6a2 2 0 002-2z"/>
            </svg>
            Retur Donasi
        </a>
        <a href="#" class="p-2 rounded hover:bg-opacity-80 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 20h14v-6H5v6z"/>
            </svg>
            Penugasan Relawan
        </a>
    </nav>
    <div class="mt-auto">
        <a href="#" class="flex items-center gap-2 text-gray-700 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7"/>
            </svg>
            Logout
        </a>
    </div>
</aside>

    <!-- Main content -->
    <main class="flex-1 p-8">
        @php
            use Illuminate\Pagination\LengthAwarePaginator;

            // === DATA DUMMY ===
            $allDonations = [
                (object)['status'=>'menunggu','judul'=>'Nasi Kotak A','quantity'=>50,'food_type'=>'Nasi + Ayam','alamat'=>'Jl. Merpati No. 12','estimated_time'=>30],
                (object)['status'=>'dalam_perjalanan','judul'=>'Snack Box B','quantity'=>20,'food_type'=>'Snack + Minuman','alamat'=>'Jl. Kenari No. 7','estimated_time'=>15],
                (object)['status'=>'terkirim','judul'=>'Makanan Siap Saji C','quantity'=>10,'food_type'=>'Mie + Telur','alamat'=>'Jl. Anggrek No. 21','estimated_time'=>0],
                (object)['status'=>'menunggu','judul'=>'Paket Lauk D','quantity'=>25,'food_type'=>'Ayam + Sayur','alamat'=>'Jl. Melati No. 3','estimated_time'=>20],
                (object)['status'=>'dalam_perjalanan','judul'=>'Bento E','quantity'=>15,'food_type'=>'Bento + Buah','alamat'=>'Jl. Cempaka No. 9','estimated_time'=>10],
                (object)['status'=>'terkirim','judul'=>'Makanan Siap Saji F','quantity'=>30,'food_type'=>'Nasi + Ikan','alamat'=>'Jl. Flamboyan No. 5','estimated_time'=>5]
            ];

            // === PAGINATION MANUAL ===
            $page = request()->get('page', 1);
            $perPage = 2;
            $offset = ($page - 1) * $perPage;
            $itemsForCurrentPage = array_slice($allDonations, $offset, $perPage);

            $donations = new LengthAwarePaginator(
                $itemsForCurrentPage,
                count($allDonations),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            // Statistik
            $total = count($allDonations);
            $terkirim = count(array_filter($allDonations, fn($d) => $d->status === 'terkirim'));
            $dalamPerjalanan = count(array_filter($allDonations, fn($d) => $d->status === 'dalam_perjalanan'));
        @endphp

        <header class="mb-8">
            <h1 class="text-2xl font-semibold mb-2">Tracking Pengiriman</h1>
            <p class="text-gray-600">Monitor pengiriman donasi makanan secara Real-Time</p>
        </header>

        <!-- Statistik -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-4 rounded-xl shadow text-center" style="background-color: #FFF2E0;">
                <p class="text-sm text-gray-600">TOTAL DONASI</p>
                <p class="text-xl font-bold" id="total-donasi">{{ $total }}</p>
            </div>
            <div class="p-4 rounded-xl shadow text-center" style="background-color: #FFF2E0;">
                <p class="text-sm text-gray-600">TERKIRIM</p>
                <p class="text-xl font-bold" id="terkirim">{{ $terkirim }}</p>
            </div>
            <div class="p-4 rounded-xl shadow text-center" style="background-color: #FFF2E0;">
                <p class="text-sm text-gray-600">DALAM PERJALANAN</p>
                <p class="text-xl font-bold" id="dalam-perjalanan">{{ $dalamPerjalanan }}</p>
            </div>
        </div>

        <!-- List Donasi -->
        <div id="donation-list" class="grid grid-cols-2 gap-6">
            @php
                $statusClasses = [
                    'menunggu' => 'bg-red-200 text-red-800',
                    'dalam_perjalanan' => 'bg-yellow-200 text-yellow-800',
                    'terkirim' => 'bg-green-200 text-green-800',
                ];
            @endphp
            @foreach ($donations as $d)
                <div class="border rounded-xl p-4 shadow bg-white flex flex-col justify-between">
                    <div>
                        <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$d->status] ?? '' }}">
                            {{ ucfirst(str_replace('_',' ',$d->status)) }}
                        </span>
                        <h3 class="mt-2 font-bold text-lg">{{ $d->judul }}</h3>
                        <p class="text-gray-700 mt-1">📦 {{ $d->quantity ?? '-' }} - {{ $d->food_type ?? '-' }}</p>
                        <p class="text-gray-700 mt-1">📍 {{ $d->alamat }}</p>
                    </div>
                    <p class="mt-2 text-gray-500 text-sm">Est. {{ $d->estimated_time ?? '-' }} menit</p>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $donations->links() }}
        </div>
    </main>
</div>
@endsection