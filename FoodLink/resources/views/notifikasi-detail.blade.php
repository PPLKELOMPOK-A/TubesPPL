@extends('layouts.app')

@section('content')
<style>
    .notif-detail-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Manrope', sans-serif;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #6B4F2A;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 24px;
        transition: transform 0.2s ease;
    }

    .btn-back:hover {
        transform: translateX(-4px);
    }

    .notif-detail-card {
        background: #FFFFFF;
        border: 1px solid rgba(188, 179, 131, 0.2);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0px 4px 20px rgba(117, 87, 80, 0.05);
    }

    .notif-detail-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .detail-tag {
        font-weight: 900;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #755750;
        background: rgba(117, 87, 80, 0.1);
        padding: 6px 16px;
        border-radius: 9999px;
    }

    .detail-time {
        font-size: 13px;
        color: rgba(102, 96, 55, 0.7);
        font-weight: 600;
    }

    .notif-detail-card h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 900;
        font-size: 32px;
        color: #38330E;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .notif-description {
        font-size: 16px;
        line-height: 1.6;
        color: #666037;
        margin-bottom: 30px;
    }

    /* Rincian Blok Informasi */
    .info-spec-box {
        background-color: #FFFCEF;
        border: 1px solid rgba(117, 87, 80, 0.15);
        border-radius: 16px;
        padding: 24px;
        margin-top: 20px;
    }

    .info-spec-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 16px;
        color: #503C1B;
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(117, 87, 80, 0.1);
        padding-bottom: 8px;
    }

    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        line-height: 1.5;
        border-bottom: 1px dashed rgba(188, 179, 131, 0.2);
        padding-bottom: 8px;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-key {
        font-weight: 700;
        color: #755750;
        width: 40%;
    }

    .info-value {
        font-weight: 500;
        color: #4D463D;
        width: 60%;
        text-align: right;
    }

    .notif-detail-footer {
        margin-top: 40px;
        padding-top: 24px;
        border-top: 1px dashed rgba(188, 179, 131, 0.3);
        display: flex;
        justify-content: flex-end;
    }

    .btn-action {
        background: #6B4F2A;
        color: #FFFFFF;
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .btn-action:hover {
        background: #503C1B;
    }
</style>

<div class="notif-detail-container">
    <a href="{{ route('notifikasi.index') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Pusat Notifikasi
    </a>

    <div class="notif-detail-card">
        <div class="notif-detail-meta">
            <span class="detail-tag">{{ $category }}</span>
            <span class="detail-time">
                <i class="fa-regular fa-clock" style="margin-right: 4px;"></i> 
                {{ $time->translatedFormat('d F Y, H:i') }} ({{ $time->diffForHumans() }})
            </span>
        </div>

        <h1>{{ $title }}</h1>
        <p class="notif-description">{{ $message }}</p>

        {{-- JIKA DATA RINCIAN TERSEDIA, TAMPILKAN BOX INFO --}}
        @if(!empty($details) && count($details) > 0)
            <div class="info-spec-box">
                <div class="info-spec-title">Rincian Informasi Data</div>
                <div class="info-grid">
                    @foreach($details as $key => $value)
                        <div class="info-row">
                            <span class="info-key">{{ $key }}</span>
                            <span class="info-value">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="notif-detail-footer">
            <a href="{{ url('/dashboard') }}" class="btn-action">
                <i class="fa-solid fa-house" style="margin-right: 8px;"></i> Ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection