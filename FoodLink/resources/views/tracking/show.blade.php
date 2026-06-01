@extends('layouts.app')

@section('title', 'Detail Tracking Pengiriman')

@section('content')
@php
    $statusText = match ($status) {
        'terkirim', 'selesai' => 'Selesai',
        'menunggu', 'menunggu_penjemputan' => 'Menunggu Penjemputan',
        default => 'Dalam Perjalanan',
    };

    $statusClass = match ($status) {
        'terkirim', 'selesai' => 'done',
        'menunggu', 'menunggu_penjemputan' => 'waiting',
        default => 'progress',
    };
@endphp

<style>
    .tracking-detail-page {
        min-height: calc(100vh - 70px);
        background: #FFF9EE;
        padding: 36px 42px;
    }

    .detail-header {
        background: linear-gradient(135deg, #6B4F2A, #9B7644);
        color: #ffffff;
        border-radius: 24px;
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .detail-header h1 {
        margin: 0 0 8px;
        font-size: 31px;
        font-weight: 800;
        color: #ffffff;
    }

    .detail-header p {
        margin: 0;
        color: rgba(255,255,255,0.92);
        font-size: 14px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(255,255,255,0.18);
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .back-button:hover {
        background: rgba(255,255,255,0.28);
        color: #ffffff;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.25fr 0.85fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .card-box {
        background: #ffffff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        margin: 0 0 16px;
        color: #1f2937;
        font-size: 21px;
        font-weight: 800;
    }

    .map-frame {
        width: 100%;
        height: 390px;
        border: 0;
        border-radius: 16px;
        background: #f3f4f6;
        margin-bottom: 16px;
    }

    .map-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
    }

    .map-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: 0.2s ease;
    }

    .map-btn.primary {
        background: #6B4F2A;
        color: #ffffff;
    }

    .map-btn.primary:hover {
        background: #563d1f;
        color: #ffffff;
    }

    .map-btn.secondary {
        background: #f6efe2;
        color: #6B4F2A;
        border: 1px solid #e9dcc4;
    }

    .map-btn.secondary:hover {
        background: #efe2ca;
        color: #6B4F2A;
    }

    .route-boxes {
        display: grid;
        gap: 12px;
    }

    .route-box {
        background: #FFF9EE;
        border: 1px solid #f0e5d2;
        border-radius: 14px;
        padding: 14px;
    }

    .route-box span,
    .info-item span {
        display: block;
        margin-bottom: 6px;
        color: #8b6a3d;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .route-box p,
    .info-item p {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
    }

    .status-pill {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .status-pill.progress {
        background: #fff3cd;
        color: #856404;
    }

    .status-pill.done {
        background: #d4edda;
        color: #155724;
    }

    .status-pill.waiting {
        background: #e5e7eb;
        color: #374151;
    }

    .info-list {
        display: grid;
        gap: 12px;
    }

    .info-item {
        background: #FFF9EE;
        border: 1px solid #f0e5d2;
        border-radius: 14px;
        padding: 14px;
    }

    .timeline-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .timeline {
        display: grid;
        gap: 18px;
    }

    .timeline-step {
        display: grid;
        grid-template-columns: 34px 1fr;
        gap: 14px;
        align-items: flex-start;
    }

    .timeline-dot {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        background: #e5e7eb;
        color: #6b7280;
    }

    .timeline-dot.done {
        background: #16803c;
        color: #ffffff;
    }

    .timeline-dot.active {
        background: #6B4F2A;
        color: #ffffff;
    }

    .timeline-content h3 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 800;
        color: #1f2937;
    }

    .timeline-content p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }

    .admin-note {
        margin-top: 20px;
        background: #fff8e8;
        border: 1px solid #efdcae;
        border-radius: 16px;
        padding: 18px;
    }

    .admin-note h3 {
        margin: 0 0 8px;
        font-size: 16px;
        font-weight: 800;
        color: #6B4F2A;
    }

    .admin-note p {
        margin: 0 0 14px;
        font-size: 14px;
        color: #5b4b2f;
        line-height: 1.6;
    }

    .chat-admin-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border-radius: 12px;
        background: #6B4F2A;
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
    }

    .chat-admin-btn:hover {
        background: #563d1f;
        color: #ffffff;
    }

    @media (max-width: 1000px) {
        .tracking-detail-page {
            padding: 22px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-header {
            flex-direction: column;
        }

        .map-frame {
            height: 300px;
        }
    }
</style>

<div class="tracking-detail-page">
    <div class="detail-header">
        <div>
            <h1>Detail Tracking Donasi #{{ $tracking->id_donasi ?? '-' }}</h1>
            <p>ID Penugasan: {{ $tracking->id_penugasan ?? '-' }}</p>
        </div>

        <a href="{{ route('donation.tracking') }}" class="back-button">
            ← Kembali
        </a>
    </div>

    <div class="detail-grid">
        <div class="card-box">
            <h2 class="card-title">Peta Pengiriman</h2>

            <iframe
                class="map-frame"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                src="https://maps.google.com/maps?q={{ urlencode($mapFocus) }}&output=embed">
            </iframe>

            <div class="map-actions">
                <a href="{{ $directionUrl }}" target="_blank" class="map-btn primary">
                    Buka Rute di Google Maps
                </a>

                <a href="{{ $pickupMapUrl }}" target="_blank" class="map-btn secondary">
                    Lihat Titik Pengambilan
                </a>

                <a href="{{ $deliveryMapUrl }}" target="_blank" class="map-btn secondary">
                    Lihat Titik Pengantaran
                </a>
            </div>

            <div class="route-boxes">
                <div class="route-box">
                    <span>Lokasi Pengambilan</span>
                    <p>{{ $tracking->lokasi_pengambilan ?? '-' }}</p>
                </div>

                <div class="route-box">
                    <span>Lokasi Pengantaran</span>
                    <p>{{ $tracking->lokasi_pengantaran ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="card-box">
            <h2 class="card-title">Informasi Pengiriman</h2>

            <span class="status-pill {{ $statusClass }}">
                {{ $statusText }}
            </span>

            <div class="info-list">
                <div class="info-item">
                    <span>Nama Donatur</span>
                    <p>{{ $tracking->nama_donatur ?? '-' }}</p>
                </div>

                <div class="info-item">
                    <span>Relawan Bertugas</span>
                    <p>{{ $tracking->relawan ?? '-' }}</p>
                </div>

                <div class="info-item">
                    <span>Tanggal Penugasan</span>
                    <p>
                        @if (!empty($tracking->tanggal_penugasan))
                            {{ \Carbon\Carbon::parse($tracking->tanggal_penugasan)->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div class="info-item">
                    <span>Posisi Saat Ini</span>
                    <p>
                        @if ($status === 'terkirim' || $status === 'selesai')
                            Donasi telah sampai di lokasi pengantaran.
                        @elseif ($status === 'menunggu' || $status === 'menunggu_penjemputan')
                            Relawan sedang menunggu proses penjemputan.
                        @else
                            Donasi sedang dalam proses pengiriman oleh relawan.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="timeline-card">
        <h2 class="card-title">Riwayat Status Pengiriman</h2>

        <div class="timeline">
            <div class="timeline-step">
                <div class="timeline-dot done">1</div>
                <div class="timeline-content">
                    <h3>Penugasan Dibuat</h3>
                    <p>Admin telah membuat penugasan relawan untuk donasi ini.</p>
                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-dot active">2</div>
                <div class="timeline-content">
                    <h3>Dalam Perjalanan</h3>
                    <p>Relawan {{ $tracking->relawan ?? '-' }} sedang menangani proses pengambilan dan pengantaran donasi.</p>
                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-dot">3</div>
                <div class="timeline-content">
                    <h3>Konfirmasi Selesai</h3>
                    <p>Jika donasi sudah diterima atau pengiriman telah selesai, silakan hubungi admin melalui fitur chat untuk melakukan konfirmasi penyelesaian.</p>
                </div>
            </div>
        </div>

        <div class="admin-note">
            <h3>Sudah selesai dikirim?</h3>
            <p>Jika pengiriman donasi sudah selesai atau sudah diterima di tujuan, silakan chat admin agar proses dapat dikonfirmasi dan ditindaklanjuti.</p>

            <a href="{{ route('chat.index') }}" class="chat-admin-btn">
                Chat Admin Sekarang
            </a>
        </div>
    </div>
</div>
@endsection