@extends('layouts.app')

@section('title', 'Riwayat Donasi')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
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
    .thumb-img { width: 130px; height: 100px; border-radius: 10px; object-fit: cover; background: #f5f5f5; flex-shrink: 0; border: 1px solid #eaeaea; }
    .info-section { flex: 1; }
    .info-section h4 { margin: 0 0 5px 0; font-size: 18px; color: #333; }
    .category { color: #888; font-size: 14px; margin-bottom: 3px; font-weight: 600; }
    .date { color: #bbb; font-size: 13px; margin-bottom: 10px; }
    .status-badge { font-size: 12px; font-weight: bold; margin-top: 8px; display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; margin-bottom: 5px;}
    .status-selesai { background: #d4edda; color: #155724; }
    .status-disetujui { background: #cce5ff; color: #004085; }
    .status-diproses { background: #e0f2f1; color: #00897b; }
    .status-pending { background: #fff4e5; color: #f39c12; }
    .status-ditolak { background: #f8d7da; color: #721c24; }
    .action-group { display: flex; flex-direction: column; gap: 10px; align-items: flex-end; flex-shrink: 0; min-width: 140px; }
    .btn-edit { background-color: #f39c12; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    .btn-edit:hover { background-color: #e67e22; color: white; }
    .btn-delete { background-color: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    .btn-delete:hover { background-color: #c0392b; }
    .btn-view-proof { background: #fff5e6; color: #5b3a1e; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; border: 1px solid #dec9a7; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    .btn-view-proof:hover { background: #faebd7; }
    .rating-display { margin-top: 10px; color: #f1c40f; font-size: 16px; }
    .comment-text { font-style: italic; color: #555; margin-top: 5px; font-size: 14px; background: #fef9ef; padding: 8px 12px; border-radius: 6px; display: inline-block; border: 1px solid #f9eedc;}
    .btn-group { display: flex; gap: 10px; margin-top: 15px; }
    .btn-empty { background: #fff5e6; color: #8a6d3b; padding: 8px 20px; border-radius: 6px; font-size: 13px; border: none; }
    .btn-rate { background: #5b3a1e; color: white; padding: 8px 25px; border-radius: 6px; font-size: 13px; border: none; cursor: pointer; transition: 0.3s; font-weight: 600;}
    .btn-rate:hover { background: #4a2f18; }
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: #fff5e6; padding: 30px; border-radius: 20px; width: 400px; text-align: center; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .close-modal { position: absolute; top: 15px; right: 15px; cursor: pointer; font-size: 20px; color: #5b3a1e; }
    .star-rating { font-size: 35px; color: #ddd; margin: 20px 0; cursor: pointer; }
    .star-rating .active { color: #f1c40f; }
    .textarea-rating { width: 100%; height: 100px; border-radius: 10px; border: 1px solid #dec9a7; padding: 12px; margin-bottom: 20px; resize: none; background: white; box-sizing: border-box; font-family: inherit;}
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

    <div x-data="{ openModal: false, selectedId: null, hover: 0, rating: 0, comment: '',
        submitRating() {
            if(this.rating === 0) return Swal.fire('Opps!', 'Pilih bintang dulu ya', 'warning');
            Swal.fire({ title: 'Mengirim...', didOpen: () => Swal.showLoading() });
            let form = document.getElementById('form-rating');
            form.action = '/riwayat-donasi/rating/' + this.selectedId;
            form.submit();
        }
    }" x-cloak>

        @forelse($donations as $item)
        @php 
            $status = strtolower($item->status ?? 'pending'); 
            $isPending = in_array($status, ['pending', 'menunggu validasi', '']);
        @endphp
        
        <div class="donation-card" id="card-{{ $item->id }}">
            @if(!empty($item->foto_makanan))
                <img src="{{ asset('storage/' . $item->foto_makanan) }}" class="thumb-img" alt="Foto Donasi" onerror="this.src='https://via.placeholder.com/120x90'">
            @else
                <div class="thumb-img" style="display:flex; align-items:center; justify-content:center; color:#bbb;">
                    <i class="fa-solid fa-image fa-2x"></i>
                </div> 
            @endif
            
            <div class="info-section">
                <h4>Donasi ke {{ $item->kategori_penerima ?? 'Penerima' }}</h4>
                <p class="category">{{ $item->kategori_makanan }}</p>
                <p class="date">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') : '-' }}</p>
                
                @if($status == 'selesai')
                    <span class="status-badge status-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
                @elseif($status == 'disetujui')
                    <span class="status-badge status-disetujui"><i class="fas fa-thumbs-up"></i> Disetujui</span>
                @elseif($status == 'diproses')
                    <span class="status-badge status-diproses"><i class="fas fa-truck"></i> Diproses</span>
                @elseif($status == 'ditolak' || $status == 'diretur')
                    <span class="status-badge status-ditolak"><i class="fas fa-times-circle"></i> {{ ucfirst($status) }}</span>
                @else
                    <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending (Menunggu Validasi)</span>
                @endif

                <br>
                <span style="font-size: 12px; color: #666; font-weight: 500;">
                    <i class="fa-regular fa-clock"></i> Batas Layak: {{ $item->waktu_layak ?? '-' }}
                </span>

                @if($status == 'selesai' || $status == 'disetujui')
                    <div style="margin-top: 15px; border-top: 1px dashed #eee; padding-top: 10px;">
                        @if($item->rating)
                            <div style="font-size: 12px; color: #888; font-weight: 600;">Penilaian Anda:</div>
                            <div class="rating-display">
                                @for($i=1; $i<=5; $i++)
                                    <i class="{{ $i <= $item->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            @if($item->komentar)    
                                <div class="comment-text">"{{ $item->komentar }}"</div>
                            @else
                                <div class="comment-text" style="color: #bbb;">(Tidak ada pesan tambahan)</div>
                            @endif  
                        @else
                            <div class="btn-group">
                                <button class="btn-empty">Belum ada penilaian</button>
                                <button class="btn-rate" @click="openModal = true; selectedId = {{ $item->id }}"><i class="fa-regular fa-star"></i> Beri Rating</button>
                            </div>
                        @endif
                    </div>
                @endif
                
                @if($status == 'ditolak')
                    <p style="color: #dc3545; font-size: 13px; margin-top: 8px;">
                        <i class="fas fa-info-circle"></i> Donasi ini ditolak dan masuk ke daftar retur.
                    </p>
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

                @if($status == 'selesai' || $status == 'disetujui')
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

        <div x-show="openModal" class="modal-overlay" x-transition>
            <div class="modal-content" @click.away="openModal = false">
                <span class="close-modal" @click="openModal = false">&times;</span>
                <h3 style="margin-top: 0; color: #5b3a1e;">Beri Penilaian</h3>
                <p style="font-size: 14px; color: #8a6d3b;">Bagaimana kualitas makanan dan koordinasi donasi ini?</p>
                <form id="form-rating" method="POST">
                    @csrf
                    <input type="hidden" name="rating" :value="rating" required>
                    <div class="star-rating">
                        <template x-for="i in 5">
                            <i class="fa-star" :class="i <= (hover || rating) ? 'fas active' : 'far'"
                               @mouseover="hover = i" @mouseleave="hover = 0" @click="rating = i"></i>
                        </template>
                    </div>
                    <textarea name="komentar" x-model="comment" class="textarea-rating" placeholder="Tuliskan pesan singkat Anda..."></textarea>
                    <button type="button" @click="submitRating()" class="btn-rate" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold;">Kirim Penilaian</button>
                </form>
            </div>
        </div>
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