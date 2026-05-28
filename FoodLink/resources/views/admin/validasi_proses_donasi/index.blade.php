@extends('layouts.app')

@section('title', 'Validasi Proses Donasi - Foodlink')

@section('content')

<style>
    /* Container Utama Halaman Validasi */
    .validasi-canvas { padding: 40px 50px; background-color: #FFF9EE; min-height: 100vh; width: 100%; }
    .validasi-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 28px; color: #1A1A1A; margin-bottom: 30px; }

    /* Grid Top Statistik */
    .stats-container { display: flex; gap: 20px; margin-bottom: 35px; }
    .stat-card { background-color: #FEF3D1; border-radius: 12px; padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: center; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.02); }
    .stat-number { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 32px; color: #32220D; margin-bottom: 12px; }
    .stat-label { font-size: 13px; font-weight: 700; color: #4E453D; text-transform: uppercase; letter-spacing: 0.8px; }

    /* Navigasi Tabs Status */
    .tabs-container { display: flex; gap: 16px; margin-bottom: 28px; }
    .tab-btn { padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; color: #4E453D; background-color: #FFFFFF; border: 1px solid rgba(209, 196, 185, 0.3); box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.02); transition: all 0.2s ease; }
    .tab-btn.active { background-color: #EAE3D2; color: #32220D; border-color: #D1C4B9; font-weight: 700; }

    /* List Wrapper Donasi */
    .donasi-list-container { display: flex; flex-direction: column; gap: 24px; }
    .donasi-card { background-color: #FFFFFF; border-radius: 16px; padding: 24px; display: flex; gap: 24px; box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid rgba(209, 196, 185, 0.15); }
    .donasi-img-wrapper { width: 140px; height: 140px; background-color: #FEF3D1; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .donasi-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .donasi-img-placeholder { font-size: 32px; }

    /* Isi Konten Card */
    .donasi-content { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .donasi-header-row { display: flex; justify-content: space-between; align-items: flex-start; }
    .donasi-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 20px; color: #32220D; margin-bottom: 6px; }
    .donasi-meta { font-size: 14px; color: #80756C; margin-bottom: 14px; }

    /* Badges Baris Keterangan */
    .badges-row { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .badge-item { padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; }
    .badge-porsi { background-color: #FEF3D1; color: #4A3721; }
    .badge-layak { background-color: #A0AEC0; color: #FFFFFF; }
    .badge-expired { background-color: #A0AEC0; color: #FFFFFF; }

    /* Status Badge Kanan Atas */
    .status-label { padding: 6px 20px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
    .status-menunggu { background-color: #FEF3D1; color: #4A3721; }
    .status-disetujui { background-color: #68D391; color: #FFFFFF; }
    .status-ditolak { background-color: #FC8181; color: #FFFFFF; }

    /* Tombol Validasi */
    .action-buttons { display: flex; gap: 16px; }
    .btn-action { padding: 12px 36px; border-radius: 8px; font-weight: 700; font-size: 14px; border: none; color: #FFFFFF; cursor: pointer; transition: all 0.2s ease; }
    .btn-setujui { background-color: #48BB78; }
    .btn-setujui:hover { background-color: #38A169; transform: translateY(-1px); }
    .btn-tolak { background-color: #F56565; }
    .btn-tolak:hover { background-color: #E53E3E; transform: translateY(-1px); }

    /* Progress Timeline (Status: Disetujui) */
    .progress-timeline { display: flex; align-items: center; gap: 12px; margin-top: 12px; font-size: 13px; font-weight: 600; }
    .pt-item { display: flex; align-items: center; gap: 8px; }
    .pt-dot { width: 14px; height: 14px; border-radius: 50%; background-color: #CBD5E0; }
    .pt-dot.active { background-color: #ED8936; }
    .pt-text { color: #A0AEC0; }
    .pt-text.active { color: #ED8936; font-weight: 700; }
    .pt-arrow { color: #CBD5E0; font-size: 11px; }

    /* Box Keterangan Alasan (Status: Ditolak) */
    .reject-reason-box { margin-top: 16px; background-color: #FFF5F5; border: 1px solid #FED7D7; border-radius: 8px; padding: 14px 20px; }
    .reject-title { font-weight: 700; color: #C53030; font-size: 14px; margin-bottom: 4px; }
    .reject-desc { font-weight: 500; color: #C53030; font-size: 14px; line-height: 1.5; }

    /* Pagination Footer */
    .pagination-container { display: flex; justify-content: flex-end; align-items: center; margin-top: 40px; gap: 20px; font-size: 14px; color: #80756C; }
</style>

<div class="validasi-canvas">
    
    <h1 class="validasi-title">Validasi Proses Donasi</h1>

    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['hari_ini'] ?? 12 }}</div>
            <div class="stat-label">MASUK HARI INI</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['menunggu'] ?? 5 }}</div>
            <div class="stat-label">PERLU VALIDASI</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['diproses'] ?? 7 }}</div>
            <div class="stat-label">SUDAH DI PROSES</div>
        </div>
    </div>

    <div class="tabs-container">
        <a href="{{ route('admin.validasi.index') }}" class="tab-btn {{ request()->routeIs('admin.validasi.index') ? 'active' : '' }}">
            Menunggu Validasi
        </a>
        <a href="{{ route('admin.validasi.disetujui') }}" class="tab-btn {{ request()->routeIs('admin.validasi.disetujui') ? 'active' : '' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.validasi.ditolak') }}" class="tab-btn {{ request()->routeIs('admin.validasi.ditolak') ? 'active' : '' }}">
            Ditolak
        </a>
    </div>

    <div class="donasi-list-container">
        
        @forelse($donations as $donasi)
            <div class="donasi-card">
                
                <div class="donasi-img-wrapper">
                    @if($donasi->foto_makanan)
                        <img src="{{ asset('storage/' . $donasi->foto_makanan) }}" alt="Foto">
                    @else
                        <span class="donasi-img-placeholder">🍲</span>
                    @endif
                </div>

                <div class="donasi-content">
                    
                    <div>
                        <div class="donasi-header-row">
                            <h3 class="donasi-name">{{ $donasi->judul ?? 'Nama Makanan' }}</h3>
                            
                            @if($donasi->status == 'menunggu')
                                <div class="status-label status-menunggu">MENUNGGU</div>
                            @elseif($donasi->status == 'disetujui')
                                <div class="status-label status-disetujui">DISETUJUI</div>
                            @else
                                <div class="status-label status-ditolak">DITOLAK</div>
                            @endif
                        </div>

                        <div class="donasi-meta">
                           Donatur: {{ $donasi->kategori ?? 'Nama Donatur' }} &nbsp;&nbsp;&nbsp; Dikirim: {{ $donasi->created_at ? $donasi->created_at->format('d M, H:i') : '26 Mar, 10:15' }}
                        </div>

                        <div class="badges-row">
                            <span class="badge-item badge-porsi">{{ $donasi->quantity ?? 50 }} Porsi</span>
                            <span class="badge-item badge-layak">Layak konsumsi</span>
                            <span class="badge-item badge-expired">Expired: {{ $donasi->expired_at ? date('d M H:i', strtotime($donasi->expired_at)) : '26 Mar 18:00' }}</span>
                        </div>
                    </div>

                    <div>
                        @if($donasi->status == 'menunggu')
                            <div class="action-buttons">
                                <form action="{{ route('admin.validasi.setujui', $donasi->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-setujui">Setujui</button>
                                </form>
                                <form action="{{ route('admin.validasi.tolak', $donasi->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-tolak">Tolak</button>
                                </form>
                            </div>
                            
                        @elseif($donasi->status == 'disetujui')
                            <div class="progress-timeline">
                                <div class="pt-item"><div class="pt-dot active"></div><span class="pt-text active">Diterima</span></div>
                                <i class="fa-solid fa-chevron-right pt-arrow"></i>
                                <div class="pt-item"><div class="pt-dot active"></div><span class="pt-text active">Divalidasi</span></div>
                                <i class="fa-solid fa-chevron-right pt-arrow"></i>
                                <div class="pt-item"><div class="pt-dot active"></div><span class="pt-text active">Penugasan relawan</span></div>
                                <i class="fa-solid fa-chevron-right pt-arrow"></i>
                                <div class="pt-item"><div class="pt-dot"></div><span class="pt-text">Pengiriman</span></div>
                                <i class="fa-solid fa-chevron-right pt-arrow"></i>
                                <div class="pt-item"><div class="pt-dot"></div><span class="pt-text">Selesai</span></div>
                            </div>
                            
                        @elseif($donasi->status == 'ditolak')
                            <div class="reject-reason-box">
                                <div class="reject-title">Keterangan Penolakan:</div>
                                <div class="reject-desc">
                                    {{ $donasi->alasan_penolakan ?? 'Donasi tidak memenuhi standar kelayakan konsumsi atau sudah melewati batas waktu kadaluarsa saat divalidasi oleh admin.' }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #eee;">
                Tidak ada data donasi.
            </div>
        @endforelse

    </div>

    @if(isset($donations) && method_exists($donations, 'links'))
    <div class="pagination-container">
        <span>Menampilkan {{ $donations->firstItem() ?? 0 }} - {{ $donations->lastItem() ?? 0 }} dari {{ $donations->total() ?? 0 }}</span>
        <div style="transform: scale(0.85); transform-origin: right;">
            {{ $donations->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif

</div>
@endsection