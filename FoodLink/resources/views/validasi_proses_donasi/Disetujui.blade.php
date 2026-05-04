@extends('layouts.app')

@section('title', 'Donasi Disetujui')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-8 text-gray-800">Validasi Proses Donasi</h1>

    <!-- TABS -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('validasi.index') }}" class="bg-white border border-gray-100 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50 transition">Menunggu Validasi</a>
        <a href="{{ route('validasi.disetujui') }}" class="bg-[#E5E7EB] text-gray-700 px-6 py-2 rounded-lg text-xs font-bold shadow-sm">Disetujui</a>
        <a href="{{ route('validasi.ditolak') }}" class="bg-white border border-gray-100 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50 transition">Ditolak</a>
    </div>

    <div class="space-y-4">
        @forelse($donations as $item)
        <div class="bg-white border border-gray-100 p-5 rounded-2xl flex flex-col md:flex-row gap-6 relative shadow-sm">
            
            <!-- THUMBNAIL -->
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
                    <span class="bg-[#9AE6B4] text-white text-[10px] font-bold px-10 py-1.5 rounded-full uppercase tracking-widest">DISETUJUI</span>
                </div>
                
                <div class="text-[10px] text-gray-400 mt-1 mb-3 flex gap-4">
                    <span>Donatur: <span class="text-gray-500 font-medium">{{ $item->kategori ?? 'Umum' }}</span></span>
                    <span>Dikirim: {{ optional($item->created_at)->format('d M, H:i') }}</span>
                </div>

                <!-- BARIS BADGES (Sesuai Figma) -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-[#FDF4E3] text-[#B08933] text-[9px] font-bold px-4 py-1.5 rounded-lg">
                        {{ $item->quantity }} Porsi
                    </span>
                    <span class="bg-[#A0AEC0] text-white text-[9px] font-bold px-4 py-1.5 rounded-lg">
                        Layak konsumsi
                    </span>
                    @if($item->expired_at)
                    <span class="bg-[#A0AEC0] text-white text-[9px] font-bold px-4 py-1.5 rounded-lg">
                        Expired: {{ \Carbon\Carbon::parse($item->expired_at)->format('d M H:i') }}
                    </span>
                    @endif
                </div>

                <!-- PROGRESS TRACKER (Sesuai Figma) -->
                <div class="flex items-center space-x-2 pt-4 border-t border-gray-50">
                    <div class="flex items-center space-x-1">
                        <span class="w-3 h-3 rounded-full bg-[#E47F3D]"></span>
                        <span class="text-[9px] font-bold text-[#E47F3D]">Diterima</span>
                    </div>
                    <span class="text-gray-300 text-[8px]">></span>
                    <div class="flex items-center space-x-1">
                        <span class="w-3 h-3 rounded-full bg-[#E47F3D]"></span>
                        <span class="text-[9px] font-bold text-[#E47F3D]">Divalidasi</span>
                    </div>
                    <span class="text-gray-300 text-[8px]">></span>
                    <div class="flex items-center space-x-1">
                        <span class="w-3 h-3 rounded-full border-2 border-[#E47F3D] bg-[#FDF4E3]"></span>
                        <span class="text-[9px] font-bold text-[#E47F3D]">Penugasan relawan</span>
                    </div>
                    <span class="text-gray-300 text-[8px]">></span>
                    <div class="flex items-center space-x-1">
                        <span class="w-3 h-3 rounded-full bg-[#CBD5E0]"></span>
                        <span class="text-[9px] font-bold text-[#CBD5E0]">Pengiriman</span>
                    </div>
                    <span class="text-gray-300 text-[8px]">></span>
                    <div class="flex items-center space-x-1">
                        <span class="w-3 h-3 rounded-full bg-[#CBD5E0]"></span>
                        <span class="text-[9px] font-bold text-[#CBD5E0]">Selesai</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <p class="text-gray-400 text-center py-10">Belum ada data disetujui.</p>
        @endforelse
    </div>
</div>
@endsection