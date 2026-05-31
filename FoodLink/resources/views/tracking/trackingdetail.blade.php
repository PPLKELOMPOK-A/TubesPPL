@extends('layouts.app')

@section('content')

<div class="main-content-canvas">

    <div style="
        background: linear-gradient(135deg, #6B4F2A, #8B6A3D);
        padding: 32px;
        border-radius: 24px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    ">
        <h1 style="
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
        ">
            Detail Tracking Pengiriman
        </h1>

        <p style="
            opacity: .9;
            font-size: 15px;
        ">
            ID Tracking :
            <strong>
                FL-00{{ $donation->id }}
            </strong>
        </p>
    </div>

    @if($donation->status == 'terkirim')
    <div style="
        background: #DDF8E4; 
        border-left: 6px solid #2E7D32; 
        padding: 20px; 
        border-radius: 16px; 
        margin-bottom: 30px; 
        display: flex; 
        align-items: center; 
        gap: 18px;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
    ">
        <div style="
            background: #2E7D32; 
            color: white; 
            width: 45px; 
            height: 45px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        ">
            <svg style="width:24px; height:24px; fill:currentColor;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
            </svg>
        </div>
        <div>
            <h3 style="color: #2E7D32; font-size: 18px; font-weight: 700; margin-bottom: 4px;">
                Alhamdulillah, Donasi Selesai!
            </h3>
            <p style="color: #1e5221; font-size: 14px; margin: 0;">
                Terima kasih, donasi makanan Anda telah berhasil diantarkan dan diterima di lokasi tujuan dengan baik.
            </p>
        </div>
    </div>
    @endif

    <div style="
        display:grid;
        grid-template-columns: 2fr 1fr;
        gap:24px;
        align-items:start;
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
                    color:#333;
                ">
                    Lokasi Pengiriman
                </h2>
                
                <iframe
                    src="https://maps.google.com/maps?q={{ urlencode($donation->alamat_penyaluran) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%"
                    height="260"
                    style="
                        border:0;
                        border-radius:18px;
                    "
                    allowfullscreen=""
                    loading="lazy">
                </iframe>

                <div style="
                    margin-top:20px;
                    display:flex;
                    flex-direction:column;
                    gap:14px;
                    font-size:14px;
                    color:#555;
                ">
                    <div style="
                        background:#F8F8F8;
                        padding:14px;
                        border-radius:14px;
                    ">
                        📍 <strong>Lokasi Penyaluran </strong><br>
                        {{ $donation->alamat_penyaluran }}
                    </div>
                </div>

            </div>

            <div style="
                background:white;
                border-radius:24px;
                padding:24px;
                box-shadow:0 5px 18px rgba(0,0,0,0.05);
            ">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    margin-bottom:20px;
                ">
                    <h2 style="
                        font-size:20px;
                        font-weight:700;
                        color:#333;
                    ">
                        Timeline Pengiriman
                    </h2>

                    <button onclick="window.location.reload();" style="
                        background:#6B4F2A;
                        color:white;
                        border:none;
                        padding:10px 18px;
                        border-radius:12px;
                        font-size:13px;
                        cursor:pointer;
                        transition:0.2s;
                        display:flex;
                        align-items:center;
                        gap:6px;
                    " onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        <svg style="width:14px; height:14px; fill:currentColor;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"></path>
                        </svg>
                        Refresh Status
                    </button>
                </div>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:18px;
                ">

                    <div style="
                        display:flex;
                        gap:14px;
                        align-items:flex-start;
                    ">
                        <div style="
                            width:14px;
                            height:14px;
                            background:#2E7D32;
                            border-radius:50%;
                            margin-top:5px;
                        "></div>

                        <div>
                            <strong>Donasi Dibuat</strong><br>
                            <span style="
                                color:#777;
                                font-size:14px;
                            ">
                                {{ $donation->created_at }}
                            </span>
                        </div>
                    </div>

                    <div style="
                        display:flex;
                        gap:14px;
                        align-items:flex-start;
                    ">
                        <div style="
                            width:14px;
                            height:14px;
                            background:{{ $donation->status == 'terkirim' ? '#2E7D32' : '#E69500' }};
                            border-radius:50%;
                            margin-top:5px;
                        "></div>

                        <div>
                            <strong>Status Pengiriman</strong><br>
                            <span style="
                                color:#777;
                                font-size:14px;
                            ">
                                {{ ucfirst(str_replace('_',' ', $donation->status)) }}
                            </span>
                        </div>
                    </div>

                    @if($donation->status == 'terkirim')
                    <div style="
                        display:flex;
                        gap:14px;
                        align-items:flex-start;
                    ">
                        <div style="
                            width:14px;
                            height:14px;
                            background:#2E7D32;
                            border-radius:50%;
                            margin-top:5px;
                        "></div>

                        <div>
                            <strong>Selesai</strong><br>
                            <span style="
                                color:#777;
                                font-size:14px;
                            ">
                                Paket telah sampai di tujuan.
                            </span>
                        </div>
                    </div>
                    @endif

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
                    margin-bottom:20px;
                    color:#333;
                ">
                    Detail Donasi
                </h2>

                @if($donation->foto_kegiatan)
                <img 
                    src="{{ asset('storage/' . $donation->foto_kegiatan) }}"
                    style="
                        width:100%;
                        height:220px;
                        object-fit:cover;
                        border-radius:18px;
                        margin-bottom:20px;
                    "
                >
                @endif

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:14px;
                    font-size:14px;
                    color:#555;
                ">

                    <div>
                        <strong>Nama Donasi</strong><br>
                        {{ $donation->judul_donasi }}
                    </div>

                    <div>
                        <strong>Kategori Penerima</strong><br>
                        {{ $donation->kategori_penerima }}
                    </div>

                    <div>
                        <strong>Alamat Penyaluran</strong><br>
                        {{ $donation->alamat_penyaluran }}
                    </div>

                    <div>
                        <strong>Tanggal Kegiatan</strong><br>
                        {{ $donation->tanggal_kegiatan }}
                    </div>

                    <div>
                        <strong>Status Pengiriman</strong><br>
                        <span style="
                            background:
                            {{ $donation->status == 'terkirim' ? '#DDF8E4' : ($donation->status == 'dalam_perjalanan' ? '#FFF4D6' : '#FFE5E5') }};

                            color:
                            {{ $donation->status == 'terkirim' ? '#2E7D32' : ($donation->status == 'dalam_perjalanan' ? '#E69500' : '#D32F2F') }};

                            padding:7px 14px;
                            border-radius:999px;
                            font-size:13px;
                            font-weight:600;
                            display:inline-block;
                            margin-top:6px;
                        ">
                            {{ ucfirst(str_replace('_',' ', $donation->status)) }}
                        </span>
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
                    color:#333;
                ">
                    Informasi Pengantar
                </h2>

                <div style="
                    display:flex;
                    align-items:center;
                    gap:16px;
                ">

                    <div style="
                        width:60px;
                        height:60px;
                        background:#F8E7C1;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:26px;
                    ">
                        🛵
                    </div>

                    <div>
                        <strong style="font-size:16px;">
                            Mitra Relawan Foodlink
                        </strong><br>
                        <span style="
                            color:#777;
                            font-size:14px;
                        ">
                            Terima kasih atas partisipasi donasi Anda.
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection