@extends('layouts.app')

@section('content')

<style>
    /* =========================================
       CSS KHUSUS PUSAT NOTIFIKASI
       ========================================= */
    .notification-wrapper {
        max-width: 1080px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Manrope', sans-serif;
        width: 100%;
    }
    .notif-header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
    }
    .notif-title h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 900;
        font-size: 36px;
        color: #000000;
        margin-bottom: 8px;
    }
    .notif-title p {
        font-size: 18px;
        color: #4D463D;
    }
    .btn-mark-read {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border: 1px solid rgba(117, 87, 80, 0.2);
        border-radius: 12px;
        background: transparent;
        font-weight: 700;
        font-size: 16px;
        color: #000000;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-mark-read:hover {
        background: rgba(117, 87, 80, 0.05);
    }
    .notif-filters {
        display: flex;
        gap: 12px;
        padding-bottom: 24px;
        border-bottom: 1px solid rgba(188, 179, 131, 0.2);
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .filter-pill {
        padding: 10px 32px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 14px;
        color: #4D463D;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .filter-pill.active {
        background: #000000;
        color: #FFFFFF;
        box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
        padding-bottom: 40px;
    }
    .notif-card {
        display: flex;
        gap: 24px;
        padding: 24px;
        border-radius: 32px;
        transition: transform 0.2s ease;
    }
    .notif-card.unread {
        background: rgba(252, 244, 214, 0.3);
        border-left: 8px solid #755750;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
    }
    .notif-card.read {
        background: rgba(252, 244, 214, 0.2);
        border-left: 8px solid transparent; 
    }
    .notif-icon-box {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 80px;
        height: 80px;
        border-radius: 16px;
        flex-shrink: 0;
    }
    .unread .notif-icon-box { background: rgba(117, 87, 80, 0.1); }
    .read .notif-icon-box { background: #F7EEC9; }
    .notif-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .notif-content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .notif-content-header h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 20px;
        color: #38330E;
        margin: 0;
    }
    .dot-unread {
        width: 14px;
        height: 14px;
        background: #755750;
        border-radius: 50%;
        box-shadow: 0px 0px 10px rgba(117, 87, 80, 0.5);
    }
    .notif-content p {
        font-size: 16px;
        line-height: 1.6;
        color: #4D463D;
        margin: 0;
    }
    .notif-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-top: 8px;
    }
    .meta-tag {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 900;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(117, 87, 80, 0.7);
    }
    .meta-time {
        font-weight: 700;
        font-size: 12px;
        color: rgba(102, 96, 55, 0.7);
    }
</style>

<div class="notification-wrapper">
    
    <div class="notif-header-section">
        <div class="notif-title">
            <h1>Pusat Notifikasi</h1>
            <p>Pantau semua aktivitas dan pembaruan Anda di sini.</p>
        </div>
        <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn-mark-read">
                <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 6L7 11L21 1" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Tandai semua dibaca
            </button>
        </form>
    </div>

    <div class="notif-filters">
        <a href="{{ route('notifikasi.index', ['filter' => 'all']) }}" class="filter-pill {{ $filter == 'all' ? 'active' : '' }}" style="text-decoration: none;">Semua</a>
        <a href="{{ route('notifikasi.index', ['filter' => 'unread']) }}" class="filter-pill {{ $filter == 'unread' ? 'active' : '' }}" style="text-decoration: none;">Belum Dibaca</a>
        
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('notifikasi.index', ['filter' => 'donasi']) }}" class="filter-pill {{ $filter == 'donasi' ? 'active' : '' }}" style="text-decoration: none;">Donasi</a>
            <a href="{{ route('notifikasi.index', ['filter' => 'relawan']) }}" class="filter-pill {{ $filter == 'relawan' ? 'active' : '' }}" style="text-decoration: none;">Relawan</a>
            <a href="{{ route('notifikasi.index', ['filter' => 'sistem']) }}" class="filter-pill {{ $filter == 'sistem' ? 'active' : '' }}" style="text-decoration: none;">Sistem</a>
        @endif

        @if(auth()->user()->role === 'donatur')
            <a href="{{ route('notifikasi.index', ['filter' => 'status donasi']) }}" class="filter-pill {{ $filter == 'status donasi' ? 'active' : '' }}" style="text-decoration: none;">Status Donasi</a>
            <a href="{{ route('notifikasi.index', ['filter' => 'komunitas']) }}" class="filter-pill {{ $filter == 'komunitas' ? 'active' : '' }}" style="text-decoration: none;">Komunitas</a>
        @endif
    </div>

    <div class="notif-list">
        @forelse($notifications as $notification)
            @php
                $isUnread = is_null($notification->read_at);
                $title = $notification->data['title'] ?? 'Pemberitahuan';
                $message = $notification->data['message'] ?? '';
                $category = $notification->data['category'] ?? 'Sistem';
            @endphp
            
            <div class="notif-card {{ $isUnread ? 'unread' : 'read' }}" 
                onclick="window.location.href='{{ $isUnread ? route('notifikasi.markSingleAsRead', $notification->id) : route('notifikasi.show', $notification->id) }}';" 
                style="cursor: pointer;">
                
                {{-- KODE <FORM> SEBELUMNYA DI SINI SUDAH DIHAPUS --}}

                <div class="notif-icon-box">
                    @if($isUnread)
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="#755750"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    @else
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="rgba(102, 96, 55, 0.5)"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    @endif
                </div>

                <div class="notif-content">
                    <div class="notif-content-header">
                        <h3 style="{{ !$isUnread ? 'color: rgba(56, 51, 14, 0.8);' : '' }}">{{ $title }}</h3>
                        @if($isUnread)
                            <div class="dot-unread"></div>
                        @endif
                    </div>
                    <p style="{{ !$isUnread ? 'color: rgba(102, 96, 55, 0.7);' : '' }}">{{ $message }}</p>
                    <div class="notif-meta">
                        <span class="meta-tag" style="{{ !$isUnread ? 'color: rgba(102, 96, 55, 0.4);' : '' }}">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 12 12"><circle cx="6" cy="6" r="6"/></svg> 
                            {{ $category }}
                        </span>
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="rgba(188, 179, 131, 0.4)"><circle cx="3" cy="3" r="3"/></svg>
                        <span class="meta-time">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px; color: #4D463D; font-family: 'Manrope', sans-serif;">
                <i class="fa-regular fa-bell-slash" style="font-size: 40px; margin-bottom: 16px; color: rgba(117, 87, 80, 0.4);"></i>
                <p style="font-size: 16px; font-weight: 500;">Tidak ada notifikasi yang sesuai dengan filter ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection