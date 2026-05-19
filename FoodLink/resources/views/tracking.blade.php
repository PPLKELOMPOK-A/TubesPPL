<!-- resources/views/tracking.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-yellow-100 p-6 flex flex-col">
        <h2 class="font-bold mb-6">Donatur</h2>
        <nav class="flex flex-col gap-4">
            <a href="#" class="p-2 rounded bg-yellow-200 font-semibold">Beranda</a>
            <a href="#" class="p-2 rounded hover:bg-yellow-200">Validasi Donasi</a>
            <a href="#" class="p-2 rounded hover:bg-yellow-200">Chat</a>
            <a href="#" class="p-2 rounded hover:bg-yellow-200">Retur Donasi</a>
            <a href="#" class="p-2 rounded hover:bg-yellow-200">Penugasan Relawan</a>
        </nav>
        <div class="mt-auto">
            <a href="#" class="flex items-center gap-2 text-gray-700"><span>↩</span> Logout</a>
        </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8">
        <header class="mb-8">
            <h1 class="text-xl font-semibold mb-2">Tracking Pengiriman</h1>
            <p class="text-gray-600">Monitor pengiriman donasi makanan secara Real-Time</p>
        </header>

        <!-- Statistik -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-4 bg-yellow-100 rounded shadow text-center">
                <p class="text-sm text-gray-500">TOTAL DONASI</p>
                <p class="text-xl font-bold" id="total-donasi">{{ $total }}</p>
            </div>
            <div class="p-4 bg-yellow-100 rounded shadow text-center">
                <p class="text-sm text-gray-500">TERKIRIM</p>
                <p class="text-xl font-bold" id="terkirim">{{ $terkirim }}</p>
            </div>
            <div class="p-4 bg-yellow-100 rounded shadow text-center">
                <p class="text-sm text-gray-500">DALAM PERJALANAN</p>
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

@section('scripts')
<script>
const statusClasses = {
    menunggu: 'bg-red-200 text-red-800',
    dalam_perjalanan: 'bg-yellow-200 text-yellow-800',
    terkirim: 'bg-green-200 text-green-800'
};

function loadDonations() {
    fetch('/donations/json')
        .then(res => res.json())
        .then(data => {
            // Update statistik
            document.getElementById('total-donasi').innerText = data.length;
            document.getElementById('terkirim').innerText = data.filter(d => d.status === 'terkirim').length;
            document.getElementById('dalam-perjalanan').innerText = data.filter(d => d.status === 'dalam_perjalanan').length;

            // Update list donasi
            const list = document.getElementById('donation-list');
            list.innerHTML = '';
            data.forEach(d => {
                const card = document.createElement('div');
                card.classList.add('border', 'rounded-xl', 'p-4', 'shadow', 'bg-white', 'flex', 'flex-col', 'justify-between');
                card.innerHTML = `
                    <div>
                        <span class="px-2 py-1 rounded-full text-sm font-semibold ${statusClasses[d.status] || ''}">
                            ${d.status.replace('_',' ')}
                        </span>
                        <h3 class="mt-2 font-bold text-lg">${d.judul}</h3>
                        <p class="text-gray-700 mt-1">📦 ${d.quantity || '-'} - ${d.food_type || '-'}</p>
                        <p class="text-gray-700 mt-1">📍 ${d.alamat}</p>
                    </div>
                    <p class="mt-2 text-gray-500 text-sm">Est. ${d.estimated_time || '-'} menit</p>
                `;
                list.appendChild(card);
            });
        });
}

// Load pertama kali
loadDonations();

// Polling setiap 5 detik
setInterval(loadDonations, 5000);
</script>
@endsection