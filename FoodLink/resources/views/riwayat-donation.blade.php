@extends('layouts.app')

@section('title', 'Riwayat Donasi')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }

    .container { padding: 30px 50px; max-width: 1100px; width: 100%; margin-left: 0; }
    .page-title { font-weight: 800; font-size: 28px; color: #333; margin-bottom: 25px; }

    /* Search Bar */
    .action-bar { display: flex; gap: 15px; margin-bottom: 30px; }
    .search-wrapper { flex: 1; position: relative; display: flex; align-items: center; gap: 8px; }
    .search-wrapper input { flex: 1; padding: 12px 15px 12px 40px; border: 1px solid #E0E0E0; border-radius: 8px; font-size: 14px; outline: none; }
    .search-wrapper i { position: absolute; left: 15px; top: 14px; color: #A0A0A0; }
    
    .btn-search {
        height: 42px; width: 42px; background: #6B4F2A; color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }

    /* Container List Donasi */
    .donasi-item { 
        display: flex; align-items: flex-start; justify-content: space-between; padding: 25px; border-bottom: 1.5px solid #eee; transition: 0.2s; background: white; margin-bottom: 15px; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .donasi-item:hover { background-color: #fafafa; }
    
    /* Area Kiri (Gambar & Info) */
    .donasi-content { display: flex; align-items: flex-start; flex: 1; min-width: 0; padding-right: 20px; }
    .donasi-img { width: 130px; height: 100px; border-radius: 10px; object-fit: cover; margin-right: 25px; background-color: #f5f5f5; border: 1px solid #eaeaea; flex-shrink: 0; }
    
    .donasi-info { flex: 1; }
    .donasi-info h3 { font-size: 18px; font-weight: 700; color: #333; margin: 0 0 5px 0; }
    .donasi-info .category { font-size: 14px; font-weight: 600; color: #888; margin-bottom: 6px; display: block; }
    .donasi-info .date { font-size: 13px; color: #bbb; margin-bottom: 10px; }

    /* Badge Status Kecil di bawah Tanggal */
    .status-badge-small { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block; margin-bottom: 10px;}
    .bg-pending { background-color: #fff4e5; color: #f39c12; }
    .bg-approved { background-color: #e8f8f5; color: #2ecc71; }

    /* Area Kanan (Tombol Aksi Utama) */
    .action-group { display: flex; flex-direction: column; gap: 10px; align-items: flex-end; flex-shrink: 0; min-width: fit-content; }
    
    .btn-edit { background-color: #f39c12; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    .btn-edit:hover { background-color: #e67e22; color: white; }
    
    .btn-delete { background-color: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    .btn-delete:hover { background-color: #c0392b; }
    
    .badge-processed { background-color: #e8f8f5; color: #2ecc71; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: bold; border: 1px solid #2ecc71; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    
    .btn-view-proof { background: #fff5e6; color: #5b3a1e; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; border: 1px solid #dec9a7; display: inline-flex; align-items: center; gap: 6px; justify-content: center; width: 100%; box-sizing: border-box;}
    .btn-view-proof:hover { background: #faebd7; }

    /* Style Rating & Modal (Dari Kode Teman Anda) */
    .rating-display { margin-top: 5px; color: #f1c40f; font-size: 15px; }
    .comment-text { font-style: italic; color: #555; margin-top: 5px; font-size: 13px; background: #fef9ef; padding: 8px 12px; border-radius: 6px; display: inline-block; border: 1px solid #f9eedc;}
    
    .btn-rate { background: #5b3a1e; color: white; padding: 6px 15px; border-radius: 6px; font-size: 12px; border: none; cursor: pointer; transition: 0.3s; margin-top: 5px; font-weight: 600;}
    .btn-rate:hover { background: #4a2f18; }
    
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: #fff5e6; padding: 30px; border-radius: 20px; width: 400px; text-align: center; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .close-modal { position: absolute; top: 15px; right: 15px; cursor: pointer; font-size: 20px; color: #5b3a1e; }
    .star-rating { font-size: 35px; color: #ddd; margin: 20px 0; cursor: pointer; }
    .star-rating .active { color: #f1c40f; }
    .textarea-rating { width: 100%; height: 100px; border-radius: 10px; border: 1px solid #dec9a7; padding: 12px; margin-bottom: 20px; resize: none; background: white; box-sizing: border-box; font-family: inherit;}
</style>

<div class="main-content-canvas">
    <div class="container" x-data="{ 
        openModal: false, 
        selectedId: null, 
        hover: 0, 
        rating: 0, 
        comment: '',
        submitRating() {
            if(this.rating === 0) return Swal.fire('Opps!', 'Pilih bintang dulu ya', 'warning');
            
            // BYPASS FITUR: Hanya menampilkan notifikasi tanpa benar-benar submit data
            Swal.fire({ 
                icon: 'info',
                title: 'Fitur Dinonaktifkan', 
                text: 'UI Modal ini berfungsi, tetapi fitur pengiriman rating ke database sedang dinonaktifkan sementara.',
                confirmButtonColor: '#5b3a1e'
            }).then(() => {
                this.openModal = false; // Tutup modal setelah pesan muncul
                this.rating = 0;
                this.comment = '';
            });

            // KODE ASLI YANG DINONAKTIFKAN (Buka komentar jika backend sudah siap)
            /*
            Swal.fire({ title: 'Mengirim...', didOpen: () => Swal.showLoading() });
            document.getElementById('form-rating').action = '/riwayat-donasi/rating/' + this.selectedId;
            document.getElementById('form-rating').submit();
            */
        }
    }">
        
        <h2 class="page-title">Riwayat Donasi Anda</h2>

        @if(session('success'))
            <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false });</script>
        @endif

        @if(session('error'))
            <script>Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });</script>
        @endif

        <form action="{{ route('riwayat-donasi.index') }}" method="GET" class="action-bar" id="searchForm">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Cari riwayat donasi..." value="{{ request('search') }}" onchange="document.getElementById('searchForm').submit();">
                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-magnifying-glass" style="position: static; color: white;"></i>
                </button>
            </div>
        </form>

        @forelse($donations as $item)
        <div class="donasi-item">
            <div class="donasi-content">
                @if(!empty($item->foto_makanan))
                    <img src="{{ asset('storage/' . $item->foto_makanan) }}" class="donasi-img" alt="Foto Donasi" onerror="this.src='https://via.placeholder.com/130x100'">
                @else
                    <div class="donasi-img" style="display:flex; align-items:center; justify-content:center; color:#bbb;">
                        <i class="fa-solid fa-image fa-2x"></i>
                    </div> 
                @endif
                
                <div class="donasi-info">
                    @php
                        $rawStatus = $item->status ?? 'menunggu validasi';
                        $statusBersih = trim(strtolower($rawStatus));
                        $isPending = in_array($statusBersih, ['menunggu validasi', 'pending', 'menunggu kurasi', '']);
                    @endphp
                    
                    <span class="category">{{ $item->kategori_makanan }}</span>
                    
                    <h3>
                        {{ $item->kegiatanDonasi ? $item->kegiatanDonasi->judul_donasi : 'Donasi ke ' . $item->kategori_penerima }}
                    </h3>
                    
                    <div class="date">
                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') : '-' }}
                    </div>

                    <span class="status-badge-small {{ $isPending ? 'bg-pending' : 'bg-approved' }}">
                        Status: {{ $item->status ?? 'Menunggu Validasi' }}
                    </span>
                    <br>
                    <span style="font-size: 12px; color: #666; font-weight: 500;">
                        <i class="fa-regular fa-clock"></i> Batas Layak: {{ $item->waktu_layak }}
                    </span>

                    @if(!$isPending) <div class="rating-container" style="margin-top: 15px; border-top: 1px dashed #eee; padding-top: 10px;">
                            @if(isset($item->rating) && $item->rating)
                                <div style="font-size: 12px; color: #888; font-weight: 600;">Penilaian Anda:</div>
                                <div class="rating-display">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $item->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                                @if($item->komentar)  
                                    <div class="comment-text">"{{ $item->komentar }}"</div>
                                @endif
                            @else
                                <button type="button" class="btn-rate" @click="openModal = true; selectedId = {{ $item->id }}">
                                    <i class="fa-regular fa-star"></i> Beri Rating
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
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
                @else
                    <span class="badge-processed">
                        <i class="fa-solid fa-check-circle"></i> Selesai
                    </span>
                    <a href="{{ route('riwayat-donasi.bukti', $item->id) }}" class="btn-view-proof">
                        <i class="fa-solid fa-file-invoice"></i> Lihat Bukti
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #EAEAEA;">
            <p>Belum ada riwayat donasi yang Anda daftarkan.</p>
        </div>
        @endforelse

        <div x-show="openModal" class="modal-overlay" x-cloak x-transition>
            <div class="modal-content" @click.away="openModal = false">
                <span class="close-modal" @click="openModal = false">&times;</span>
                <h3 style="margin-top: 0; color: #5b3a1e;">Beri Penilaian</h3>
                <p style="font-size: 14px; color: #8a6d3b;">Bagaimana kualitas makanan dan koordinasi donasi ini?</p>
                
                <form id="form-rating" method="POST">
                    @csrf
                    <input type="hidden" name="rating" :value="rating" required>

                    <div class="star-rating">
                        <template x-for="i in 5">
                            <i class="fa-star" 
                               :class="i <= (hover || rating) ? 'fas active' : 'far'"
                               @mouseover="hover = i" 
                               @mouseleave="hover = 0" 
                               @click="rating = i"></i>
                        </template>
                    </div>

                    <textarea name="komentar" x-model="comment" class="textarea-rating" placeholder="Tuliskan pesan singkat Anda..."></textarea>

                    <button type="button" @click="submitRating()" class="btn-rate" style="width: 100%; padding: 12px; font-size: 15px;">Kirim Penilaian</button>
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