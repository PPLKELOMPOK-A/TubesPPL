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

     <!-- Main Content -->
    <main class="flex-1 p-8">

        {{-- ================= HEADER ================= --}}
        <header class="mb-8">
            <h1 class="text-2xl font-semibold mb-2">Tracking Pengiriman</h1>
            <p class="text-gray-600">Monitor pengiriman donasi makanan secara Real-Time</p>
        </header>

        {{-- ================= DATA HANDLING ================= --}}
        @php
            // Kalau dari controller ada → pakai DB
            // Kalau tidak ada → fallback dummy
            if (!isset($donations)) {
                $donations = collect([
                    (object)['id'=>1,'status'=>'menunggu','judul'=>'Nasi Kotak A','quantity'=>50,'food_type'=>'Nasi + Ayam','alamat'=>'Jl. Merpati No. 12','estimated_time'=>30],
                    (object)['id'=>2,'status'=>'dalam_perjalanan','judul'=>'Snack Box B','quantity'=>20,'food_type'=>'Snack + Minuman','alamat'=>'Jl. Kenari No. 7','estimated_time'=>15],
                    (object)['id'=>3,'status'=>'terkirim','judul'=>'Makanan Siap Saji C','quantity'=>10,'food_type'=>'Mie + Telur','alamat'=>'Jl. Anggrek No. 21','estimated_time'=>0],
                ]);
            }

            $total = $donations->count();
            $terkirim = $donations->where('status', 'terkirim')->count();
            $dalamPerjalanan = $donations->where('status', 'dalam_perjalanan')->count();

            $statusClasses = [
                'menunggu' => 'bg-red-200 text-red-800',
                'dalam_perjalanan' => 'bg-yellow-200 text-yellow-800',
                'terkirim' => 'bg-green-200 text-green-800',
            ];
        @endphp

        {{-- ================= STATISTIK ================= --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-4 rounded-xl shadow text-center" style="background-color: #FFF2E0;">
                <p class="text-sm text-gray-600">TOTAL DONASI</p>
                <p class="text-xl font-bold">{{ $total }}</p>
            </div>

            <div class="p-4 rounded-xl shadow text-center" style="background-color: #FFF2E0;">
                <p class="text-sm text-gray-600">TERKIRIM</p>
                <p class="text-xl font-bold">{{ $terkirim }}</p>
            </div>

            <div class="p-4 rounded-xl shadow text-center" style="background-color: #FFF2E0;">
                <p class="text-sm text-gray-600">DALAM PERJALANAN</p>
                <p class="text-xl font-bold">{{ $dalamPerjalanan }}</p>
            </div>
        </div>

        {{-- ================= LIST DONASI ================= --}}
        <div class="grid grid-cols-2 gap-6">
            @forelse ($donations as $d)
                <a href="{{ route('tracking.detail', ['id' => $d->id]) }}"
                   class="border rounded-xl p-4 shadow bg-white flex flex-col justify-between hover:shadow-lg transition">

                    <div>
                        <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$d->status] ?? '' }}">
                            {{ ucfirst(str_replace('_',' ',$d->status)) }}
                        </span>

                        <h3 class="mt-2 font-bold text-lg">{{ $d->judul }}</h3>

                        <p class="text-gray-700 mt-1">
                            📦 {{ $d->quantity ?? '-' }} - {{ $d->food_type ?? '-' }}
                        </p>

                        <p class="text-gray-700 mt-1">
                            📍 {{ $d->alamat }}
                        </p>
                    </div>

                    <p class="mt-2 text-gray-500 text-sm">
                        Est. {{ $d->estimated_time ?? '-' }} menit
                    </p>

                </a>
            @empty
                <p class="col-span-2 text-center text-gray-500">
                    Belum ada data donasi
                </p>
            @endforelse
        </div>

        {{-- ================= PAGINATION (kalau dari DB) ================= --}}
        @if(method_exists($donations, 'links'))
            <div class="mt-8">
                {{ $donations->links() }}
            </div>
        @endif

    </main>
</div>
@endsection