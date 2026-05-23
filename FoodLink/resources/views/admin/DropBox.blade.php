@extends('layouts.app')

@section('title', 'Drop Box')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Drop Box</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $totalLokasi }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Total Lokasi</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $tersedia }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Tersedia</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $hampirPenuh }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Hampir Penuh</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $penuh }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Penuh</div>
        </div>
    </div>

    <div class="bg-[#FDF4E3] rounded-2xl border border-[#FBEBCE] h-48 relative mb-8 overflow-hidden">
        <div class="absolute top-4 right-4 text-[9px] font-bold text-gray-500 uppercase tracking-widest">Peta Sebaran Drop Box</div>
        
        <div class="absolute top-10 left-[20%] flex flex-col items-center">
            <div class="w-3.5 h-3.5 bg-[#48BB78] rounded-full shadow-sm"></div>
            <span class="text-[9px] font-bold text-gray-600 mt-1 uppercase">Sudirman</span>
        </div>
        <div class="absolute top-20 left-[35%] flex flex-col items-center">
            <div class="w-3.5 h-3.5 bg-[#48BB78] rounded-full shadow-sm"></div>
            <span class="text-[9px] font-bold text-gray-600 mt-1 uppercase">Tebet</span>
        </div>
        <div class="absolute top-8 left-[45%] flex flex-col items-center">
            <div class="w-3.5 h-3.5 bg-[#48BB78] rounded-full shadow-sm"></div>
            <span class="text-[9px] font-bold text-gray-600 mt-1 uppercase">Menteng</span>
        </div>
        <div class="absolute bottom-8 left-[40%] flex flex-col items-center">
            <div class="w-3.5 h-3.5 bg-[#ED8936] rounded-full shadow-sm"></div>
            <span class="text-[9px] font-bold text-gray-600 mt-1 uppercase">Pancoran</span>
        </div>
        <div class="absolute top-16 left-[55%] flex flex-col items-center">
            <div class="w-3.5 h-3.5 bg-[#ED8936] rounded-full shadow-sm"></div>
            <span class="text-[9px] font-bold text-gray-600 mt-1 uppercase">Matraman</span>
        </div>
        <div class="absolute top-10 right-[25%] flex flex-col items-center">
            <div class="w-3.5 h-3.5 bg-[#F56565] rounded-full shadow-sm"></div>
            <span class="text-[9px] font-bold text-gray-600 mt-1 uppercase">Cempaka Putih</span>
        </div>

        <div class="absolute bottom-4 left-6 flex gap-4">
            <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 bg-[#48BB78] rounded-full"></div><span class="text-[9px] font-bold text-gray-600 uppercase">Tersedia</span></div>
            <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 bg-[#ED8936] rounded-full"></div><span class="text-[9px] font-bold text-gray-600 uppercase">Hampir Penuh</span></div>
            <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 bg-[#F56565] rounded-full"></div><span class="text-[9px] font-bold text-gray-600 uppercase">Penuh</span></div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6 gap-4">
        <form action="#" method="GET" class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" placeholder="Lokasi Drop Box..." class="w-full pl-12 pr-6 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-300 shadow-sm bg-white">
        </form>
        <button class="px-6 py-3 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 bg-white hover:bg-gray-50 shadow-sm transition">
            + Tambah Lokasi
        </button>
    </div>

    <div class="space-y-5">
        @forelse($dropboxes as $item)
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row items-center gap-6">
            
            <div class="w-28 h-28 bg-[#FFF9F0] rounded-xl flex items-center justify-center flex-shrink-0 border border-[#FBEBCE]">
                <svg class="w-10 h-10 text-[#4299E1]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <div class="flex-1 w-full">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-xl font-bold text-gray-800">{{ $item->nama }}</h2>
                    @if($item->status == 'tersedia')
                        <span class="bg-[#C6F6D5] text-[#2F855A] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Tersedia</span>
                    @elseif($item->status == 'hampir_penuh')
                        <span class="bg-[#FEEBC8] text-[#C05621] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Hampir Penuh</span>
                    @else
                        <span class="bg-[#FED7D7] text-[#C53030] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Penuh</span>
                    @endif
                </div>
                
                <p class="text-xs text-gray-600 mb-1">{{ $item->lokasi }} · Mitra: {{ $item->mitra }}</p>
                
                @if($item->status == 'tersedia')
                    <p class="text-sm font-bold text-[#48BB78] mb-3">{{ $item->kapasitas }}%</p>
                @elseif($item->status == 'hampir_penuh')
                    <p class="text-sm font-bold text-[#ED8936] mb-3">{{ $item->kapasitas }}%</p>
                @else
                    <p class="text-sm font-bold text-[#F56565] mb-3">{{ $item->kapasitas }}%</p>
                @endif
                
                <p class="text-[10px] text-gray-400 font-medium">Terakhir diperbarui: {{ $item->update }}</p>
            </div>

            <div class="flex flex-col gap-3 w-full md:w-40">
                <button class="bg-[#545454] text-white font-bold py-2.5 rounded-xl text-xs shadow-sm hover:bg-gray-700 transition">Detail</button>
                <button class="bg-[#545454] text-white font-bold py-2.5 rounded-xl text-xs shadow-sm hover:bg-gray-700 transition">Jadwalkan Jemput</button>
            </div>
        </div>
        @empty
        <div class="text-center py-10 bg-white rounded-2xl border border-gray-100">
            <p class="text-gray-400 font-bold">Belum ada lokasi Drop Box.</p>
        </div>
        @endforelse
    </div>

    <div class="flex justify-end items-center mt-10 gap-4">
        <span class="text-xs font-bold text-gray-500">1-3 dari 200</span>
        <div class="flex bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <button class="p-2 border-r hover:bg-gray-50 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></button>
            <button class="px-4 py-2 border-r bg-[#333] text-white text-xs font-bold">1</button>
            <button class="px-4 py-2 border-r text-xs font-bold text-gray-600">2</button>
            <button class="px-4 py-2 border-r text-xs font-bold text-gray-600">9</button>
            <button class="px-4 py-2 border-r text-xs font-bold text-gray-600">10</button>
            <button class="p-2 hover:bg-gray-50 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
        </div>
    </div>
</div>
@endsection