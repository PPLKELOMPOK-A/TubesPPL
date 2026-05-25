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

<div class="main-content-canvas">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start; /* Agar sejajar atas jika teks sebelah kiri panjang */
        margin-bottom:30px;
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
                margin-top:6px;
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
        grid-template-columns:2fr 1fr;
        gap:24px;
    ">

        <div style="
            display:flex;
            flex-direction:column;
            gap:24px;
        ">

            <div style="
                background:white;
                border-radius:24px;
                padding:24px;
                box-shadow:0 5px 18px rgba(0,0,0,0.05);
            ">
                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:18px;
                ">
                    Lokasi Pengiriman
                </h2>

                <iframe
                    src="https://maps.google.com/?q={{ urlencode($donation->alamat_penyaluran) }}&output=embed"
                    width="100%"
                    height="280"
                    style="border:0; border-radius:18px;"
                    loading="lazy">
                </iframe>

                <div style="
                    margin-top:18px;
                    color:#555;
                    line-height:1.8;
                ">
                    📍 {{ $donation->alamat_penyaluran }}
                </div>
            </div>

            <div style="
                background:white;
                border-radius:24px;
                padding:24px;
                box-shadow:0 5px 18px rgba(0,0,0,0.05);
            ">

                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:20px;
                ">
                    Timeline Pengiriman
                </h2>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:18px;
                ">

                    <div>
                        ✅ Donasi dibuat
                        <br>
                        <small style="color:#888;">
                            {{ $donation->created_at }}
                        </small>
                    </div>

                    <div>
                        🚚 Status:
                        <strong>
                            {{ ucfirst(str_replace('_',' ', $donation->status)) }}
                        </strong>
                    </div>

                </div>
            </div>

        </div>

        <div style="
            display:flex;
            flex-direction:column;
            gap:24px;
        ">

            <div style="
                background:white;
                border-radius:24px;
                padding:24px;
                box-shadow:0 5px 18px rgba(0,0,0,0.05);
            ">

                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:18px;
                ">
                    Detail Donasi
                </h2>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:14px;
                    color:#555;
                    line-height:1.7;
                ">

                    <div>
                        🍱 <strong>Judul Donasi</strong><br>
                        {{ $donation->judul_donasi }}
                    </div>

                    <div>
                        🏷️ <strong>Kategori</strong><br>
                        {{ $donation->kategori_penerima }}
                    </div>

                    <div>
                        📅 <strong>Tanggal</strong><br>
                        {{ $donation->tanggal_kegiatan }}
                    </div>

                    <div>
                        📍 <strong>Alamat</strong><br>
                        {{ $donation->alamat_penyaluran }}
                    </div>

                    <div>
                        📝 <strong>Deskripsi</strong><br>
                        {{ $donation->deskripsi }}
                    </div>

                </div>
            </div>

            <div style="
                background:white;
                border-radius:24px;
                padding:24px;
                box-shadow:0 5px 18px rgba(0,0,0,0.05);
            ">

                <h2 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:18px;
                ">
                    Status Pengiriman
                </h2>

                <div style="
                    background:
                    {{ $donation->status == 'terkirim' ? '#DDF8E4' : '#FFF4D6' }};
                    color:
                    {{ $donation->status == 'terkirim' ? '#2E7D32' : '#E69500' }};
                    padding:14px;
                    border-radius:14px;
                    font-weight:700;
                    text-align:center;
                ">
                    {{ ucfirst(str_replace('_',' ', $donation->status)) }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection