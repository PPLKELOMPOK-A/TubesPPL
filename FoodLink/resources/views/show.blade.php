@extends('layouts.app')

@section('content')

<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* FIX: Memaksa layout keluar dari kontainer pembungkus agar sidebar menempel ke pojok */
    .container, .container-fluid {
        max-width: none !important;
        padding-left: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    main.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Layout Wrapper */
    .app-layout {
        display: flex;
        min-height: 100vh;
        width: 100%;
        background-color: #f5f5f5;
    }

    /* Sidebar Kiri: Menempel Pojok (Full Height) */
    .sidebar-left {
        width: 260px;
        background: #f7e4bc; /* Warna krem sesuai gambar */
        display: flex;
        flex-direction: column;
        padding: 40px 0;
        position: fixed; /* Kunci di posisi ini */
        height: 100vh;
        top: 0;
        left: 0;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .sidebar-menu li {
        margin-bottom: 8px;
    }

    .sidebar-menu a, .sidebar-menu .active-item {
        display: flex;
        align-items: center;
        padding: 12px 25px;
        text-decoration: none;
        color: #5b3a1e;
        font-size: 14px;
        gap: 15px;
        transition: 0.3s;
        cursor: pointer;
    }

    /* Style Aktif: Tombol Cokelat sesuai Gambar 2 */
    .active-item {
        background-color: #5b3a1e;
        color: white !important;
        font-weight: bold;
        margin-right: 20px;
    }

    .sidebar-menu a:hover:not(.active-item) {
        background: rgba(91, 58, 30, 0.05);
    }

    /* Logout Section */
    .logout-box {
        padding: 20px 25px;
        margin-top: auto;
    }

    .logout-box a {
        color: #5b3a1e;
        text-decoration: none;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    /* Konten Kanan: Memberi margin agar tidak tertutup sidebar */
    .main-content {
        flex: 1;
        margin-left: 260px; 
        padding: 60px 80px;
        background: white;
        min-height: 100vh;
    }

    /* Header & Typography */
    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #000;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #666;
        font-size: 14px;
        margin-bottom: 30px;
    }

    /* Area Foto */
    .main-image-wrapper {
        margin-bottom: 25px;
    }

    .main-image-wrapper img {
        width: 100%;
        max-width: 900px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        max-width: 900px;
        margin-bottom: 30px;
    }

    .gallery-grid img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
    }

    .description-text {
        max-width: 900px;
        font-size: 16px;
        line-height: 1.6;
        color: #333;
        margin-bottom: 40px;
        padding: 20px;
        background: #fdfaf3;
        border-left: 4px solid #e6d1a3;
    }

    /* Footer Buttons Rapat Kanan */
    .action-row {
        display: flex;
        justify-content: flex-end; 
        gap: 15px;
        max-width: 900px;
    }

    .btn-action {
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: bold;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        transition: 0.3s;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-download {
        background: #5b3a1e;
        color: white;
    }

    .btn-back {
        background: #fdf0d5;
        color: #5b3a1e;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="app-layout">
    <!-- Sidebar Kiri -->
    <aside class="sidebar-left">
        <ul class="sidebar-menu">
            <li><a href="#"><span></span> Beranda</a></li>
            <div class="active-item">
                    <span></span> Bukti Penyelesaian Donasi
                </div>
            <li><a href="#"><span></span> Riwayat Donasi</a></li>
            <li><a href="#"><span></span> Riwayat Koordinasi</a></li>
            <li>
                
            </li>
        </ul>

        <div class="logout-box">
            <a href="#"><span>🚪</span> Logout</a>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="main-content">
        <header>
            <h1 class="page-title">{{ $data['judul'] }}</h1>
            <p class="page-subtitle">Lihat dan verifikasi hasil distribusi donasi makanan</p>
        </header>

        <div class="main-image-wrapper">
            <img src="{{ asset($data['foto_utama']) }}" alt="Foto Utama">
        </div>

        <div class="gallery-grid">
            @if(!empty($data['galeri']))
                @foreach ($data['galeri'] as $foto)
                    <img src="{{ asset($foto) }}" alt="Foto Galeri">
                @endforeach
            @endif
        </div>

        <div class="description-text">
            <strong>Laporan / Deskripsi:</strong><br>
            {{ $data['deskripsi'] }}
        </div>

        <footer class="action-row">
            <a href="#" id="btn-download-bukti" class="btn-action btn-download">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download Bukti
            </a>

            <a href="{{ route('bukti-donasi.index') }}" class="btn-action btn-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Kembali
            </a>
        </footer>
    </main>
</div>

<script>
    document.getElementById('btn-download-bukti').addEventListener('click', function(e) {
        e.preventDefault();
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: 'Bukti Berhasil Diunduh!'
        });
    });
</script>

@endsection