@extends('layouts.app')

@section('content')

<style>
    .action-btn {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        padding: 0;
        transition: 0.2s ease-in-out;
        font-family: inherit;
    }
    .action-btn:hover { opacity: 0.7; }
    
    /* Efek Pop saat diklik */
    .pop-anim {
        animation: pop 0.3s ease;
    }
    @keyframes pop {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
</style>

<div class="main-content-canvas" style="padding: 20px 40px 40px 40px;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
        <div>
            <h1 style="font-size:36px; font-weight:700; color:#333;">Komunitas</h1>
            <p style="margin-top:10px; color:#777; font-size:16px;">Berbagi cerita dan update tentang donasi makanan</p>
        </div>

        <form action="{{ route('komunitas.index') }}" method="GET" style="display:flex; gap:16px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari post, atau topik..."
                   style="width:280px; padding:14px 20px; border:none; border-radius:14px; background:white; box-shadow:0 3px 12px rgba(0,0,0,0.05); outline:none; font-size:15px;">
            <select name="kategori" onchange="this.form.submit()"
                    style="padding:14px 18px; border:none; border-radius:14px; background:white; box-shadow:0 3px 12px rgba(0,0,0,0.05); outline:none; font-size:15px; color:#555; cursor:pointer;">
                <option value="">Semua Kategori</option>
                <option value="Inspirasi" {{ request('kategori') == 'Inspirasi' ? 'selected' : '' }}>Inspirasi</option>
                <option value="Donasi" {{ request('kategori') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                <option value="Relawan" {{ request('kategori') == 'Relawan' ? 'selected' : '' }}>Relawan</option>
                <option value="Pertanyaan" {{ request('kategori') == 'Pertanyaan' ? 'selected' : '' }}>Pertanyaan</option>
            </select>
        </form>
    </div>

    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:24px; margin-bottom:40px;">
        <div style="background:white; padding:28px; border-radius:24px; box-shadow:0 5px 18px rgba(0,0,0,0.05);">
            <div style="font-size:36px;font-weight:700;color:#6B4F2A;">1.2K</div>
            <div style="margin-top:8px; color:#777; font-size:16px;">Anggota</div>
        </div>
        <div style="background:white; padding:28px; border-radius:24px; box-shadow:0 5px 18px rgba(0,0,0,0.05);">
            <div style="font-size:36px;font-weight:700;color:#6B4F2A;">{{ $posts->count() }}</div>
            <div style="margin-top:8px; color:#777; font-size:16px;">Postingan</div>
        </div>
        <div style="background:white; padding:28px; border-radius:24px; box-shadow:0 5px 18px rgba(0,0,0,0.05);">
            <div style="font-size:36px;font-weight:700;color:#6B4F2A;">869</div>
            <div style="margin-top:8px; color:#777; font-size:16px;">Mingguan</div>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:30px;">
        @forelse ($posts as $post)
            <div style="background:white; border-radius:28px; padding: 40px; box-shadow:0 5px 18px rgba(0,0,0,0.05);">
                
                <div style="display:flex; align-items:center; justify-content: space-between; gap:20px; margin-bottom:30px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->nama_user) }}&background=F8E7C1&color=6B4F2A" style="width:60px; height:60px; border-radius:50%;">
                        <div>
                            <div style="font-weight:700; color:#333; font-size:18px;">{{ $post->nama_user }}</div>
                            <div style="font-size:14px; color:#888;">@ {{ strtolower(str_replace(' ', '', $post->nama_user)) }}</div>
                        </div>
                    </div>
                    <div style="font-size:14px; color:#888;">{{ $post->created_at->diffForHumans() }}</div>
                </div>

                <h2 style="font-size:28px; font-weight:700; color:#222; margin-bottom:20px;">{{ $post->judul }}</h2>
                <p style="color:#666; line-height:1.8; font-size:16px; margin-bottom:20px;">{{ \Illuminate\Support\Str::limit($post->isi, 200, '...') }}</p>

                <a href="{{ route('komunitas.detail', $post->id) }}" style="color:#4CAF50; font-weight:600; display:inline-block; text-decoration:none; font-size:16px; margin-bottom:30px;">
                    Baca Selengkapnya
                </a>

                <div style="display:flex; gap:40px; color:#777; font-size:16px; align-items:center; padding-top:20px; border-top: 1px solid #eee;">
                    
                    <button class="action-btn" onclick="toggleLike(this, {{ $post->id }})">
                        <i class="far fa-heart" id="icon-like-{{ $post->id }}"></i>
                        <span id="count-like-{{ $post->id }}">{{ rand(10, 200) }}</span>
                    </button>
                    
                    <button class="action-btn" onclick="alert('Bagian komentar sedang dalam pengembangan!')">
                        <i class="far fa-comment"></i> {{ rand(2, 50) }}
                    </button>
                    
                    <button class="action-btn" onclick="toggleRetweet(this, {{ $post->id }})">
                        <i class="fas fa-retweet" id="icon-rt-{{ $post->id }}"></i>
                        <span id="count-rt-{{ $post->id }}">{{ rand(1, 30) }}</span>
                    </button>
                    
                    <button class="action-btn" onclick="toggleBookmark(this, {{ $post->id }})" style="margin-left:auto;">
                        <i class="far fa-bookmark" id="icon-save-{{ $post->id }}"></i>
                    </button>

                </div>
            </div>
        @empty
            <div style="text-align:center; padding:60px; background:white; border-radius:28px; color:#888; font-size:16px; box-shadow:0 5px 18px rgba(0,0,0,0.05);">
                Belum ada cerita atau update di Komunitas dengan kategori tersebut.
            </div>
        @endforelse
    </div>

    <a href="{{ route('komunitas.create') }}" style="position:fixed; bottom:40px; right:50px; width:80px; height:80px; border:none; border-radius:50%; background:#F8D98B; color:#333; font-size:36px; cursor:pointer; box-shadow:0 8px 20px rgba(0,0,0,0.15); display:flex; align-items:center; justify-content:center; text-decoration: none; z-index: 100;">
        <i class="fas fa-plus"></i>
    </a>

</div>

<script>
    // Fungsi Like
    function toggleLike(btn, id) {
        let icon = document.getElementById('icon-like-' + id);
        let countSpan = document.getElementById('count-like-' + id);
        let currentCount = parseInt(countSpan.innerText);

        icon.classList.remove('pop-anim'); // Reset animasi
        void icon.offsetWidth; // Trigger reflow
        icon.classList.add('pop-anim'); // Jalankan animasi

        if (btn.dataset.liked === "true") {
            // Jika sudah dilike, batalkan like
            btn.dataset.liked = "false";
            btn.style.color = "inherit";
            icon.classList.replace('fas', 'far'); // Jadi outline (kosong)
            countSpan.innerText = currentCount - 1;
        } else {
            // Jika belum dilike, tambahkan like
            btn.dataset.liked = "true";
            btn.style.color = "#e0245e"; // Merah khas Twitter/Instagram
            icon.classList.replace('far', 'fas'); // Jadi solid (penuh)
            countSpan.innerText = currentCount + 1;
        }
    }

    // Fungsi Retweet
    function toggleRetweet(btn, id) {
        let icon = document.getElementById('icon-rt-' + id);
        let countSpan = document.getElementById('count-rt-' + id);
        let currentCount = parseInt(countSpan.innerText);

        icon.classList.remove('pop-anim'); 
        void icon.offsetWidth; 
        icon.classList.add('pop-anim');

        if (btn.dataset.retweeted === "true") {
            btn.dataset.retweeted = "false";
            btn.style.color = "inherit";
            countSpan.innerText = currentCount - 1;
        } else {
            btn.dataset.retweeted = "true";
            btn.style.color = "#17bf63"; // Hijau khas Retweet
            countSpan.innerText = currentCount + 1;
        }
    }

    // Fungsi Bookmark
    function toggleBookmark(btn, id) {
        let icon = document.getElementById('icon-save-' + id);
        
        icon.classList.remove('pop-anim'); 
        void icon.offsetWidth; 
        icon.classList.add('pop-anim');

        if (btn.dataset.saved === "true") {
            btn.dataset.saved = "false";
            btn.style.color = "inherit";
            icon.classList.replace('fas', 'far');
        } else {
            btn.dataset.saved = "true";
            btn.style.color = "#1da1f2"; // Biru
            icon.classList.replace('far', 'fas');
        }
    }
</script>

@endsection