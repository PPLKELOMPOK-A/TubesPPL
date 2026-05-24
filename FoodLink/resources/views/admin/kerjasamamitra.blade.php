@extends('layouts.app')

@section('title', 'Kerja Sama Mitra')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-10 text-gray-800">Kerja Sama Mitra</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-[#FFF9F0] p-10 rounded-[2rem] border border-[#FBEBCE] text-center">
            <div class="text-5xl font-bold text-gray-800 mb-2">{{ $totalMitra }}</div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Total Mitra</div>
        </div>
        <div class="bg-[#FFF9F0] p-10 rounded-[2rem] border border-[#FBEBCE] text-center">
            <div class="text-5xl font-bold text-gray-800 mb-2">{{ $mitraAktif }}</div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Aktif</div>
        </div>
        <div class="bg-[#FFF9F0] p-10 rounded-[2rem] border border-[#FBEBCE] text-center">
            <div class="text-5xl font-bold text-gray-800 mb-2">{{ $mitraPengajuan }}</div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Proses Pengajuan</div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('mitra.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="pl-12 pr-6 py-2.5 border border-gray-200 rounded-full text-sm w-64 focus:outline-none focus:ring-1 focus:ring-gray-300 shadow-sm bg-white">
            </form>

            <a href="{{ route('mitra.index', request()->except('kategori')) }}" class="px-6 py-2.5 rounded-full text-xs font-bold border transition {{ request('kategori') == null ? 'bg-[#333] text-white' : 'bg-white text-gray-600 border-gray-200' }}">Semua</a>
            @foreach(['Restoran', 'Toko', 'NGO', 'Kantin'] as $cat)
                <a href="{{ route('mitra.index', array_merge(request()->all(), ['kategori' => $cat])) }}" class="px-6 py-2.5 rounded-full text-xs font-bold border transition {{ request('kategori') == $cat ? 'bg-[#333] text-white' : 'bg-white text-gray-600 border-gray-200' }}">{{ $cat }}</a>
            @endforeach
            
            <button onclick="document.getElementById('modalTambahMitra').classList.remove('hidden')" class="px-6 py-2.5 border border-gray-200 rounded-full text-xs font-bold text-gray-800 bg-white hover:bg-gray-50 transition">+ Tambah Mitra</button>
        </div>
    </div>

    <div class="flex gap-4 mb-8">
        <a href="{{ route('mitra.index', request()->except('status')) }}" class="px-6 py-2 text-xs font-bold rounded-full transition {{ request('status') == null ? 'bg-[#D1D5DB] text-gray-800' : 'text-gray-500 hover:text-gray-800 underline' }}">Semua Mitra</a>
        <a href="{{ route('mitra.index', array_merge(request()->all(), ['status' => 'aktif'])) }}" class="px-6 py-2 text-xs font-bold rounded-full transition {{ request('status') == 'aktif' ? 'bg-[#D1D5DB] text-gray-800' : 'text-gray-500 hover:text-gray-800' }}">Aktif</a>
        <a href="{{ route('mitra.index', array_merge(request()->all(), ['status' => 'pengajuan'])) }}" class="px-6 py-2 text-xs font-bold rounded-full transition {{ request('status') == 'pengajuan' ? 'bg-[#D1D5DB] text-gray-800' : 'text-gray-500 hover:text-gray-800' }}">Pengajuan Baru</a>
        <a href="{{ route('mitra.index', array_merge(request()->all(), ['status' => 'tidak_aktif'])) }}" class="px-6 py-2 text-xs font-bold rounded-full transition {{ request('status') == 'tidak_aktif' ? 'bg-[#D1D5DB] text-gray-800' : 'text-gray-500 hover:text-gray-800' }}">Tidak Aktif</a>
    </div>

    <div class="space-y-6">
        @forelse($mitras as $item)
        <div class="bg-white border border-gray-100 p-8 rounded-[2.5rem] shadow-sm flex flex-col md:flex-row items-center gap-8">
            <div class="w-36 h-36 bg-[#FDF4E3] rounded-[1.5rem] flex items-center justify-center flex-shrink-0">
                <svg class="w-12 h-12 text-[#B08933]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-2xl font-black text-gray-800">{{ $item->nama_mitra }}</h2>
                    @if($item->status == 'aktif')
                        <span class="bg-[#C6F6D5] text-[#22543D] text-[10px] font-bold px-3 py-1 rounded-full uppercase">Aktif</span>
                    @elseif($item->status == 'pengajuan')
                        <span class="bg-[#FEEBC8] text-[#744210] text-[10px] font-bold px-3 py-1 rounded-full uppercase">Proses pengajuan</span>
                    @else
                        <span class="bg-[#E2E8F0] text-[#4A5568] text-[10px] font-bold px-3 py-1 rounded-full uppercase">Tidak Aktif</span>
                    @endif
                </div>
                <p class="text-sm text-gray-400 font-medium mb-5">{{ $item->kategori }} · {{ $item->lokasi }} · {{ $item->keterangan_waktu }}</p>
                
                <div class="flex flex-wrap gap-2">
                    <span class="bg-[#FDF4E3] text-[#B08933] text-[12px] font-bold px-6 py-2 rounded-full">{{ $item->kategori }}</span>
                    @if($item->status == 'pengajuan')
                        <span class="bg-[#FDF4E3] text-[#B08933] text-[12px] font-bold px-6 py-2 rounded-full">Menunggu verifikasi</span>
                    @else
                        <span class="bg-[#FDF4E3] text-[#B08933] text-[12px] font-bold px-6 py-2 rounded-full">{{ $item->total_donasi ?? 0 }} Donasi</span>
                        <span class="bg-[#FDF4E3] text-[#B08933] text-[12px] font-bold px-6 py-2 rounded-full">{{ $item->porsi_tersalur ?? 0 }} porsi tersalur</span>
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-3 w-full md:w-40">
                @if($item->status == 'aktif')
                    <button type="button" onclick="openProfileModal('{{ $item->nama_mitra }}', '{{ $item->kategori }}', '{{ $item->lokasi }}', '{{ $item->deskripsi ?? 'Keterangan belum tersedia.' }}', '{{ $item->status }}')" class="bg-[#333] text-white font-bold py-3 rounded-2xl text-xs w-full">Lihat Profile</button>
                    <a href="https://wa.me/6281584844763?text=Halo%20{{ urlencode($item->nama_mitra) }},%20kami%20dari%20Admin%20FoodLink%20ingin%20berdiskusi%20terkait%20kerjasama..." target="_blank" class="bg-[#333] text-white font-bold py-3 rounded-2xl text-xs w-full text-center block">Hubungi</a>
                
                @elseif($item->status == 'pengajuan')
                    <form action="{{ route('mitra.updateStatus', $item->id) }}" method="POST" class="w-full">
                        @csrf @method('PATCH') <input type="hidden" name="status" value="aktif">
                        <button class="bg-[#48BB78] text-white font-bold py-3 rounded-2xl text-xs w-full mb-3 shadow-sm">Setujui</button>
                    </form>
                    <form action="{{ route('mitra.updateStatus', $item->id) }}" method="POST" class="w-full">
                        @csrf @method('PATCH') <input type="hidden" name="status" value="ditolak">
                        <button class="bg-[#F56565] text-white font-bold py-3 rounded-2xl text-xs w-full shadow-sm">Tolak</button>
                    </form>
                @else
                    <button type="button" onclick="openProfileModal('{{ $item->nama_mitra }}', '{{ $item->kategori }}', '{{ $item->lokasi }}', '{{ $item->deskripsi ?? 'Keterangan belum tersedia.' }}', '{{ $item->status }}')" class="bg-[#333] text-white font-bold py-3 rounded-2xl text-xs w-full">Lihat Profile</button>
                    <form action="{{ route('mitra.updateStatus', $item->id) }}" method="POST" class="w-full">
                        @csrf @method('PATCH') <input type="hidden" name="status" value="aktif">
                        <button class="bg-[#333] text-white font-bold py-3 rounded-2xl text-xs w-full">Aktifkan</button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center text-gray-400 py-20 font-bold">Data tidak ditemukan.</p>
        @endforelse
    </div>

    <div class="flex justify-end items-center mt-12 gap-4">
        <span class="text-xs font-bold text-gray-500">1-{{ count($mitras) }} dari 200</span>
        <div class="flex bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <button class="p-2 border-r hover:bg-gray-50 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></button>
            <button class="px-4 py-2 border-r bg-[#333] text-white text-xs font-bold">1</button>
            <button class="px-4 py-2 border-r text-xs font-bold text-gray-600">2</button>
            <button class="px-4 py-2 border-r text-xs font-bold text-gray-600">9</button>
            <button class="p-2 hover:bg-gray-50 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
        </div>
    </div>
</div>

<div id="modalTambahMitra" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-8 bg-[#FFF9F0] border-b border-[#FBEBCE] flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800">Tambah Mitra Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambahMitra').classList.add('hidden')" class="text-gray-400 text-3xl">&times;</button>
        </div>
        <form action="{{ route('mitra.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Mitra</label>
                <input type="text" name="nama_mitra" required placeholder="Cth: Warung Mak Inah" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                <select name="kategori" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
                    <option value="Restoran">Restoran</option>
                    <option value="Toko">Toko</option>
                    <option value="NGO">NGO</option>
                    <option value="Kantin">Kantin</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi</label>
                <input type="text" name="lokasi" required placeholder="Depok" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-[#333] text-white font-bold py-4 rounded-2xl shadow-lg">Simpan & Ajukan</button>
        </form>
    </div>
</div>

<div id="modalProfile" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-8 bg-[#FFF9F0] border-b border-[#FBEBCE] flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800">Profil Mitra</h3>
            <button type="button" onclick="document.getElementById('modalProfile').classList.add('hidden')" class="text-gray-400 text-3xl">&times;</button>
        </div>
        <div class="p-8 space-y-4">
            <div class="flex items-center gap-4">
                 <div class="w-16 h-16 bg-[#FDF4E3] rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-[#B08933]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h4 id="profileName" class="text-lg font-black text-gray-800">Nama</h4>
                    <p id="profileLocation" class="text-sm text-gray-500 font-medium">Lokasi</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span id="profileCategory" class="bg-[#FDF4E3] text-[#B08933] text-xs font-bold px-4 py-1.5 rounded-full">Kategori</span>
                <span id="profileStatus" class="text-xs font-bold px-4 py-1.5 rounded-full uppercase">Status</span>
            </div>
            <div class="pt-4 border-t border-gray-100">
                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan Usaha</h5>
                <p id="profileDescription" class="text-sm text-gray-700 leading-relaxed">Deskripsi...</p>
            </div>
        </div>
        <div class="p-6 bg-gray-50 border-t border-gray-100">
             <button type="button" onclick="document.getElementById('modalProfile').classList.add('hidden')" class="px-6 py-4 bg-[#333] text-white rounded-2xl text-sm font-bold w-full">Tutup</button>
        </div>
    </div>
</div>

<script>
    function openProfileModal(name, category, location, desc, status) {
        document.getElementById('profileName').textContent = name;
        document.getElementById('profileCategory').textContent = category;
        document.getElementById('profileLocation').textContent = location;
        document.getElementById('profileDescription').textContent = desc;
        
        let statusBadge = document.getElementById('profileStatus');
        if(status === 'aktif') {
            statusBadge.textContent = 'AKTIF';
            statusBadge.className = 'bg-[#C6F6D5] text-[#22543D] text-xs font-bold px-4 py-1.5 rounded-full uppercase';
        } else if(status === 'pengajuan') {
            statusBadge.textContent = 'PENGAJUAN';
            statusBadge.className = 'bg-[#FEEBC8] text-[#744210] text-xs font-bold px-4 py-1.5 rounded-full uppercase';
        } else {
            statusBadge.textContent = 'TIDAK AKTIF';
            statusBadge.className = 'bg-[#E2E8F0] text-[#4A5568] text-xs font-bold px-4 py-1.5 rounded-full uppercase';
        }

        document.getElementById('modalProfile').classList.remove('hidden');
    }
</script>
@endsection