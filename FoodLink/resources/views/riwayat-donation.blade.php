@extends('layouts.app')

@section('title', 'Riwayat Donasi')

@section('styles')
<!-- Font Awesome untuk Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- SweetAlert2 untuk Notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Alpine.js -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    
    .search-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 30px;
    }

    .search-bar {
        flex: 1;
        height: 42px;
        padding: 0 16px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 14px;
        background: white;
        outline: none;
        box-sizing: border-box;
    }

    .btn-search {
        height: 42px;
        width: 42px;
        background: #6B4F2A;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Card Donasi */
    .donation-card { background: white; padding: 20px; border-bottom: 1px solid #eee; display: flex; gap: 20px; position: relative; margin-bottom: 10px; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .thumb-img { width: 120px; height: 90px; border-radius: 8px; object-fit: cover; }
    .info-section { flex: 1; }
    .info-section h4 { margin: 0 0 5px 0; font-size: 18px; color: #333; }
    .category { color: #888; font-size: 14px; margin-bottom: 3px; }
    .date { color: #bbb; font-size: 13px; }
    .status-badge { color: #2ecc71; font-size: 13px; font-weight: bold; margin-top: 8px; display: flex; align-items: center; gap: 5px; }

    /* Rating Section */
    .rating-display { margin-top: 10px; color: #f1c40f; font-size: 16px; }
    .comment-text { font-style: italic; color: #555; margin-top: 5px; font-size: 14px; background: #fef9ef; padding: 8px; border-radius: 6px; display: inline-block; }
    
    /* Buttons */
    .btn-group { display: flex; gap: 10px; margin-top: 15px; }
    .btn-empty { background: #fff5e6; color: #8a6d3b; padding: 8px 20px; border-radius: 6px; font-size: 13px; border: none; }
    .btn-rate { background: #5b3a1e; color: white; padding: 8px 25px; border-radius: 6px; font-size: 13px; border: none; cursor: pointer; transition: 0.3s; }
    .btn-view-proof { background: #fff5e6; color: #5b3a1e; padding: 10px 30px; border-radius: 6px; text-decoration: none; font-size: 14px; position: absolute; right: 20px; bottom: 20px; font-weight: bold; }

    /* Modal Overlay */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: #fff5e6; padding: 30px; border-radius: 20px; width: 400px; text-align: center; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .close-modal { position: absolute; top: 15px; right: 15px; cursor: pointer; font-size: 20px; color: #5b3a1e; }
    .star-rating { font-size: 35px; color: #ddd; margin: 20px 0; cursor: pointer; }
    .star-rating .active { color: #f1c40f; }
    .textarea-rating { width: 100%; height: 100px; border-radius: 10px; border: 1px solid #dec9a7; padding: 12px; margin-bottom: 20px; resize: none; background: white; box-sizing: border-box; }
</style>
@endsection

@section('content')

<h2 style="font-weight: 800; font-size: 28px; color: #333;">Riwayat Donasi</h2>

<form method="GET" action="{{ route('riwayat-donasi.index') }}">
    <div class="search-wrapper">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="search-bar"
               placeholder="Cari riwayat donasi...">
        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</form>

<div x-data="{ 
    openModal: false, 
    selectedId: null, 
    hover: 0, 
    rating: 0, 
    comment: '',
    submitRating() {
        if(this.rating === 0) return Swal.fire('Opps!', 'Pilih bintang dulu ya', 'warning');
        
        Swal.fire({ title: 'Mengirim...', didOpen: () => Swal.showLoading() });

        // Submit form secara manual setelah loading SweetAlert muncul
        document.getElementById('form-rating').action = '/riwayat-donasi/rating/' + this.selectedId;
        document.getElementById('form-rating').submit();
    }
}" x-cloak>

    @forelse($donations as $item)
    <div class="donation-card" id="card-{{ $item->id }}">
        <img src="{{ asset('img/'.$item->foto) }}" class="thumb-img" onerror="this.src='https://via.placeholder.com/120x90'">
        
        <div class="info-section">
            <h4>{{ $item->judul }}</h4>
            <p class="category">{{ $item->kategori }}</p>
            <p class="date">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</p>
            
            <div class="status-badge">
                <i class="fas fa-check-circle"></i> Status: Selesai
            </div>

            <!-- Bagian Penilaian Dinamis -->
            <div class="rating-container-{{ $item->id }}">
                @if($item->rating)
                    <div class="rating-display">
                        @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= $item->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>

                @if($item->komentar)    
                    <p class="komentar-text">"{{ $item->komentar }}"</p>
                @else
                    <p class="komentar-text" style="color: #bbb;">(Tidak ada pesan tambahan)</p>
                @endif  
                @else
                    <div class="btn-group">
                        <button class="btn-empty">Belum ada penilaian</button>
                        <button class="btn-rate" @click="openModal = true; selectedId = {{ $item->id }}">Beri Rating</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Perbaikan Link ke Bukti Donasi -->
        <a href="{{ route('riwayat-donasi.show-bukti', $item->id) }}" class="btn-view-proof">Lihat Bukti</a>
    </div>
    @empty
    <div style="text-align: center; padding: 50px; color: #888;">
        <p>Belum ada riwayat donasi.</p>
    </div>
    @endforelse

    <!-- Modal Penilaian -->
    <div x-show="openModal" class="modal-overlay" x-transition>
        <div class="modal-content" @click.away="openModal = false">
            <span class="close-modal" @click="openModal = false">&times;</span>
            <h3 style="margin-top: 0; color: #5b3a1e;">Beri Penilaian</h3>
            <p style="font-size: 14px; color: #8a6d3b;">Bagaimana kualitas makanan dan koordinasi donasi ini?</p>
            
            <!-- Action akan diisi secara dinamis oleh JavaScript/Alpine -->
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

                <button type="button" @click="submitRating()" class="btn-rate" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold;">Kirim Penilaian</button>
            </form>
        </div>
    </div>

</div>

@endsection