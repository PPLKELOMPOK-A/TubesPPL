@extends('layouts.app')

@section('title', 'Menunggu Validasi - Foodlink')

@section('content')

<style>
    .validasi-canvas { padding: 40px 50px; background-color: #FFF9EE; min-height: 100vh; width: 100%; }
    .validasi-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 28px; color: #1A1A1A; margin-bottom: 30px; }
    .stats-container { display: flex; gap: 20px; margin-bottom: 35px; }
    .stat-card { background-color: #FEF3D1; border-radius: 12px; padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: center; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.02); }
    .stat-number { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 32px; color: #32220D; margin-bottom: 12px; }
    .stat-label { font-size: 13px; font-weight: 700; color: #4E453D; text-transform: uppercase; letter-spacing: 0.8px; }
    .tabs-container { display: flex; gap: 16px; margin-bottom: 28px; }
    .tab-btn { padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; color: #4E453D; background-color: #FFFFFF; border: 1px solid rgba(209, 196, 185, 0.3); box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.02); transition: all 0.2s ease; }
    .tab-btn.active { background-color: #EAE3D2; color: #32220D; border-color: #D1C4B9; font-weight: 700; }
    .donasi-list-container { display: flex; flex-direction: column; gap: 24px; }
    .donasi-card { background-color: #FFFFFF; border-radius: 16px; padding: 24px; display: flex; gap: 24px; box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid rgba(209, 196, 185, 0.15); }
    .donasi-img-wrapper { width: 140px; height: 140px; background-color: #FEF3D1; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .donasi-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .donasi-img-placeholder { font-size: 32px; }
    .donasi-content { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .donasi-header-row { display: flex; justify-content: space-between; align-items: flex-start; }
    .donasi-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 20px; color: #32220D; margin-bottom: 6px; }
    .donasi-meta { font-size: 14px; color: #80756C; margin-bottom: 14px; }
    .badges-row { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .badge-item { padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; }
    .badge-porsi { background-color: #FEF3D1; color: #4A3721; }
    .badge-layak { background-color: #A0AEC0; color: #FFFFFF; }
    .status-label { padding: 6px 20px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
    .status-menunggu { background-color: #FEF3D1; color: #4A3721; }
    .action-buttons { display: flex; gap: 16px; }
    .btn-action { padding: 12px 36px; border-radius: 8px; font-weight: 700; font-size: 14px; border: none; color: #FFFFFF; cursor: pointer; transition: all 0.2s ease; }
    .btn-setujui { background-color: #48BB78; }
    .btn-setujui:hover { background-color: #38A169; transform: translateY(-1px); }
    .btn-tolak { background-color: #F56565; }
    .btn-tolak:hover { background-color: #E53E3E; transform: translateY(-1px); }
    
    /* CSS Modal */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    .modal-content { background: #fff; padding: 30px; border-radius: 16px; width: 450px; max-width: 90%; display: flex; flex-direction: column; gap: 15px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .modal-close-btn { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 20px; color: #A0AEC0; cursor: pointer; }
    .modal-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 20px; color: #32220D; margin-bottom: 5px; border-bottom: 1px solid #FBEBCE; padding-bottom: 15px; text-align: center;}
    .modal-text { font-size: 14px; color: #4A5568; text-align: center; margin-bottom: 10px; line-height: 1.5;}
    .modal-content textarea { padding: 12px; border: 1px solid #E2E8F0; border-radius: 8px; font-family: inherit; font-size: 14px; outline: none; width: 100%; box-sizing: border-box; resize: vertical; min-height: 100px;}
    .modal-content label { font-size: 13px; font-weight: 700; color: #4A5568; margin-bottom: -8px; }
    .btn-group { display: flex; gap: 10px; margin-top: 10px; }
    .btn-modal { flex: 1; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s; font-size: 14px; }
    .btn-cancel { background: #E2E8F0; color: #4A5568; }
    .btn-confirm-green { background: #48BB78; color: white; }
    .btn-confirm-red { background: #F56565; color: white; }
</style>

<div class="validasi-canvas">
    <h1 class="validasi-title">Validasi Proses Donasi</h1>

    <div class="stats-container">
        <div class="stat-card"><div class="stat-number">{{ $stats['hari_ini'] ?? 0 }}</div><div class="stat-label">MASUK HARI INI</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['menunggu'] ?? 0 }}</div><div class="stat-label">PERLU VALIDASI</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['diproses'] ?? 0 }}</div><div class="stat-label">SUDAH DI PROSES</div></div>
    </div>

    <div class="tabs-container">
        <a href="{{ route('admin.validasi.index') }}" class="tab-btn active">Menunggu Validasi</a>
        <a href="{{ route('admin.validasi.disetujui') }}" class="tab-btn">Disetujui</a>
        <a href="{{ route('admin.validasi.ditolak') }}" class="tab-btn">Ditolak</a>
    </div>

    <div class="donasi-list-container">
        @forelse($donations as $donasi)
            <div class="donasi-card">
                <div class="donasi-img-wrapper">
                    @if(isset($donasi->foto_makanan) && $donasi->foto_makanan)
                        <img src="{{ asset('storage/' . $donasi->foto_makanan) }}" alt="Foto">
                    @else
                        <span class="donasi-img-placeholder">🍲</span>
                    @endif
                </div>

                <div class="donasi-content">
                    <div>
                        <div class="donasi-header-row">
                            <h3 class="donasi-name">{{ $donasi->judul ?? 'Kategori Makanan' }}</h3>
                            <div class="status-label status-menunggu">MENUNGGU</div>
                        </div>
                        <div class="donasi-meta">
                            Donatur: {{ $donasi->nama_donatur ?? 'Anonim' }} &nbsp;&nbsp;&nbsp; Dikirim: {{ $donasi->created_at ? \Carbon\Carbon::parse($donasi->created_at)->format('d M, H:i') : '-' }}
                        </div>
                        <div class="badges-row">
                            <span class="badge-item badge-porsi">Porsi: {{ $donasi->quantity ?? 0 }}</span>
                            <span class="badge-item badge-layak">Lokasi: {{ $donasi->lokasi_dropbox ?? 'Dropbox Pusat' }}</span>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn-action btn-setujui" onclick="bukaModalSetujui('{{ $donasi->id }}')">Setujui</button>
                        <button type="button" class="btn-action btn-tolak" onclick="bukaModalTolak('{{ $donasi->id }}')">Tolak</button>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px;">Tidak ada data menunggu validasi.</div>
        @endforelse
    </div>
</div>

<div id="modalSetujui" class="modal-overlay">
    <div class="modal-content">
        <button type="button" class="modal-close-btn" onclick="tutupModal('modalSetujui')">X</button>
        <h3 class="modal-title">Konfirmasi Persetujuan</h3>
        <p class="modal-text">Apakah Anda yakin ingin menyetujui donasi ini?</p>
        <form id="formSetujui" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf
            <div class="btn-group">
                <button type="button" class="btn-modal btn-cancel" onclick="tutupModal('modalSetujui')">Batal</button>
                <button type="submit" class="btn-modal btn-confirm-green">Ya, Setujui</button>
            </div>
        </form>
    </div>
</div>

<div id="modalTolak" class="modal-overlay">
    <div class="modal-content">
        <button type="button" class="modal-close-btn" onclick="tutupModal('modalTolak')">X</button>
        <h3 class="modal-title" style="color: #E53E3E; border-bottom-color: #FED7D7;">Tolak Donasi</h3>
        <p class="modal-text">Silakan masukkan alasan penolakan.</p>
        <form id="formTolak" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label>Keterangan Tolak <span style="color: red;">*</span></label>
                <textarea name="keterangan_tolak" required placeholder="Contoh: Donasi ditolak karena kemasan rusak..." rows="4"></textarea>
            </div>
            <div class="btn-group">
                <button type="button" class="btn-modal btn-cancel" onclick="tutupModal('modalTolak')">Batal</button>
                <button type="submit" class="btn-modal btn-confirm-red">Tolak Donasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalSetujui(id) {
        document.getElementById('formSetujui').action = "/admin/validasi-proses-donasi/" + id + "/setujui";
        document.getElementById('modalSetujui').style.display = 'flex';
    }
    function bukaModalTolak(id) {
        document.getElementById('formTolak').action = "/admin/validasi-proses-donasi/" + id + "/tolak";
        document.getElementById('modalTolak').style.display = 'flex';
    }
    function tutupModal(idModal) {
        document.getElementById(idModal).style.display = 'none';
    }
</script>
@endsection