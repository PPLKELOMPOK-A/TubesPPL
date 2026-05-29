@extends('layouts.app')

@section('content')

<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
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

    .main-content {
        flex: 1;
        padding: 60px 80px;
        background: white;
        min-height: 100vh;
    }

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

    .main-image-wrapper {
        margin-bottom: 25px;
    }

    .main-image-wrapper img {
        width: 100%;
        max-width: 900px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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

<main class="main-content">
    <header>
        <h1 class="page-title">{{ $data->judul }}</h1>
        <p class="page-subtitle">Lihat dan verifikasi hasil distribusi donasi makanan</p>
    </header>

    <div class="main-image-wrapper">
        @if($data->foto)
            <img src="{{ asset('img/' . $data->foto) }}" alt="Foto Utama">
        @else
            <p style="color:#999;">Tidak ada foto tersedia</p>
        @endif
    </div>

    <div class="description-text">
        <strong>Laporan / Deskripsi:</strong><br>
        {{ $data->deskripsi }}
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

        <a href="{{ route('bukti.donasi') }}" class="btn-action btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Kembali
        </a>
    </footer>
</main>

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