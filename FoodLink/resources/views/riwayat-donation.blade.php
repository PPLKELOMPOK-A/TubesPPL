<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Donasi</title>
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 untuk Notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f9f9f9; }
        .container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR REVISI (Sesuai Desain Sebelumnya) --- */
        .sidebar { 
            width: 280px; 
            background: #e6d1a3; 
            padding: 40px 15px; 
            border-right: 1px solid #e0e0e0; 
            position: fixed; 
            height: 100%; 
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; width: 100%; flex-grow: 1; }
        .sidebar-menu li { margin-bottom: 15px; width: 100%; }
        .sidebar-menu li a { 
            display: block; 
            padding: 12px 20px; 
            text-decoration: none; 
            color: #5b3a1e; 
            font-size: 15px; 
            font-weight: 600; 
            border-radius: 12px; 
            transition: 0.3s;
            box-sizing: border-box;
            width: 100%;
        }
        .sidebar-menu li.active a { background: #5b3a1e; color: white !important; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .sidebar-menu li a:hover:not(.active) { background: rgba(91, 58, 30, 0.1); }
        .logout { color: #5b3a1e; font-weight: bold; cursor: pointer; text-decoration: none; padding: 20px; }

        /* Content */
        .main-content { flex: 1; margin-left: 280px; padding: 40px; }
        .search-bar { width: 100%; max-width: 600px; padding: 12px; border: 1px solid #ddd; border-radius: 10px; margin-bottom: 30px; background: white; }

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
</head>
<body x-data="{ 
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
}">

<div class="container">
    <!-- Sidebar Revisi -->
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="#">Beranda</a></li>
            <li class="active"><a href="{{ route('riwayat-donasi.index') }}">Riwayat Donasi</a></li>
            <li><a href="#">Riwayat Koordinasi</a></li>
            <li><a href="#">Bukti Donasi</a></li>
        </ul>
        <a href="#" class="logout">Logout</a>
    </div>

    <!-- Content -->
    <div class="main-content">
        <h2 style="font-weight: 800; font-size: 28px; color: #333;">Riwayat Donasi</h2>
        
        <input type="text" class="search-bar" placeholder="Cari riwayat donasi...">

        @forelse($donations as $item)
        <div class="donation-card" id="card-{{ $item->id }}">
            <img src="{{ asset('storage/' . $item->foto) }}" class="thumb-img" onerror="this.src='https://via.placeholder.com/120x90'">
            
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

                    @if($item->comment)    
                        <p class="comment-text">"{{ $item->comment }}"</p>
                    @else
                        <p class="comment-text" style="color: #bbb;">(Tidak ada pesan tambahan)</p>
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
            <a href="{{ route('bukti-donasi.show', $item->id) }}" class="btn-view-proof">Lihat Bukti</a>
        </div>
        @empty
        <div style="text-align: center; padding: 50px; color: #888;">
            <p>Belum ada riwayat donasi.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Penilaian -->
<div x-show="openModal" class="modal-overlay" x-cloak x-transition>
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

</body>
</html>