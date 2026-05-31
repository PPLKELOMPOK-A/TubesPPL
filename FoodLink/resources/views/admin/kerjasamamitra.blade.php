@extends('layouts.admin')

@section('title', 'Kerjasama Mitra - Foodlink')

@section('content')

<style>
    /* Container Utama */
    .mitra-canvas { padding: 40px 50px; background-color: #FFF9EE; min-height: 100vh; width: 100%; }
    .mitra-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 28px; color: #1A1A1A; margin-bottom: 30px; }

    /* Card Statistik */
    .stats-container { display: flex; gap: 20px; margin-bottom: 30px; }
    .stat-card { background-color: #FEF3D1; border-radius: 12px; padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: center; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.02); }
    .stat-number { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 32px; color: #32220D; margin-bottom: 8px; }
    .stat-label { font-size: 13px; font-weight: 700; color: #4E453D; text-transform: uppercase; letter-spacing: 0.8px; }

    /* Baris Filter 1: Search & Kategori */
    .filter-row-top { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
    
    .search-box { position: relative; display: flex; align-items: center; }
    .search-box i { position: absolute; left: 16px; color: #A0AEC0; font-size: 14px; }
    .search-input { padding: 10px 16px 10px 40px; border-radius: 8px; border: 1px solid #E2E8F0; width: 240px; font-size: 13px; outline: none; color: #4A5568; }
    
    .btn-filter { padding: 10px 24px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #1A202C; cursor: pointer; text-decoration: none; box-shadow: 0px 1px 2px rgba(0,0,0,0.02); transition: 0.2s; }
    .btn-filter.active { border-color: #A0AEC0; font-weight: 700; }
    
    .btn-add-mitra { margin-left: auto; padding: 10px 20px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 13px; font-weight: 700; color: #1A202C; cursor: pointer; box-shadow: 0px 1px 2px rgba(0,0,0,0.02); transition: 0.2s; }
    .btn-add-mitra:hover { background-color: #F7FAFC; }

    /* Baris Filter 2: Tab Status */
    .filter-row-bottom { display: flex; gap: 12px; margin-bottom: 30px; }
    .tab-status { padding: 10px 24px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #4A5568; cursor: pointer; text-decoration: none; box-shadow: 0px 1px 2px rgba(0,0,0,0.02); transition: 0.2s; }
    .tab-status.active { background: #D6D6D6; color: #1A202C; border-color: #CBD5E0; font-weight: 700; }

    /* List Mitra */
    .mitra-list-container { display: flex; flex-direction: column; gap: 20px; }
    .mitra-card { background-color: #FFFFFF; border-radius: 16px; padding: 24px; display: flex; gap: 24px; box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.04); border: 1px solid rgba(209, 196, 185, 0.2); align-items: center; }
    
    .mitra-img-wrapper { width: 120px; height: 120px; background-color: #FEF3D1; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #A0AEC0; flex-shrink: 0; }
    
    .mitra-content { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .mitra-header-row { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
    .mitra-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 18px; color: #1A202C; margin: 0; }
    
    /* Status Badge sebelah judul */
    .badge-status { padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .status-aktif { background-color: #9AE6B4; color: #2F855A; }
    .status-pengajuan { background-color: #FEF3D1; color: #B7791F; }
    .status-tidak_aktif { background-color: #FEF3D1; color: #ED8936; opacity: 0.6; }

    .mitra-meta { font-size: 12px; color: #718096; margin-bottom: 16px; }

    /* Pill Badges */
    .badges-row { display: flex; gap: 12px; flex-wrap: wrap; }
    .badge-item { padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 700; background-color: #FEF3D1; color: #744210; }

    /* Tombol Aksi Kanan */
    .action-buttons { display: flex; flex-direction: column; gap: 12px; }
    .btn-action { width: 140px; padding: 10px 0; border-radius: 8px; font-weight: 700; font-size: 13px; border: none; color: #FFFFFF; cursor: pointer; text-align: center; text-decoration: none; transition: 0.2s; display: inline-block; }
    .btn-dark { background-color: #555555; }
    .btn-dark:hover { background-color: #333333; }
    .btn-green { background-color: #68D391; }
    .btn-green:hover { background-color: #48BB78; }
    .btn-red { background-color: #FF6B6B; }
    .btn-red:hover { background-color: #FC8181; }

    /* Modal Styling */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 999; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    .modal-content { background: #fff; padding: 35px; border-radius: 16px; width: 450px; max-width: 90%; display: flex; flex-direction: column; gap: 15px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .modal-close-btn { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 20px; color: #A0AEC0; cursor: pointer; }
    
    .modal-content input, .modal-content select { padding: 12px; border: 1px solid #E2E8F0; border-radius: 8px; font-family: inherit; font-size: 14px; outline: none; }
    .modal-content label { font-size: 13px; font-weight: 700; color: #4A5568; margin-bottom: -8px; }
</style>

<div class="mitra-canvas">
    
    <h1 class="mitra-title">Kerja Sama Mitra</h1>

    <!-- 1. Statistik Card -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number">{{ $totalMitra ?? 24 }}</div>
            <div class="stat-label">TOTAL MITRA</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $mitraAktif ?? 18 }}</div>
            <div class="stat-label">AKTIF</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $mitraPengajuan ?? 1 }}</div>
            <div class="stat-label">PROSES PENGAJUAN</div>
        </div>
    </div>

    @php 
        $currentStatus = request('status'); 
        $currentKategori = request('kategori');
    @endphp

    <!-- 2. Filter Baris 1: Pencarian & Kategori -->
    <div class="filter-row-top">
        <form action="{{ route('mitra.index') }}" method="GET" class="search-box">
            @if($currentStatus) <input type="hidden" name="status" value="{{ $currentStatus }}"> @endif
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}">
        </form>

        <a href="{{ route('mitra.index', ['status' => $currentStatus]) }}" class="btn-filter {{ !$currentKategori ? 'active' : '' }}">Semua</a>
        <a href="{{ route('mitra.index', ['kategori' => 'Restoran', 'status' => $currentStatus]) }}" class="btn-filter {{ $currentKategori == 'Restoran' ? 'active' : '' }}">Restoran</a>
        <a href="{{ route('mitra.index', ['kategori' => 'Toko', 'status' => $currentStatus]) }}" class="btn-filter {{ $currentKategori == 'Toko' ? 'active' : '' }}">Toko</a>
        <a href="{{ route('mitra.index', ['kategori' => 'NGO', 'status' => $currentStatus]) }}" class="btn-filter {{ $currentKategori == 'NGO' ? 'active' : '' }}">NGO</a>
        
        <!-- UPDATE: Tambahan Tombol Kantin -->
        <a href="{{ route('mitra.index', ['kategori' => 'Kantin', 'status' => $currentStatus]) }}" class="btn-filter {{ $currentKategori == 'Kantin' ? 'active' : '' }}">Kantin</a>

        <button onclick="document.getElementById('modalTambah').style.display='flex'" class="btn-add-mitra">
            + Tambah Mitra
        </button>
    </div>

    <!-- 3. Filter Baris 2: Tab Status -->
    <div class="filter-row-bottom">
        <a href="{{ route('mitra.index', ['kategori' => $currentKategori]) }}" class="tab-status {{ !$currentStatus ? 'active' : '' }}">
            Semua Mitra
        </a>
        <a href="{{ route('mitra.index', ['status' => 'aktif', 'kategori' => $currentKategori]) }}" class="tab-status {{ $currentStatus == 'aktif' ? 'active' : '' }}">
            Aktif
        </a>
        <a href="{{ route('mitra.index', ['status' => 'pengajuan', 'kategori' => $currentKategori]) }}" class="tab-status {{ $currentStatus == 'pengajuan' ? 'active' : '' }}">
            Pengajuan Baru
        </a>
        <a href="{{ route('mitra.index', ['status' => 'tidak_aktif', 'kategori' => $currentKategori]) }}" class="tab-status {{ $currentStatus == 'tidak_aktif' ? 'active' : '' }}">
            Tidak Aktif
        </a>
    </div>

    <!-- 4. List Card Mitra -->
    <div class="mitra-list-container">
        
        @forelse($mitras as $mitra)
            <div class="mitra-card">
                
                <div class="mitra-img-wrapper">
                    @if($mitra->kategori == 'Restoran' || $mitra->kategori == 'Toko')
                        <i class="fa-regular fa-paper-plane" style="transform: rotate(45deg);"></i>
                    @elseif($mitra->kategori == 'NGO')
                        <i class="fa-solid fa-user-group" style="font-size: 24px; color: #8FA2B4;"></i>
                    @else
                        <i class="fa-solid fa-house" style="font-size: 24px; color: #A0AEC0;"></i>
                    @endif
                </div>

                <div class="mitra-content">
                    <div class="mitra-header-row">
                        <h3 class="mitra-name">{{ $mitra->nama_mitra }}</h3>
                        
                        @if($mitra->status == 'aktif')
                            <div class="badge-status status-aktif">Aktif</div>
                        @elseif($mitra->status == 'pengajuan')
                            <div class="badge-status status-pengajuan">Proses pengajuan</div>
                        @else
                            <div class="badge-status status-tidak_aktif">Tidak Aktif</div>
                        @endif
                    </div>

                    <div class="mitra-meta">
                        {{ $mitra->kategori }} &nbsp;&middot;&nbsp; {{ $mitra->lokasi }} &nbsp;&middot;&nbsp; {{ $mitra->keterangan_waktu }}
                    </div>

                    <div class="badges-row">
                        <span class="badge-item">{{ $mitra->kategori }}</span>
                        
                        @if($mitra->status == 'pengajuan')
                            <span class="badge-item">Menunggu verifikasi</span>
                        @else
                            <span class="badge-item">{{ $mitra->total_donasi }} Donasi</span>
                            <span class="badge-item">{{ $mitra->porsi_tersalur }} porsi tersalur</span>
                        @endif
                    </div>
                </div>

                <!-- Tombol Aksi Kanan -->
                <div class="action-buttons">
                    @if($mitra->status == 'aktif')
                        <!-- UPDATE: Lihat Profile memicu Modal & Hubungi mengarah ke WA -->
                        <button type="button" class="btn-action btn-dark" 
                            onclick="bukaProfile('{{ $mitra->nama_mitra }}', '{{ $mitra->kategori }}', '{{ $mitra->lokasi }}', '{{ $mitra->deskripsi }}')">
                            Lihat Profile
                        </button>
                        <a href="https://wa.me/6281584844763" target="_blank" class="btn-action btn-dark">Hubungi</a>
                        
                    @elseif($mitra->status == 'pengajuan')
                        <form action="{{ route('mitra.updateStatus', $mitra->id) }}" method="POST">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="aktif">
                            <button type="submit" class="btn-action btn-green">Setujui</button>
                        </form>
                        <form action="{{ route('mitra.updateStatus', $mitra->id) }}" method="POST">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="ditolak">
                            <button type="submit" class="btn-action btn-red">Tolak</button>
                        </form>

                    @elseif($mitra->status == 'tidak_aktif')
                        <!-- UPDATE: Lihat Profile memicu Modal -->
                        <button type="button" class="btn-action btn-dark" 
                            onclick="bukaProfile('{{ $mitra->nama_mitra }}', '{{ $mitra->kategori }}', '{{ $mitra->lokasi }}', '{{ $mitra->deskripsi }}')">
                            Lihat Profile
                        </button>
                        <form action="{{ route('mitra.updateStatus', $mitra->id) }}" method="POST">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="aktif">
                            <button type="submit" class="btn-action btn-dark">Aktifkan</button>
                        </form>
                    @endif
                </div>

            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #eee;">
                Tidak ada data mitra untuk filter ini.
            </div>
        @endforelse

    </div>

    <!-- Pagination Bawah -->
    <div class="pagination-container" style="display: flex; justify-content: flex-end; margin-top: 40px; font-size: 13px; color: #666;">
        <span>1-5 dari 200</span>
        <div style="margin-left: 15px; display: flex; gap: 5px;">
            <span style="padding: 5px 10px; border: 1px solid #ddd; background: #fff;">&lt;</span>
            <span style="padding: 5px 10px; border: 1px solid #ddd; background: #4A3721; color: white;">1</span>
            <span style="padding: 5px 10px; border: 1px solid #ddd; background: #fff;">2</span>
            <span style="padding: 5px 10px; background: transparent;">...</span>
            <span style="padding: 5px 10px; border: 1px solid #ddd; background: #fff;">9</span>
            <span style="padding: 5px 10px; border: 1px solid #ddd; background: #fff;">10</span>
            <span style="padding: 5px 10px; border: 1px solid #ddd; background: #fff;">&gt;</span>
        </div>
    </div>

</div>


<!-- ============================================== -->
<!-- MODAL: LIHAT PROFILE MITRA -->
<!-- ============================================== -->
<div id="modalProfile" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="document.getElementById('modalProfile').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
        
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 5px;">
            <div style="width: 50px; height: 50px; background: #FEF3D1; border-radius: 8px; display: flex; align-items:center; justify-content:center; color: #744210; font-size: 20px;">
                <i class="fa-solid fa-building"></i>
            </div>
            <div>
                <h3 id="profileNama" style="font-family: Montserrat; font-weight: 700; color: #32220D; margin: 0;">Nama Mitra</h3>
                <span id="profileKategoriLokasi" style="font-size: 13px; color: #718096;">Kategori - Lokasi</span>
            </div>
        </div>
        
        <hr style="border: none; border-top: 1px solid #E2E8F0; margin: 5px 0;">
        
        <h4 style="font-size: 14px; font-weight: 700; color: #4A5568; margin-bottom: -5px;">Tentang Mitra</h4>
        <p id="profileDeskripsi" style="font-size: 13px; color: #4A5568; line-height: 1.6;">
            Deskripsi lengkap mitra akan muncul di sini.
        </p>
    </div>
</div>


<!-- ============================================== -->
<!-- MODAL: TAMBAH MITRA BARU -->
<!-- ============================================== -->
<div id="modalTambah" class="modal-overlay">
    <form action="{{ route('mitra.store') }}" method="POST" class="modal-content">
        @csrf
        <h3 style="font-family: Montserrat; font-weight: 700; color: #32220D; margin-bottom: 5px;">Tambah Mitra Baru</h3>
        
        <label>Nama Mitra</label>
        <input type="text" name="nama_mitra" required placeholder="Contoh: PT. Sumber Makmur">
        
        <label>Kategori</label>
        <select name="kategori" required>
            <option value="Restoran">Restoran</option>
            <option value="Toko">Toko</option>
            <option value="NGO">NGO</option>
            <option value="Kantin">Kantin</option>
        </select>

        <label>Lokasi / Wilayah</label>
        <input type="text" name="lokasi" required placeholder="Contoh: Jakarta Pusat">

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 15px;">
            <button type="button" onclick="document.getElementById('modalTambah').style.display='none'" style="padding: 12px 20px; background: #F7FAFC; border: 1px solid #E2E8F0; border-radius: 8px; font-weight: 600; cursor:pointer;">Batal</button>
            <button type="submit" style="padding: 12px 20px; background: #68D391; color: white; border: none; border-radius: 8px; font-weight: 700; cursor:pointer;">Simpan Data</button>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- SCRIPT UNTUK MODAL -->
<!-- ============================================== -->
<script>
    // Fungsi untuk membuka pop-up profile dan mengisi datanya
    function bukaProfile(nama, kategori, lokasi, deskripsi) {
        document.getElementById('profileNama').innerText = nama;
        document.getElementById('profileKategoriLokasi').innerText = kategori + ' • ' + lokasi;
        document.getElementById('profileDeskripsi').innerText = deskripsi;
        
        // Tampilkan Modal
        document.getElementById('modalProfile').style.display = 'flex';
    }
</script>

@endsection