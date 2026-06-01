@extends('layouts.app')

@section('content')

<style>
    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: white;
        color: #6B4F2A;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .btn-kembali:hover { background-color: #6B4F2A; color: white; }

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
    
    .pop-anim { animation: pop 0.3s ease; }
    @keyframes pop {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
</style>

<div class="main-content-canvas" style="padding: 30px 40px 40px 40px;">

    <div style="max-width: 850px; margin: 0 auto;">

        <div style="margin-bottom: 30px;">
            <a href="{{ route('komunitas.index') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div style="
            background: white;
            border-radius: 28px;
            padding: 50px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.05);
        ">
            <div style="display:flex; align-items:center; justify-content: space-between; gap:20px; margin-bottom:30px;">
                <div style="display:flex; align-items:center; gap:16px;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->nama_user) }}&background=F8E7C1&color=6B4F2A" style="width:65px; height:65px; border-radius:50%;">
                    <div>
                        <div style="font-weight:700; color:#333; font-size:20px;">{{ $post->nama_user }}</div>
                        <div style="font-size:15px; color:#888;">@ {{ strtolower(str_replace(' ', '', $post->nama_user)) }}</div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size:15px; color:#888; margin-bottom: 6px;">{{ $post->created_at->format('d M Y, H:i') }}</div>
                    @if($post->kategori)
                        <span style="background: #F8E7C1; color: #6B4F2A; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                            {{ $post->kategori }}
                        </span>
                    @endif
                </div>
            </div>

            <h1 style="font-size:34px; font-weight:800; color:#222; margin-bottom:24px; line-height: 1.3;">
                {{ $post->judul }}
            </h1>

            <div style="color:#555; line-height:1.9; font-size:18px; margin-bottom:40px;">
                {!! nl2br(e($post->isi)) !!}
            </div>

            <div style="display:flex; gap:40px; color:#777; font-size:18px; align-items:center; padding-top:24px; border-top: 1px solid #eee;">
                
                <button class="action-btn" id="btn-like-{{ $post->id }}" onclick="toggleLike(this, {{ $post->id }})">
                    <i class="far fa-heart" id="icon-like-{{ $post->id }}"></i>
                    <span id="count-like-{{ $post->id }}">{{ rand(10, 200) }}</span>
                </button>
                
                <button class="action-btn" onclick="alert('Bagian komentar sedang dalam pengembangan!')">
                    <i class="far fa-comment"></i> {{ rand(2, 50) }}
                </button>
                
                <button class="action-btn" id="btn-rt-{{ $post->id }}" onclick="toggleRetweet(this, {{ $post->id }})">
                    <i class="fas fa-retweet" id="icon-rt-{{ $post->id }}"></i>
                    <span id="count-rt-{{ $post->id }}">{{ rand(1, 30) }}</span>
                </button>
                
                <button class="action-btn" id="btn-save-{{ $post->id }}" onclick="toggleBookmark(this, {{ $post->id }})" style="margin-left:auto;">
                    <i class="far fa-bookmark" id="icon-save-{{ $post->id }}"></i>
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let postId = {{ $post->id }};
        
        // 1. Cek status Like di memori browser
        let isLiked = localStorage.getItem('foodlink_like_' + postId);
        if(isLiked === 'true') {
            let btn = document.getElementById('btn-like-' + postId);
            let icon = document.getElementById('icon-like-' + postId);
            btn.dataset.liked = "true";
            btn.style.color = "#e0245e"; 
            icon.classList.replace('far', 'fas');
        }

        // 2. Cek status Retweet di memori
        let isRt = localStorage.getItem('foodlink_rt_' + postId);
        if(isRt === 'true') {
            let btn = document.getElementById('btn-rt-' + postId);
            let icon = document.getElementById('icon-rt-' + postId);
            btn.dataset.retweeted = "true";
            btn.style.color = "#17bf63";
        }

        // 3. Cek status Bookmark di memori
        let isSaved = localStorage.getItem('foodlink_save_' + postId);
        if(isSaved === 'true') {
            let btn = document.getElementById('btn-save-' + postId);
            let icon = document.getElementById('icon-save-' + postId);
            btn.dataset.saved = "true";
            btn.style.color = "#1da1f2";
            icon.classList.replace('far', 'fas');
        }
    });

    // Fungsi klik Like (dan simpan ke memori)
    function toggleLike(btn, id) {
        let icon = document.getElementById('icon-like-' + id);
        let countSpan = document.getElementById('count-like-' + id);
        let currentCount = parseInt(countSpan.innerText);

        icon.classList.remove('pop-anim'); void icon.offsetWidth; icon.classList.add('pop-anim');

        if (btn.dataset.liked === "true") {
            btn.dataset.liked = "false";
            btn.style.color = "inherit";
            icon.classList.replace('fas', 'far');
            countSpan.innerText = currentCount - 1;
            localStorage.setItem('foodlink_like_' + id, 'false'); // Hapus dari memori
        } else {
            btn.dataset.liked = "true";
            btn.style.color = "#e0245e";
            icon.classList.replace('far', 'fas');
            countSpan.innerText = currentCount + 1;
            localStorage.setItem('foodlink_like_' + id, 'true'); // Simpan ke memori
        }
    }

    function toggleRetweet(btn, id) {
        let icon = document.getElementById('icon-rt-' + id);
        let countSpan = document.getElementById('count-rt-' + id);
        let currentCount = parseInt(countSpan.innerText);

        icon.classList.remove('pop-anim'); void icon.offsetWidth; icon.classList.add('pop-anim');

        if (btn.dataset.retweeted === "true") {
            btn.dataset.retweeted = "false";
            btn.style.color = "inherit";
            countSpan.innerText = currentCount - 1;
            localStorage.setItem('foodlink_rt_' + id, 'false');
        } else {
            btn.dataset.retweeted = "true";
            btn.style.color = "#17bf63";
            countSpan.innerText = currentCount + 1;
            localStorage.setItem('foodlink_rt_' + id, 'true');
        }
    }

    function toggleBookmark(btn, id) {
        let icon = document.getElementById('icon-save-' + id);
        
        icon.classList.remove('pop-anim'); void icon.offsetWidth; icon.classList.add('pop-anim');

        if (btn.dataset.saved === "true") {
            btn.dataset.saved = "false";
            btn.style.color = "inherit";
            icon.classList.replace('fas', 'far');
            localStorage.setItem('foodlink_save_' + id, 'false');
        } else {
            btn.dataset.saved = "true";
            btn.style.color = "#1da1f2";
            icon.classList.replace('far', 'fas');
            localStorage.setItem('foodlink_save_' + id, 'true');
        }
    }
</script>

@endsection