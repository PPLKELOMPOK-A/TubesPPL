@extends('layouts.app')

@section('title', 'Kerja Sama Mitra')

@section('content')
<div class="max-w-7xl mx-auto px-2 md:px-6 relative">
    <h1 class="text-2xl font-bold mb-8 text-gray-800">Kerja Sama Mitra</h1>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-[#FFF9F0] p-8 rounded-2xl border border-[#FBEBCE] shadow-sm">
            <div class="text-4xl font-bold text-gray-800 mb-2">{{ $totalMitra ?? 0 }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Total Mitra</div>
        </div>
        <div class="bg-[#FFF9F0] p-8 rounded-2xl border border-[#FBEBCE] shadow-sm">
            <div class="text-4xl font-bold text-gray-800 mb-2">{{ $mitraAktif ?? 0 }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Aktif</div>
        </div>
        <div class="bg-[#FFF9F0] p-8 rounded-2xl border border-[#FBEBCE] shadow-sm">
            <div class="text-4xl font-bold text-gray-800 mb-2">{{ $mitraPengajuan ?? 0 }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Proses Pengajuan</div>
        </div>
    </div>

    <!-- FILTER ROW 1 (Search & Kategori) -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <div class="flex flex-wrap items-center gap-3">
            
            <!-- SEARCH FORM DINAMIS -->
            <form action="{{ route('mitra.index') }}" method="GET" class="relative">
                <!-- Simpan parameter status & kategori saat ini agar tidak hilang saat men-search -->
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif

                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search (Enter)" class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-300 w-56 shadow-sm">
            </form>

            <!-- TOMBOL KATEGORI DINAMIS -->
            <a href="{{ route('mitra.index', request()->except('kategori')) }}" class="px-6 py-2 border rounded-xl text-xs font-bold shadow-sm transition {{ request('kategori') == null ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-800' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Semua</a>
            
            <a href="{{ route('mitra.index', array_merge(request()->all(), ['kategori' => 'Restoran'])) }}" class="px-6 py-2 border rounded-xl text-xs font-bold shadow-sm transition {{ request('kategori') == 'Restoran' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-800' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Restoran</a>
            
            <a href="{{ route('mitra.index', array_merge(request()->all(), ['kategori' => 'Toko'])) }}" class="px-6 py-2 border rounded-xl text-xs font-bold shadow-sm transition {{ request('kategori') == 'Toko' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-800' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Toko</a>
            
            <a href="{{ route('mitra.index', array_merge(request()->all(), ['kategori' => 'NGO'])) }}" class="px-6 py-2 border rounded-xl text-xs font-bold shadow-sm transition {{ request('kategori') == 'NGO' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-800' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">NGO</a>

            <a href="{{ route('mitra.index', array_merge(request()->all(), ['kategori' => 'Kantin'])) }}" class="px-6 py-2 border rounded-xl text-xs font-bold shadow-sm transition {{ request('kategori') == 'Kantin' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-800' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Kantin</a>
        </div>
        
        <!-- TOMBOL TAMBAH MITRA (Memicu Modal) -->
        <button onclick="document.getElementById('modalTambahMitra').classList.remove('hidden')" class="px-6 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 shadow-sm bg-white flex items-center hover:bg-gray-50 transition">
            + Tambah Mitra
        </button>
    </div>

    <!-- FILTER ROW 2 (Status Tabs Dinamis) -->
    <div class="flex flex-wrap items-center gap-3 mb-10">
        <a href="{{ route('mitra.index', request()->except('status')) }}" class="px-6 py-2 rounded-xl text-xs font-bold shadow-sm transition {{ request('status') == null ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-700' : 'bg-white border border-gray-200 text-gray-500 hover:bg-gray-50' }}">Semua Mitra</a>
        
        <a href="{{ route('mitra.index', array_merge(request()->all(), ['status' => 'aktif'])) }}" class="px-6 py-2 rounded-xl text-xs font-bold shadow-sm transition {{ request('status') == 'aktif' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-700' : 'bg-white border border-gray-200 text-gray-500 hover:bg-gray-50' }}">Aktif</a>
        
        <a href="{{ route('mitra.index', array_merge(request()->all(), ['status' => 'pengajuan'])) }}" class="px-6 py-2 rounded-xl text-xs font-bold shadow-sm transition {{ request('status') == 'pengajuan' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-700' : 'bg-white border border-gray-200 text-gray-500 hover:bg-gray-50' }}">Pengajuan Baru</a>
        
        <a href="{{ route('mitra.index', array_merge(request()->all(), ['status' => 'tidak_aktif'])) }}" class="px-6 py-2 rounded-xl text-xs font-bold shadow-sm transition {{ request('status') == 'tidak_aktif' ? 'bg-[#D1D5DB] border-[#D1D5DB] text-gray-700' : 'bg-white border border-gray-200 text-gray-500 hover:bg-gray-50' }}">Tidak Aktif</a>
    </div>

    <!-- LIST MITRA DINAMIS -->
    <div class="space-y-6 max-w-5xl">
        
        @forelse($mitras ?? [] as $item)
        <div class="bg-white border-2 border-gray-100 p-6 rounded-[2rem] shadow-xl flex flex-col md:flex-row items-center gap-8">
            <div class="w-32 h-32 bg-[#FDF4E3] rounded-2xl flex-shrink-0 flex items-center justify-center border border-[#FBEBCE] overflow-hidden">
                @if($item->logo)
                    <img src="{{ asset('storage/'.$item->logo) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-8 h-8 text-[#D9A74A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                @endif
            </div>
            
            <div class="flex-1 w-full">
                <div class="flex items-center gap-4 mb-2">
                    <h2 class="text-xl font-black text-gray-800">{{ $item->nama_mitra ?? 'Nama Mitra' }}</h2>
                    
                    @if($item->status == 'aktif')
                        <span class="bg-[#9AE6B4] text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Aktif</span>
                    @elseif($item->status == 'pengajuan')
                        <span class="bg-[#FDF4E3] text-[#B08933] text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Proses pengajuan</span>
                    @elseif($item->status == 'tidak_aktif')
                        <span class="bg-[#FFF4E0] text-[#F6AD55] text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Tidak Aktif</span>
                    @endif
                </div>
                
                <p class="text-xs text-gray-500 mb-5 font-medium">{{ $item->kategori ?? 'Kategori' }} · {{ $item->lokasi ?? 'Lokasi' }} · {{ $item->keterangan_waktu ?? 'Tanggal' }}</p>
                
                <div class="flex flex-wrap gap-3">
                    <span class="bg-[#FDF4E3] text-[#B08933] text-xs font-bold px-5 py-1.5 rounded-xl">{{ $item->kategori ?? 'Kategori' }}</span>
                    @if($item->status == 'pengajuan')
                        <span class="bg-[#FDF4E3] text-[#B08933] text-xs font-bold px-5 py-1.5 rounded-xl">Menunggu verifikasi</span>
                    @else
                        <span class="bg-[#FDF4E3] text-[#B08933] text-xs font-bold px-5 py-1.5 rounded-xl">{{ $item->total_donasi ?? 0 }} donasi</span>
                        <span class="bg-[#FDF4E3] text-[#B08933] text-xs font-bold px-5 py-1.5 rounded-xl">{{ $item->porsi_tersalur ?? 0 }} porsi tersalur</span>
                    @endif
                </div>
            </div>

            <!-- LOGIKA TOMBOL -->
            <div class="flex flex-col gap-3 w-full md:w-36">
                @if($item->status == 'aktif')
                    <a href="{{ route('mitra.show', $item->id ?? 1) }}" class="text-center bg-[#4A5568] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm hover:bg-gray-600 transition w-full block cursor-pointer">Lihat Profile</a>
                    <a href="https://wa.me/6281584844763?text=Halo%20{{ urlencode($item->nama_mitra ?? 'Mitra') }},%20kami%20dari%20Admin%20FoodLink%20ingin%20berdiskusi%20mengenai..." target="_blank" class="text-center bg-[#4A5568] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm hover:bg-gray-600 transition w-full block cursor-pointer">Hubungi</a>
                
                @elseif($item->status == 'pengajuan')
                    <form action="{{ route('mitra.updateStatus', $item->id ?? 1) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="aktif">
                        <button type="submit" class="bg-[#68D391] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm hover:bg-green-500 transition w-full">Setujui</button>
                    </form>
                    <form action="{{ route('mitra.updateStatus', $item->id ?? 1) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="bg-[#FC8181] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm hover:bg-red-500 transition w-full">Tolak</button>
                    </form>

                @elseif($item->status == 'tidak_aktif')
                    <a href="{{ route('mitra.show', $item->id ?? 1) }}" class="text-center bg-[#4A5568] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm hover:bg-gray-600 transition w-full block cursor-pointer">Lihat Profile</a>
                    <form action="{{ route('mitra.updateStatus', $item->id ?? 1) }}" method="POST" class="w-full mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="aktif">
                        <button type="submit" class="bg-[#4A5568] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm hover:bg-gray-600 transition w-full">Aktifkan</button>
                    </form>
                @endif
            </div>
        </div>
        @empty
            <div class="text-center py-10 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-400 font-bold">Data mitra yang kamu cari tidak ditemukan.</p>
            </div>
        @endforelse
        
    </div>

    <!-- PAGINATION -->
    <div class="flex justify-end items-center mt-12 text-xs font-bold text-gray-500 max-w-5xl">
        <span class="mr-4">1-5 dari 200</span>
        <div class="flex border rounded overflow-hidden">
            <button class="px-3 py-2 border-r hover:bg-gray-100">&lt;</button>
            <button class="px-3 py-2 border-r bg-gray-800 text-white">1</button>
            <button class="px-3 py-2 border-r hover:bg-gray-100">2</button>
            <button class="px-3 py-2 border-r hover:bg-gray-100">..</button>
            <button class="px-3 py-2 border-r hover:bg-gray-100">9</button>
            <button class="px-3 py-2 border-r hover:bg-gray-100">10</button>
            <button class="px-3 py-2 hover:bg-gray-100">&gt;</button>
        </div>
    </div>
</div>

<!-- MODAL POP-UP TAMBAH MITRA -->
<div id="modalTambahMitra" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-[2rem] w-full max-w-md overflow-hidden shadow-2xl border-2 border-[#FBEBCE]">
        <div class="px-8 py-5 bg-[#FFF9F0] border-b border-[#FBEBCE] flex justify-between items-center">
            <h3 class="font-black text-gray-800 text-lg">Tambah Mitra Baru</h3>
            <button onclick="document.getElementById('modalTambahMitra').classList.add('hidden')" class="text-gray-400 hover:text-red-500 text-2xl font-bold transition">&times;</button>
        </div>
        <form action="{{ route('mitra.store') }}" method="POST" class="p-8">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Mitra</label>
                <input type="text" name="nama_mitra" required placeholder="Cth: Warung Makan Bu Ani" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A74A] focus:border-transparent transition bg-gray-50">
            </div>
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kategori</label>
                <select name="kategori" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A74A] transition bg-gray-50">
                    <option value="Restoran">Restoran</option>
                    <option value="Toko">Toko</option>
                    <option value="NGO">NGO</option>
                    <option value="Kantin">Kantin</option>
                </select>
            </div>
            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Lokasi (Kota/Daerah)</label>
                <input type="text" name="lokasi" required placeholder="Cth: Depok" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A74A] focus:border-transparent transition bg-gray-50">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTambahMitra').classList.add('hidden')" class="px-6 py-3 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition">Batal</button>
                <button type="submit" class="px-6 py-3 bg-[#4A5568] text-white rounded-xl text-sm font-bold hover:bg-gray-700 transition shadow-md">Simpan & Ajukan</button>
            </div>
        </form>
    </div>
</div>

@endsection
