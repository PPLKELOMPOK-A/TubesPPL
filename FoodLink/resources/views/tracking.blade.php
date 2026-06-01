@extends('layouts.app')

@section('title', 'Tracking Pengiriman')

@section('content')
<style>
    .tracking-container {
        padding: 40px 50px;
        background: #FFF9EE;
        min-height: calc(100vh - 70px);
    }

    .tracking-hero {
        background: linear-gradient(135deg, #6B4F2A, #9B7644);
        color: white;
        padding: 34px;
        border-radius: 24px;
        margin-bottom: 28px;
    }

    .tracking-hero h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
    }

    .tracking-hero p {
        margin: 0;
        font-size: 15px;
        color: #ffffff;
        opacity: 0.92;
    }

    .tracking-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    .tracking-stat-card {
        background: #ffffff;
        padding: 24px;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .tracking-stat-card span {
        display: block;
        color: #8b8b8b;
        font-size: 13px;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .tracking-stat-card strong {
        font-size: 32px;
        color: #6B4F2A;
        font-weight: 800;
    }

    .tracking-stat-card .green {
        color: #16803c;
    }

    .tracking-stat-card .orange {
        color: #e68a00;
    }

    .tracking-search-card {
        background: #ffffff;
        padding: 18px;
        border-radius: 18px;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    }

    .tracking-search-form {
        display: flex;
        gap: 10px;
    }

    .tracking-search-input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        outline: none;
        color: #374151;
        background: #ffffff;
    }

    .tracking-search-input:focus {
        border-color: #6B4F2A;
        box-shadow: 0 0 0 2px rgba(107, 79, 42, 0.15);
    }

    .tracking-search-button {
        border: none;
        border-radius: 12px;
        background: #6B4F2A;
        color: #ffffff;
        padding: 0 20px;
        font-weight: 700;
        cursor: pointer;
    }

    .tracking-search-button:hover {
        background: #563d1f;
    }

    .tracking-list {
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .tracking-list-title {
        margin: 0 0 18px;
        font-size: 20px;
        font-weight: 800;
        color: #1f2937;
    }

    .tracking-card {
        border: 1px solid #eee4d2;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        background: #ffffff;
    }

    .tracking-card:last-child {
        margin-bottom: 0;
    }

    .tracking-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0e5d2;
    }

    .tracking-card h3 {
        margin: 0 0 5px;
        color: #3f2c14;
        font-size: 18px;
        font-weight: 800;
    }

    .tracking-card .sub-id {
        margin: 0;
        color: #777;
        font-size: 13px;
    }

    .tracking-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px 24px;
    }

    .tracking-info-block {
        background: #FFF9EE;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .tracking-info-label {
        display: block;
        margin-bottom: 5px;
        color: #8b6a3d;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .tracking-info-value {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        line-height: 1.5;
        font-weight: 500;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        background: #fff3cd;
        color: #856404;
    }

    .empty-tracking {
        text-align: center;
        color: #888;
        padding: 40px;
        font-size: 14px;
    }

    .pagination-wrapper {
        margin-top: 22px;
    }

    @media (max-width: 900px) {
        .tracking-container {
            padding: 24px;
        }

        .tracking-stats {
            grid-template-columns: 1fr;
        }

        .tracking-search-form {
            flex-direction: column;
        }

        .tracking-search-button {
            height: 44px;
        }

        .tracking-grid {
            grid-template-columns: 1fr;
        }

        .tracking-card-header {
            flex-direction: column;
        }
    }
</style>

<div class="tracking-container">
    <div class="tracking-hero">
        <h1>Tracking Pengiriman</h1>
        <p>Monitor pengiriman donasi makanan berdasarkan data penugasan relawan dari admin.</p>
    </div>

    <div class="tracking-stats">
        <div class="tracking-stat-card">
            <span>Total Donasi</span>
            <strong>{{ $total ?? 0 }}</strong>
        </div>

        <div class="tracking-stat-card">
            <span>Terkirim</span>
            <strong class="green">{{ $terkirim ?? 0 }}</strong>
        </div>

        <div class="tracking-stat-card">
            <span>Dalam Perjalanan</span>
            <strong class="orange">{{ $dalamPerjalanan ?? 0 }}</strong>
        </div>
    </div>

    <div class="tracking-search-card">
        <form action="{{ route('donation.tracking') }}" method="GET" class="tracking-search-form">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="tracking-search-input"
                placeholder="Cari berdasarkan ID donasi, nama donatur, relawan, atau lokasi..."
            >

            <button type="submit" class="tracking-search-button">
                Cari
            </button>
        </form>
    </div>

    <div class="tracking-list">
        <h2 class="tracking-list-title">Daftar Tracking Penugasan Relawan</h2>

        @forelse ($donations as $donation)
            <div class="tracking-card">
                <div class="tracking-card-header">
                    <div>
                        <h3>Donasi #{{ $donation->id_donasi ?? '-' }}</h3>
                        <p class="sub-id">ID Penugasan: {{ $donation->id_penugasan ?? '-' }}</p>
                    </div>

                    <span class="status-badge">
                        Dalam Perjalanan
                    </span>
                </div>

                <div class="tracking-grid">
                    <div class="tracking-info-block">
                        <span class="tracking-info-label">Nama Donatur</span>
                        <p class="tracking-info-value">{{ $donation->nama_donatur ?? '-' }}</p>
                    </div>

                    <div class="tracking-info-block">
                        <span class="tracking-info-label">Relawan Bertugas</span>
                        <p class="tracking-info-value">{{ $donation->relawan ?? '-' }}</p>
                    </div>

                    <div class="tracking-info-block">
                        <span class="tracking-info-label">Lokasi Pengambilan</span>
                        <p class="tracking-info-value">{{ $donation->lokasi_pengambilan ?? '-' }}</p>
                    </div>

                    <div class="tracking-info-block">
                        <span class="tracking-info-label">Lokasi Pengantaran</span>
                        <p class="tracking-info-value">{{ $donation->lokasi_pengantaran ?? '-' }}</p>
                    </div>

                    <div class="tracking-info-block">
                        <span class="tracking-info-label">Tanggal Penugasan</span>
                        <p class="tracking-info-value">
                            @if (!empty($donation->tanggal_penugasan))
                                {{ \Carbon\Carbon::parse($donation->tanggal_penugasan)->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-tracking">
                Belum ada data tracking donasi dari penugasan relawan.
            </div>
        @endforelse

        @if (method_exists($donations, 'links'))
            <div class="pagination-wrapper">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection