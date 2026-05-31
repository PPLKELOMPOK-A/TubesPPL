@extends('layouts.app')

@section('title', 'Donasi Ditolak')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-8 text-gray-800">Validasi Proses Donasi</h1>

    <div class="flex space-x-2 mb-6">
        <a href="{{ route('admin.validasi.index') }}" class="bg-white border border-gray-100 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50 transition">Menunggu Validasi</a>
        <a href="{{ route('admin.validasi.disetujui') }}" class="bg-white border border-gray-100 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50 transition">Disetujui</a>
        <a href="{{ route('admin.validasi.ditolak') }}" class="bg-[#E5E7EB] text-gray-700 px-6 py-2 rounded-lg text-xs font-bold shadow-sm">Ditolak</a>
    </div>

    <div class="space-y-4">
        @forelse($donations as $item)
        <div class="bg-white border border-gray-100 p-5 rounded-2xl flex flex-col md:flex-row gap-6 relative shadow-sm">
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
                    <span class="bg-[#FEB2B2] text-white text-[9px] font-bold px-4 py-1 rounded-full uppercase tracking-widest">Ditolak</span>
                </div>
                
                <div class="text-[10px] text-gray-400 mt-1 flex gap-4">
                    <span>Donatur: <span class="text-gray-500">{{ $item->kategori ?? 'Umum' }}</span></span>
                    <span>Dikirim: {{ optional($item->created_at)->format('d M, H:i') }}</span>
                </div>

                <div class="flex flex-wrap gap-2 mt-3 mb-4">
                    <span class="bg-[#FFF4E0] text-[#D9A74A] text-[9px] font-bold px-3 py-1 rounded-md">{{ $item->quantity }} Porsi</span>
                    <span class="bg-[#A0AEC0] text-white text-[9px] font-bold px-3 py-1 rounded-md">Layak konsumsi</span>
                    @if($item->expired_at)
                    <span class="bg-[#A0AEC0] text-white text-[9px] font-bold px-3 py-1 rounded-md">Expired: {{ \Carbon\Carbon::parse($item->expired_at)->format('d M H:i') }}</span>
                    @endif
                </div>
                
                <div class="bg-[#FFF5F5] border border-[#FED7D7] p-4 rounded-xl flex items-start space-x-2">
                    <svg class="w-3.5 h-3.5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-[10px] font-bold text-red-800 uppercase tracking-tight">Keterangan Penolakan:</h4>
                        <p class="text-[10px] text-red-500 mt-1 leading-relaxed">
                            {{ $item->keterangan_tolak ?? 'Donasi tidak memenuhi standar kelayakan konsumsi atau sudah melewati batas waktu kadaluarsa saat divalidasi oleh admin.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="text-center py-10 text-gray-400 italic">Tidak ada donasi ditolak.</div>
        @endforelse
    </div>
</div>
@endsection