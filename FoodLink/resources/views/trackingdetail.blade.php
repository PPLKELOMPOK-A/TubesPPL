@extends('layouts.app')

@section('content')

<style>
    /* Tambahan style untuk efek hover pada tombol */
    .btn-kembali {
        background: #FBEBCE; /* Warna krem sesuai tema */
        color: #6B4F2A; /* Warna teks coklat tua */
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px; /* Jarak antara icon dan teks */
        transition: all 0.3s ease; /* Animasi halus */
        border: 2px solid #FBEBCE;
    }
    
    .btn-kembali:hover {
        background: #6B4F2A; /* Berubah coklat saat di-hover */
        color: #FBEBCE; /* Teks berubah krem */
        border: 2px solid #6B4F2A;
    }
</style>

<div class="main-content-canvas" style="max-width: 1100px; margin: 0 auto; padding: 10px 20px 40px 20px;">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        margin-bottom: 40px;
    ">
        <div>
            <h1 style="
                font-size:32px;
                font-weight:700;
                color:#333;
                margin: 0;
            ">
                Detail Tracking
            </h1>

            <p style="
                color:#888;
                margin-top:8px;
                margin-bottom: 0;
            ">
                Pantau perjalanan donasi secara real-time
            </p>
        </div>

        <a href="{{ route('donation.tracking') }}" class="btn-kembali">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
    </div>

    <div style="
        display:grid;
        grid-template-columns: 2fr 1fr;
        gap: 32px;
    ">

        <div style="
            display:flex;
            flex-direction:column;
            gap: 32px;
        ">

            <div style="
                background:white;
                border-radius:24px;
                padding: 32px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            ">
                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:20px;
                    color: #222;
                ">
                    Lokasi Pengiriman
                </h2>

                <iframe
    src="https://maps.google.com/maps?q={{ urlencode($donation->alamat_penyaluran) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
    width="100%"
    height="320"
    style="border:0; border-radius:18px;"
    loading="lazy">
</iframe>

                <div style="
                    margin-top:20px;
                    color:#555;
                    line-height:1.6;
                    display: flex;
                    align-items: flex-start;
                    gap: 8px;
                ">
                    <span style="font-size: 18px;">📍</span> 
                    <span>{{ $donation->alamat_penyaluran }}</span>
                </div>
            </div>

            <div style="
                background:white;
                border-radius:24px;
                padding: 32px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            ">

                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:24px;
                    color: #222;
                ">
                    Timeline Pengiriman
                </h2>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:20px;
                ">

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 20px;">✅</span>
                        <div>
                            <strong style="color: #333;">Donasi dibuat</strong>
                            <br>
                            <small style="color:#888; font-size: 13px; margin-top: 4px; display: inline-block;">
                                {{ $donation->created_at }}
                            </small>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 20px;">🚚</span>
                        <div>
                            <span style="color: #555;">Status:</span>
                            <strong style="color: #333; margin-left: 4px;">
                                {{ ucfirst(str_replace('_',' ', $donation->status)) }}
                            </strong>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div style="
            display:flex;
            flex-direction:column;
            gap: 32px;
        ">

            <div style="
                background:white;
                border-radius:24px;
                padding: 32px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            ">

                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:24px;
                    color: #222;
                ">
                    Detail Donasi
                </h2>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:20px;
                    color:#555;
                    line-height:1.6;
                ">

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 18px;">🍱</span>
                        <div>
                            <strong style="color: #333; display: block; margin-bottom: 4px;">Judul Donasi</strong>
                            {{ $donation->judul_donasi }}
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 18px;">🏷️</span>
                        <div>
                            <strong style="color: #333; display: block; margin-bottom: 4px;">Kategori</strong>
                            {{ $donation->kategori_penerima }}
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 18px;">📅</span>
                        <div>
                            <strong style="color: #333; display: block; margin-bottom: 4px;">Tanggal</strong>
                            {{ $donation->tanggal_kegiatan }}
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 18px;">📝</span>
                        <div>
                            <strong style="color: #333; display: block; margin-bottom: 4px;">Deskripsi</strong>
                            {{ $donation->deskripsi }}
                        </div>
                    </div>

                </div>
            </div>

            <div style="
                background:white;
                border-radius:24px;
                padding: 32px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            ">

                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:20px;
                    color: #222;
                ">
                    Status Pengiriman
                </h2>

                <div style="
                    background: {{ $donation->status == 'terkirim' ? '#DDF8E4' : '#FFF4D6' }};
                    color: {{ $donation->status == 'terkirim' ? '#2E7D32' : '#E69500' }};
                    padding: 16px;
                    border-radius: 12px;
                    font-weight: 700;
                    font-size: 16px;
                    text-align: center;
                    letter-spacing: 0.5px;
                ">
                    {{ ucfirst(str_replace('_',' ', $donation->status)) }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection