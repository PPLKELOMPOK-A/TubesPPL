@extends('layouts.app')

@section('title', 'Detail Bukti Donasi')

@section('content')
<style>
    .bukti-container { 
        max-width: 800px; 
        margin: 0 auto; 
        background: white; 
        padding: 30px; 
        border-radius: 15px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.08); 
    }
    .header-bukti { 
        display: flex; 
        align-items: center; 
        margin-bottom: 30px; 
        border-bottom: 1px solid #eee; 
        padding-bottom: 15px; 
    }
    .btn-back { text-decoration: none; color: #333; font-size: 1.5rem; margin-right: 20px; }
    .title-section-bukti h2 { margin: 0; color: #2c3e50; font-size: 1.5rem; }
    .status-badge-selesai { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    .status-badge-disetujui { background: #cce5ff; color: #004085; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    .status-badge-diproses { background: #e0f2f1; color: #00897b; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    .status-badge-ditolak { background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    .status-badge-diretur { background: #e2e3e5; color: #383d41; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    .status-badge-pending { background: #fff4e5; color: #f39c12; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px; display: inline-block; }
    .content-card-bukti { display: grid; grid-template-columns: 1fr; gap: 20px; }
    .detail-info { background: #fffaf5; border-radius: 10px; padding: 20px; border: 1px solid #ffe8d6; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #ddd; padding-bottom: 8px; }
    .info-row span:first-child { color: #7f8c8d; font-size: 0.9rem; }
    .info-row span:last-child { font-weight: 600; color: #2c3e50; text-align: right; }
    .description-box { line-height: 1.6; color: #555; background: #fdfdfd; padding: 15px; border-radius: 8px; border: 1px solid #eee; }
</style>

<div class="main-content-canvas">
    <div class="bukti-container">
        <div class="header-bukti">
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div class="title-section-bukti">
                @if($isRetur)
                    <span class="status-badge-diretur">🔄 Diretur</span>
                    <h2>Retur: {{ $donasi->nama_makanan ?? '-' }}</h2>
                @else
                    @php $status = strtolower($donasi->status ?? 'pending'); @endphp
                    @if($status == 'selesai')
                        <span class="status-badge-selesai">✅ Selesai Disalurkan</span>
                    @elseif($status == 'disetujui')
                        <span class="status-badge-disetujui">👍 Disetujui</span>
                    @elseif($status == 'diproses')
                        <span class="status-badge-diproses">🚚 Sedang Diproses</span>
                    @elseif($status == 'ditolak')
                        <span class="status-badge-ditolak">❌ Ditolak</span>
                    @else
                        <span class="status-badge-pending">⏳ Menunggu Validasi</span>
                    @endif
                    <h2>Donasi ke {{ $donasi->kategori_penerima ?? 'Penerima' }}</h2>
                @endif
            </div>
        </div>

        <div class="content-card-bukti">
            <div class="detail-info">
                @if($isRetur)
                    <div class="info-row">
                        <span>ID Retur</span>
                        <span>#RTR-00{{ $donasi->id ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>ID Donasi</span>
                        <span>#DON-00{{ $donasi->id_donasi ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Tanggal Pengajuan</span>
                        <span>{{ $donasi->tanggal_pengajuan ? \Carbon\Carbon::parse($donasi->tanggal_pengajuan)->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Nama Makanan</span>
                        <span>{{ $donasi->nama_makanan ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Jumlah</span>
                        <span>{{ $donasi->jumlah ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Kategori</span>
                        <span>{{ $donasi->kategori ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Alasan Retur</span>
                        <span>{{ $donasi->alasan ?? '-' }}</span>
                    </div>
                @else
                    <div class="info-row">
                        <span>ID Transaksi</span>
                        <span>#TRX-00{{ $donasi->id ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Tanggal Donasi</span>
                        <span>{{ $donasi->created_at ? \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Nama Donatur</span>
                        <span>{{ $donasi->nama_donatur ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Tujuan Penyaluran</span>
                        <span>{{ $donasi->kategori_penerima ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Jenis Makanan</span>
                        <span>{{ $donasi->kategori_makanan ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Lokasi Dropbox</span>
                        <span>{{ $donasi->lokasi_dropbox ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Wilayah</span>
                        <span>{{ $donasi->kategori_wilayah ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Waktu Layak</span>
                        <span>{{ $donasi->waktu_layak ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Status</span>
                        <span>{{ ucfirst($donasi->status ?? '-') }}</span>
                    </div>
                @endif
            </div>

            <div class="description-box">
                <strong>Deskripsi:</strong><br>
                {{ $donasi->deskripsi ?? 'Tidak ada catatan.' }}
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('riwayat-donasi.index') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #5b3a1e; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                ← Kembali ke Riwayat Donasi
            </a>
        </div>
    </div>
</div>
@endsection