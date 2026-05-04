@extends('layouts.app')

@section('title', 'Validasi Proses Donasi')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-8">Validasi Proses Donasi</h1>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#FFF9F0] p-6 rounded-xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800">3</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-tight">Masuk Hari Ini</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800">1</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-tight">Perlu Validasi</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800">2</div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-tight">Sudah di Proses</div>
        </div>
    </div>

    <!-- TABS -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('validasi.index') }}" class="bg-[#E5E7EB] text-gray-700 px-6 py-2 rounded-lg text-xs font-bold shadow-sm">Menunggu Validasi</a>
        <a href="{{ route('validasi.disetujui') }}" class="bg-white border border-gray-100 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50 transition">Disetujui</a>
        <a href="{{ route('validasi.ditolak') }}" class="bg-white border border-gray-100 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50 transition">Ditolak</a>
    </div>

    <!-- LIST DONASI -->
    <div class="space-y-4">
        @forelse($donations as $item)
        <div class="bg-white border border-gray-100 p-5 rounded-2xl flex flex-col md:flex-row gap-6 relative shadow-sm">
            
            <!-- PERBAIKAN: Thumbnail Gambar dengan Logika Ekstensi Spesifik -->
            <div class="w-28 h-28 bg-[#FFF9F0] rounded-xl flex-shrink-0 flex items-center justify-center border border-[#FBEBCE] overflow-hidden">
                <img src="
                    @if($item->foto)
                        {{ asset('storage/'.$item->foto) }}
                    @elseif(str_contains(strtolower($item->judul ?? ''), 'mie') || str_contains(strtolower($item->judul ?? ''), 'bakso'))
                        {{ asset('img/mie ayam bakso.jpeg') }}
                    @elseif(str_contains(strtolower($item->judul ?? ''), 'nasi'))
                        {{ asset('img/nasi kotak.avif') }}
                    @elseif(str_contains(strtolower($item->judul ?? ''), 'roti') || str_contains(strtolower($item->judul ?? ''), 'pastry'))
                        {{ asset('img/roti pastry.jpg') }}
                    @else
                        https://cdn-icons-png.flaticon.com/512/3081/3081840.png
                    @endif
                " class="w-full h-full object-cover">
            </div>

            <div class="flex-1 pt-1">
                <div class="flex justify-between items-start">
                    <h2 class="text-base font-bold text-gray-800">{{ $item->judul }}</h2>
                    <span class="text-[#D9A74A] text-[9px] font-bold px-3 py-1 rounded-full uppercase tracking-widest bg-[#FFF4E0]">Menunggu</span>
                </div>
                
                <div class="text-[10px] text-gray-400 mt-1 flex gap-4">
                    <span>Donatur: <span class="text-gray-500">{{ $item->kategori ?? 'Warung Pak Budi' }}</span></span>
                    <span>Dikirim: {{ optional($item->created_at)->format('d M, H:i') }}</span>
                </div>

                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="bg-[#FFF4E0] text-[#D9A74A] text-[9px] font-bold px-3 py-1 rounded-md">{{ $item->quantity }} Porsi</span>
                    <span class="bg-[#A0AEC0] text-white text-[9px] font-bold px-3 py-1 rounded-md">Layak konsumsi</span>
                    @if($item->expired_at)
                    <span class="bg-[#A0AEC0] text-white text-[9px] font-bold px-3 py-1 rounded-md">Expired: {{ \Carbon\Carbon::parse($item->expired_at)->format('d M H:i') }}</span>
                    @endif
                </div>

                <div class="flex gap-2 mt-4">
                    <form action="{{ route('validasi.setujui', $item->id) }}" method="POST"> @csrf <button class="bg-[#81E6D9] text-white font-bold py-1.5 px-8 rounded-lg text-[11px] hover:bg-[#4FD1C5] transition shadow-sm">Setujui</button></form>
                    <form action="{{ route('validasi.tolak', $item->id) }}" method="POST"> @csrf <button class="bg-[#FEB2B2] text-white font-bold py-1.5 px-8 rounded-lg text-[11px] hover:bg-[#FC8181] transition shadow-sm">Tolak</button></form>
                </div>
            </div>
        </div>
        @empty
            <div class="text-center py-10 text-gray-300 italic">Tidak ada data donasi.</div>
        @endforelse
    </div>
</div>
@endsection