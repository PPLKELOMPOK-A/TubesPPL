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
        color: #ffffff;
        padding: 34px;
        border-radius: 24px;
        margin-bottom: 28px;
        box-shadow: 0 16px 32px rgba(107, 79, 42, 0.16);
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
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .tracking-stat-card {
        background: #ffffff;
        padding: 24px;
        border-radius: 18px;
        border: 1px solid #f0e5d2;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .tracking-stat-card span {
        display: block;
        color: #8b8b8b;
        font-size: 13px;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .tracking-stat-card strong {
        display: block;
        font-size: 32px;
        line-height: 1;
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
        border: 1px solid #f0e5d2;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    }

    .tracking-search-form {
        display: flex;
        gap: 10px;
        align-items: stretch;
    }

    .tracking-search-input {
        flex: 1;
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        outline: none;
        color: #374151;
        background: #ffffff;
        transition: 0.2s ease;
    }

    .tracking-search-input:focus {
        border-color: #6B4F2A;
        box-shadow: 0 0 0 3px rgba(107, 79, 42, 0.14);
    }

    .tracking-search-button {
        border: none;
        border-radius: 12px;
        background: #6B4F2A;
        color: #ffffff;
        padding: 0 22px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .tracking-search-button:hover {
        background: #563d1f;
        transform: translateY(-1px);
    }

    .tracking-list {
        background: #ffffff;
        border-radius: 22px;
        padding: 28px;
        border: 1px solid #f0e5d2;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .tracking-list-title {
        margin: 0 0 24px;
        font-size: 20px;
        font-weight: 800;
        color: #1f2937;
    }

    .tracking-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
        margin-bottom: 24px;
    }

    .tracking-card-link:last-of-type {
        margin-bottom: 0;
    }

    .tracking-card {
        position: relative;
        border: 1px solid #eadcc2;
        border-radius: 18px;
        padding: 24px;
        background: #ffffff;
        cursor: pointer;
        transition: 0.2s ease;
        box-shadow: 0 8px 18px rgba(107, 79, 42, 0.05);
    }

    .tracking-card:hover {
        transform: translateY(-2px);
        border-color: #d4b98a;
        box-shadow: 0 14px 30px rgba(107, 79, 42, 0.12);
    }

    .tracking-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0e5d2;
    }

    .tracking-card-title {
        margin: 0 0 6px;
        color: #3f2c14;
        font-size: 18px;
        font-weight: 800;
    }

    .tracking-card-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.5;
    }

    .tracking-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        background: #fff3cd;
        color: #856404;
        border: 1px solid #f3d27a;
    }

    .tracking-status.is-done {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .tracking-status.is-process {
        background: #fff3cd;
        color: #856404;
        border-color: #f3d27a;
    }

    .tracking-status.is-pending {
        background: #f3f4f6;
        color: #4b5563;
        border-color: #d1d5db;
    }

    .tracking-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 22px;
    }

    .tracking-info {
        min-height: 84px;
        background: #FFF9EE;
        border: 1px solid #f2e4ca;
        border-radius: 14px;
        padding: 15px 16px;
    }

    .tracking-info-label {
        display: block;
        margin-bottom: 7px;
        color: #8b6a3d;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .tracking-info-value {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        line-height: 1.55;
        font-weight: 500;
        word-break: break-word;
    }

    .tracking-empty {
        text-align: center;
        color: #7c6f5f;
        padding: 42px 24px;
        font-size: 14px;
        background: #FFF9EE;
        border: 1px dashed #e3cfa9;
        border-radius: 16px;
    }

    .tracking-pagination {
        margin-top: 26px;
    }

    @media (max-width: 900px) {
        .tracking-container {
            padding: 24px;
        }

        .tracking-hero {
            padding: 26px;
        }

        .tracking-hero h1 {
            font-size: 26px;
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

        .tracking-list {
            padding: 20px;
        }

        .tracking-card {
            padding: 20px;
        }

        .tracking-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .tracking-grid {
            grid-template-columns: 1fr;
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

        @forelse (($trackings ?? collect()) as $tracking)
            @php
                $rawStatus = $tracking->status ?? $tracking->status_pengiriman ?? 'Dalam Perjalanan';
                $statusText = trim((string) $rawStatus) !== '' ? $rawStatus : 'Dalam Perjalanan';
                $statusLower = strtolower((string) $statusText);

                if (str_contains($statusLower, 'terkirim') || str_contains($statusLower, 'selesai')) {
                    $statusClass = 'is-done';
                } elseif (str_contains($statusLower, 'pending') || str_contains($statusLower, 'menunggu')) {
                    $statusClass = 'is-pending';
                } else {
                    $statusClass = 'is-process';
                }
            @endphp

            <a href="{{ route('tracking.show', $tracking->id_penugasan ?? $tracking->id) }}" class="tracking-card-link">
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <div>
                            <h3 class="tracking-card-title">
                                Donasi #{{ $tracking->id_donasi ?? '-' }}
                            </h3>

                            <p class="tracking-card-subtitle">
                                ID Penugasan: {{ $tracking->id_penugasan ?? '-' }}
                            </p>
                        </div>

                        <span class="tracking-status {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <div class="tracking-grid">
                        <div class="tracking-info">
                            <span class="tracking-info-label">Nama Donatur</span>
                            <p class="tracking-info-value">
                                {{ $tracking->nama_donatur ?? '-' }}
                            </p>
                        </div>

                        <div class="tracking-info">
                            <span class="tracking-info-label">Relawan Bertugas</span>
                            <p class="tracking-info-value">
                                {{ $tracking->relawan ?? '-' }}
                            </p>
                        </div>

                        <div class="tracking-info">
                            <span class="tracking-info-label">Lokasi Pengambilan</span>
                            <p class="tracking-info-value">
                                {{ $tracking->lokasi_pengambilan ?? '-' }}
                            </p>
                        </div>

                        <div class="tracking-info">
                            <span class="tracking-info-label">Lokasi Pengantaran</span>
                            <p class="tracking-info-value">
                                {{ $tracking->lokasi_pengantaran ?? '-' }}
                            </p>
                        </div>

                        <div class="tracking-info">
                            <span class="tracking-info-label">Tanggal Penugasan</span>
                            <p class="tracking-info-value">
                                @if (!empty($tracking->tanggal_penugasan))
                                    {{ \Carbon\Carbon::parse($tracking->tanggal_penugasan)->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="tracking-empty">
                Belum ada data tracking donasi dari penugasan relawan.
            </div>
        @endforelse

        @if (isset($trackings) && method_exists($trackings, 'links'))
            <div class="tracking-pagination">
                {{ $trackings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection