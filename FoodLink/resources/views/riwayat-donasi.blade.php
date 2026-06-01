@extends('layouts.app')

@section('title', 'Riwayat Donasi')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .page-wrapper { padding: 30px 50px; }
    .search-wrapper { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
    .search-bar { flex: 1; height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #ddd; font-size: 14px; background: white; outline: none; box-sizing: border-box; }
    .btn-search { height: 42px; width: 42px; background: #6B4F2A; color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .filter-tabs { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
    .tab-btn { padding: 8px 20px; border-radius: 20px; border: 1px solid #ddd; background: white; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s; color: #444; text-decoration: none; }
    .tab-btn:hover { background: #f5f5f5; }
    .tab-btn.active { background: #6B4F2A; color: white; border-color: #6B4F2A; }
    .tab-btn.tab-selesai.active { background: #28a745; border-color: #28a745; }
    .tab-btn.tab-diproses.active { background: #ffc107; border-color: #ffc107; color: #333; }
    .tab-btn.tab-ditolak.active { background: #dc3545; border-color: #dc3545; }
    .tab-btn.tab-diretur.active { background: #6c757d; border-color: #6c757d; }
    .donation-card { background: white; padding: 20px; border-bottom: 1px solid #eee; display: flex; gap: 20px; position: relative; margin-bottom: 15px; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .info-section { flex: 1; }
    .info-section h4 { margin: 0 0 5px 0; font-size: 18px; color: #333; }
    .category { color: #888; font-size: 14px; margin-bottom: 3px; font-weight: 600; }
    .date { color: #bbb; font-size: 13px; margin-bottom: 10px; }
    .status-badge { font-size: 12px; font-weight: bold; margin-top: 8px; display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; margin-bottom: 5px; }
    .status-selesai { background: #d4edda; color: #155724; }
    .status-disetujui { background: #cce5ff; color: #004085; }
    .status-diproses { background: #e0f2f1; color: #00897b; }
    .status-pending { background: #fff4e5; color: #f39c12; }
    .status-ditolak { background: #f8d7da; color: #721c24; }
    .status-diretur { background: #e2e3e5; color: #383d41; }
    .action-group { display: flex; flex-direction: column; gap: 10px; align-items: flex-end; flex-shrink: 0; min-width: 140px; }
    .btn-edit { background-color: #f39c12; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box; }
    .btn-edit:hover { background-color: #e67e22; color: white; }
    .btn-delete { background-color: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box; }
    .btn-delete:hover { background-color: #c0392b; }
    .btn-view-proof { background: #fff5e6; color: #5b3a1e; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; border: 1px solid #dec9a7; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box; }
    .btn-view-proof:hover { background: #faebd7; }
    .empty-state { text-align: center; padding: 50px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #EAEAEA; }
</style>

<div class="page-wrapper">
    <h2 style="font-weight: 800; font-size: 28px; color: #333; margin-bottom: 15px;">Riwayat Donasi</h2>

    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false });</script>
    @endif
    @if(session('error'))
        <script>Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });</script>
    @endif

    <form method="GET" action="{{ route('riwayat-donasi.index') }}">
        <div class="search-wrapper">
            <input type="text" name="search" value="{{ request('search') }}" class="search-bar" placeholder="Cari riwayat donasi...">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button type="submit" class="btn-search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>

    <div class="filter-tabs">
        <a href="{{ route('riwayat-donasi.index', ['search' => request('search')]) }}" class="tab-btn {{ !request('status') ? 'active' : '' }}">
            Semua ({{ $totalSemua ?? 0 }})
        </a>
        <a href="{{ route('riwayat-donasi.index', ['status' => 'selesai', 'search' => request('search')]) }}" class="tab-btn tab-selesai {{ request('status') == 'selesai' ? 'active' : '' }}">
            ✅ Selesai ({{ $totalSelesai ?? 0 }})
        </a>
        <a href="{{ route('riwayat-donasi.index', ['status' => 'diproses', 'search' => request('search')]) }}" class="tab-btn tab-diproses {{ request('status') == 'diproses' ? 'active' : '' }}">
            ⏳ Diproses ({{ $totalDiproses ?? 0 }})
        </a>
        <a href="{{ route('riwayat-donasi.index', ['status' => 'ditolak', 'search' => request('search')]) }}" class="tab-btn tab-ditolak {{ request('status') == 'ditolak' ? 'active' : '' }}">
            ❌ Ditolak ({{ $totalDitolak ?? 0 }})
        </a>
        <a href="{{ route('riwayat-donasi.index', ['status' => 'diretur', 'search' => request('search')]) }}" class="tab-btn tab-diretur {{ request('status') == 'diretur' ? 'active' : '' }}">
            🔄 Diretur ({{ $totalDiretur ?? 0 }})
        </a>
    </div>

    <div>
        @forelse($donations as $item)
        @php 
            $isRetur = isset($item->_is_retur) && $item->_is_retur;
            $status = $isRetur ? 'diretur' : strtolower($item->status ?? 'pending'); 
            $isPending = !$isRetur && in_array($status, ['pending', 'menunggu', 'menunggu validasi', '']);
        @endphp
        
        <div class="donation-card" id="card-{{ $item->id }}">
            <div class="info-section">
                @if($isRetur)
                    <h4>Retur: {{ $item->nama_makanan ?? '-' }}</h4>
                    <p class="category">{{ $item->kategori ?? '-' }}</p>
                    <p class="date">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('l, d F Y') : '-' }}</p>
                    <span class="status-badge status-diretur"><i class="fas fa-undo"></i> Diretur</span>
                    <br>
                    <span style="font-size: 12px; color: #666; font-weight: 500;">
                        <i class="fa-solid fa-circle-info"></i> Alasan: {{ $item->alasan ?? '-' }}
                    </span>
                @else
                    <h4>Donasi ke {{ $item->kategori_penerima ?? 'Penerima' }}</h4>
                    <p class="category">{{ $item->kategori_makanan }}</p>
                    <p class="date">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') : '-' }}</p>
                    
                    @if($status == 'selesai')
                        <span class="status-badge status-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
                    @elseif($status == 'disetujui')
                        <span class="status-badge status-disetujui"><i class="fas fa-thumbs-up"></i> Disetujui</span>
                    @elseif($status == 'diproses')
                        <span class="status-badge status-diproses"><i class="fas fa-truck"></i> Diproses</span>
                    @elseif($status == 'ditolak')
                        <span class="status-badge status-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                    @else
                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending (Menunggu Validasi)</span>
                    @endif

                    <br>
                    <span style="font-size: 12px; color: #666; font-weight: 500;">
                        <i class="fa-regular fa-clock"></i> Batas Layak: {{ $item->waktu_layak ?? '-' }}
                    </span>
                @endif
            </div>

            <div class="action-group">
                @if($isPending)
                    <a href="{{ route('donasi.edit', $item->id) }}" class="btn-edit">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <form action="{{ route('donasi.cancel', $item->id) }}" method="POST" id="form-batal-{{ $item->id }}" style="margin: 0; width: 100%;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-delete" onclick="confirmBatal({{ $item->id }})">
                            <i class="fa-solid fa-trash-can"></i> Batalkan
                        </button>
                    </form>
                @endif

                @if(in_array($status, ['selesai', 'disetujui', 'ditolak', 'diretur', 'diproses']))
                    <a href="{{ route('riwayat-donasi.bukti', $item->id) }}" class="btn-view-proof">
                        <i class="fa-solid fa-file-invoice"></i> Lihat Bukti
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <p>Belum ada riwayat donasi yang Anda daftarkan.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    function confirmBatal(id) {
        Swal.fire({
            title: 'Batalkan Donasi?',
            text: "Data donasi yang dibatalkan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#999',
            confirmButtonText: 'Ya, Batalkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-batal-' + id).submit();
            }
        })
    }
</script>
@endsection