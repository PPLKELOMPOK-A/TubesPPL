@extends('layouts.app')

@section('content')

<div class="main-content-canvas">

    <!-- HEADER -->
    <div style="
        background: linear-gradient(135deg, #6B4F2A, #9A7B4F);
        border-radius: 24px;
        padding: 35px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    ">
        <h1 style="
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
        ">
            Tracking Pengiriman
        </h1>

        <p style="
            opacity: .9;
            font-size: 15px;
        ">
            Monitor pengiriman donasi makanan secara real-time
        </p>
    </div>

    <!-- STATISTIK -->
    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    ">

        <!-- TOTAL -->
        <div style="
            background: white;
            padding: 25px;
            border-radius: 22px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        ">
            <p style="color:#888; font-size:14px;">TOTAL DONASI</p>
            <h2 id="total-donasi" style="
                margin-top:10px;
                font-size:32px;
                color:#6B4F2A;
            ">
                {{ $total }}
            </h2>
        </div>

        <!-- TERKIRIM -->
        <div style="
            background: white;
            padding: 25px;
            border-radius: 22px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        ">
            <p style="color:#888; font-size:14px;">TERKIRIM</p>
            <h2 id="terkirim" style="
                margin-top:10px;
                font-size:32px;
                color:#2E7D32;
            ">
                {{ $terkirim }}
            </h2>
        </div>

        <!-- DALAM PERJALANAN -->
        <div style="
            background: white;
            padding: 25px;
            border-radius: 22px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        ">
            <p style="color:#888; font-size:14px;">DALAM PERJALANAN</p>
            <h2 id="dalam-perjalanan" style="
                margin-top:10px;
                font-size:32px;
                color:#E69500;
            ">
                {{ $dalamPerjalanan }}
            </h2>
        </div>

    </div>

    <!-- LIST DONASI -->
    <div id="donation-list" style="
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
    ">

        @php
            $statusStyles = [
                'menunggu' => 'background:#FFE5E5;color:#D32F2F;',
                'dalam_perjalanan' => 'background:#FFF4D6;color:#E69500;',
                'terkirim' => 'background:#DDF8E4;color:#2E7D32;',
            ];
        @endphp

       @forelse ($donations as $d)

<a href="{{ route('tracking.detail', $d->id) }}" style="
    text-decoration:none;
">

<div style="
    background:white;
    border-radius:24px;
    padding:24px;
    box-shadow:0 5px 18px rgba(0,0,0,0.05);
    transition:.2s;
    cursor:pointer;
">

            <!-- STATUS -->
            <span style="
                {{ $statusStyles[$d->status] ?? '' }}
                padding:8px 14px;
                border-radius:999px;
                font-size:13px;
                font-weight:600;
                display:inline-block;
            ">
                {{ ucfirst(str_replace('_',' ', $d->status)) }}
            </span>

            <!-- JUDUL -->
            <h3 style="
                margin-top:18px;
                font-size:22px;
                color:#333;
                font-weight:700;
            ">
                {{ $d->judul_donasi }}
            </h3>

            <!-- DETAIL -->
            <div style="
                margin-top:18px;
                display:flex;
                flex-direction:column;
                gap:10px;
                color:#555;
                font-size:14px;
            ">

                <div>
                    🍱 Kategori:
                    <strong>{{ $d->kategori_penerima }}</strong>
                </div>

                <div>
                    📍 {{ $d->alamat_penyaluran }}
                </div>

                <div>
                    📅 {{ $d->tanggal_kegiatan }}
                </div>

            </div>

        </div>

</a>

@empty

        <div style="
            grid-column:1/-1;
            background:white;
            padding:60px;
            border-radius:24px;
            text-align:center;
            color:#888;
        ">
            Belum ada data tracking donasi.
        </div>

        @endforelse

    </div>

    <!-- PAGINATION -->
    <div style="margin-top:35px;">
        {{ $donations->links() }}
    </div>

</div>

@endsection