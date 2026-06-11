@extends('layouts.app')

@section('title', 'Ditolak - Foodlink')

@section('content')

<style>
    /* CSS disamakan persis dengan index agar desain tidak pecah */
    .validasi-canvas { padding: 40px 50px; background-color: #FFF9EE; min-height: 100vh; width: 100%; }
    .validasi-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 28px; color: #1A1A1A; margin-bottom: 30px; }
    .stats-container { display: flex; gap: 20px; margin-bottom: 35px; }
    .stat-card { background-color: #FEF3D1; border-radius: 12px; padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: center; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.02); }
    .stat-number { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 32px; color: #32220D; margin-bottom: 12px; }
    .stat-label { font-size: 13px; font-weight: 700; color: #4E453D; text-transform: uppercase; letter-spacing: 0.8px; }
    .tabs-container { display: flex; gap: 16px; margin-bottom: 28px; }
    .tab-btn { padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; color: #4E453D; background-color: #FFFFFF; border: 1px solid rgba(209, 196, 185, 0.3); transition: all 0.2s ease; }
    .tab-btn.active { background-color: #EAE3D2; color: #32220D; border-color: #D1C4B9; font-weight: 700; }
    .donasi-list-container { display: flex; flex-direction: column; gap: 24px; }
    .donasi-card { background-color: #FFFFFF; border-radius: 16px; padding: 24px; display: flex; gap: 24px; box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid rgba(209, 196, 185, 0.15); }
    .donasi-img-wrapper { width: 140px; height: 140px; background-color: #FEF3D1; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .donasi-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .donasi-content { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .donasi-header-row { display: flex; justify-content: space-between; align-items: flex-start; }
    .donasi-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 20px; color: #32220D; margin-bottom: 6px; }
    .donasi-meta { font-size: 14px; color: #80756C; margin-bottom: 14px; }
    .badges-row { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .badge-item { padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; }
    .badge-porsi { background-color: #FEF3D1; color: #4A3721; }
    .badge-layak { background-color: #A0AEC0; color: #FFFFFF; }
    .status-label { padding: 6px 20px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
    .status-ditolak { background-color: #FC8181; color: #FFFFFF; }

    /* Box Keterangan Alasan Khusus Ditolak */
    .reject-reason-box { margin-top: 16px; background-color: #FFF5F5; border: 1px solid #FED7D7; border-radius: 8px; padding: 14px 20px; }
    .reject-title { font-weight: 700; color: #C53030; font-size: 14px; margin-bottom: 4px; }
    .reject-desc { font-weight: 500; color: #C53030; font-size: 14px; line-height: 1.5; }
</style>

<div class="validasi-canvas">
    <h1 class="validasi-title">Validasi Proses Donasi</h1>

    <div class="stats-container">
        <div class="stat-card"><div class="stat-number">{{ $stats['hari_ini'] ?? 0 }}</div><div class="stat-label">MASUK HARI INI</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['menunggu'] ?? 0 }}</div><div class="stat-label">PERLU VALIDASI</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['diproses'] ?? 0 }}</div><div class="stat-label">SUDAH DI PROSES</div></div>
    </div>

    <div class="tabs-container">
        <a href="{{ route('admin.validasi.index') }}" class="tab-btn">Menunggu Validasi</a>
        <a href="{{ route('admin.validasi.disetujui') }}" class="tab-btn">Disetujui</a>
        <a href="{{ route('admin.validasi.ditolak') }}" class="tab-btn active">Ditolak</a>
    </div>

    <div class="donasi-list-container">
        @forelse($donations as $donasi)
            <div class="donasi-card">
                <div class="donasi-img-wrapper">
                    @if(isset($donasi->foto_makanan) && $donasi->foto_makanan)
                        <img src="{{ asset('storage/' . $donasi->foto_makanan) }}">
                    @else
                        <span style="font-size: 32px;">🍲</span>
                    @endif
                </div>

                <div class="donasi-content">
                    <div>
                        <div class="donasi-header-row">
                            <h3 class="donasi-name">{{ $donasi->judul ?? 'Kategori Makanan' }}</h3>
                            <div class="status-label status-ditolak">DITOLAK</div>
                        </div>
                        <div class="donasi-meta">
                            Donatur: {{ $donasi->nama_donatur ?? 'Anonim' }} &nbsp;&nbsp;&nbsp; Dikirim: {{ $donasi->created_at ? \Carbon\Carbon::parse($donasi->created_at)->format('d M, H:i') : '-' }}
                        </div>
                        <div class="badges-row">
                            <span class="badge-item badge-porsi">Porsi: {{ $donasi->quantity ?? 0 }}</span>
                            <span class="badge-item badge-layak">Lokasi: {{ $donasi->lokasi_dropbox ?? 'Dropbox Pusat' }}</span>
                        </div>
                    </div>
                    
                    <div class="reject-reason-box">
                        <div class="reject-title">Keterangan Penolakan:</div>
                        <div class="reject-desc">
                            {{-- Memanggil alasan penolakan dari database --}}
                            {{ $donasi->keterangan_tolak ?? $donasi->alasan_penolakan ?? 'Donasi ditolak oleh admin karena tidak memenuhi standar kelayakan.' }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px;">Tidak ada donasi yang ditolak.</div>
        @endforelse
    </div>
</div>
@endsection