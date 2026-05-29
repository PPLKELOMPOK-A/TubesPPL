@extends('layouts.app')

@section('content')
<div class="app">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-title">Bukti Penyelesaian Donasi</div>
            <ul class="menu">
                <li><a href="#">Beranda</a></li>
                <li><a href="#">Riwayat Donasi</a></li>
                <li><a href="#">Riwayat Koordinasi</a></li>
                <li><a href="#" class="active">Bukti Donasi</a></li>
            </ul>
        </div>
        <div class="logout">
            <a href="#">Logout</a>
        </div>
    </aside>

    <!-- Main content -->
    <main class="main">
        <!-- Topbar -->
        <div class="topbar">
            <div class="icons">
                <span>&#128276;</span>
                <div class="avatar"></div>
            </div>
        </div>

        <div class="content">
            <!-- Header -->
            <div class="page-title">Bukti Penyelesaian Donasi</div>
            <div class="page-subtitle">Lihat dan Verifikasi hasil distribusi donasi makanan</div>

            <!-- Search -->
            <div class="search-box">
                <form method="GET" action="{{ route('bukti-donasi.index') }}">
                    <input type="text" name="search" placeholder="Search" value="{{ $search ?? '' }}">
                </form>
            </div>

            <!-- List Donasi -->
            <div class="list">
                @forelse ($donasiList as $item)
                    <div class="card">
                        <div class="card-left">
                            <img src="{{ $item['gambar'] ?? asset('images/placeholder.png') }}" alt="Thumbnail" class="thumb">
                            <div class="info">
                                <h3>{{ $item['judul'] }}</h3>
                                <p>{{ $item['organisasi'] }}</p>
                                <p>{{ $item['tanggal'] }}</p>
                            </div>
                        </div>

                        <div class="actions">
                            <a href="{{ route('bukti-donasi.detail', $item['id']) }}" class="btn btn-secondary">Detail</a>
                            <a href="{{ route('bukti-donasi.show', $item['id']) }}" class="btn btn-secondary">Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="empty">Data tidak ditemukan.</div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <span>{{ $donasiList->firstItem() ?? 0 }}-{{ $donasiList->lastItem() ?? 0 }} dari {{ $donasiList->total() ?? 0 }}</span>
                <div class="pages">
                    {{ $donasiList->links('vendor.pagination.simple-tailwind') }}
                </div>
            </div>
        </div>
    </main>
</div>
@endsection