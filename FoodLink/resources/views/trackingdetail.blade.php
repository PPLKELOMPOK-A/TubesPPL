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

   <!-- Main -->
    <main class="flex-1 p-8 space-y-6">
        <header>
            <h1 class="text-2xl font-bold">Detail Tracking Pengiriman</h1>
            <p class="text-gray-600">ID : FL-{{ $donation->id }}</p>
        </header>

        <div class="grid grid-cols-3 gap-6">
            <!-- MAP -->
            <div class="col-span-2 space-y-4">
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Lokasi Pengiriman</h2>

                    <iframe
                        src="https://maps.google.com/?q={{ urlencode($donation->alamat) }}&output=embed"
                        width="100%" height="200" style="border:0;">
                    </iframe>

                    <ul class="mt-3 text-sm space-y-1">
                        <li>📍 Lokasi Pengambilan: {{ $donation->alamat }}</li>
                        <li>🚚 Status: {{ ucfirst(str_replace('_',' ', $donation->status)) }}</li>
                    </ul>
                </div>

                <!-- TIMELINE -->
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Timeline Pengiriman</h2>

                    <ul class="text-sm space-y-2">
                        @forelse($donation->trackings as $t)
                            <li>
                                {{ $t->status }} - {{ $t->created_at->format('d M Y H:i') }}
                            </li>
                        @empty
                            <li class="text-gray-400">Belum ada tracking</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- DETAIL -->
            <div class="space-y-4">
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Detail Donasi</h2>

                    <p><strong>Nama</strong> : {{ $donation->judul }}</p>
                    <p><strong>Jumlah</strong> : {{ $donation->quantity ?? '-' }}</p>
                    <p><strong>Kategori</strong> : {{ $donation->kategori }}</p>
                    <p><strong>Tanggal</strong> : {{ $donation->tanggal }}</p>
                    <p><strong>Estimasi</strong> : {{ $donation->estimated_time ?? '-' }} menit</p>
                    <p><strong>Catatan</strong> : {{ $donation->deskripsi }}</p>
                </div>

                <!-- KURIR -->
                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">Informasi Kurir</h2>

                    @if($donation->courier)
                        <p>Nama: {{ $donation->courier->nama }}</p>
                        <p>No HP: {{ $donation->courier->telepon }}</p>
                    @else
                        <p class="text-gray-400">Belum ada kurir</p>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection