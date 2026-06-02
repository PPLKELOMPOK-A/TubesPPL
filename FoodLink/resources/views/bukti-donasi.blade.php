@extends('layouts.app')

@section('title', 'Foodlink - Bukti Penyelesaian Donasi')

@section('content')
<style>
    .content-container {
        padding: 40px 50px;
        max-width: 1200px;
        margin-left: 0; 
        margin-right: auto;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 28px;
        color: #6B4F2A;
        font-weight: 700;
        margin-bottom: 5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .page-header p {
        color: #718096;
        font-size: 14px;
    }

    /* Search Bar Styling */
    .search-section {
        background: #FFFFFF;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        margin-bottom: 25px;
    }

    .search-form {
        display: flex;
        gap: 12px;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #A0AEC0;
    }

    .search-input {
        width: 100%;
        padding: 12px 12px 12px 45px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #F8FAFC;
    }

    .search-input:focus {
        outline: none;
        border-color: #6B4F2A;
        background-color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(107, 79, 42, 0.1);
    }

    .btn-search {
        background-color: #6B4F2A;
        color: #FFFFFF;
        border: none;
        padding: 0 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-search:hover {
        background-color: #523B1F;
    }

    /* Table & Card Layout */
    .table-container {
        background: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .custom-table th {
        background-color: #F8E7C1;
        color: #6B4F2A;
        padding: 16px 20px;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 2px solid #E2E8F0;
    }

    .custom-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #EDF2F7;
        font-size: 14px;
        color: #4A5568;
        vertical-align: middle;
    }

    .custom-table tr:hover {
        background-color: #FFF9EE;
    }

    /* Status Badge */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-success {
        background-color: #DEF7EC;
        color: #03543F;
    }

    /* Action Button */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #FFFFFF;
        color: #6B4F2A;
        border: 1px solid #6B4F2A;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-detail:hover {
        background-color: #6B4F2A;
        color: #FFFFFF;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #718096;
    }

    .empty-state i {
        font-size: 48px;
        color: #CBD5E0;
        margin-bottom: 15px;
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #EDF2F7;
    }
</style>

<div class="content-container">
    <!-- Header -->
    <div class="page-header">
        <h1>Bukti Penyelesaian Donasi</h1>
        <p>Lihat dan verifikasi hasil distribusi donasi makanan yang telah Anda lakukan.</p>
    </div>

    <!-- Fitur Pencarian -->
    <div class="search-section">
        <form action="{{ route('bukti.donasi') }}" method="GET" class="search-form">
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Cari berdasarkan kategori makanan atau penerima..." 
                    value="{{ request('search') }}"
                >
            </div>
            <button type="submit" class="btn-search">Cari</button>
            @if(request('search'))
                <a href="{{ route('bukti.donasi') }}" class="btn-detail" style="line-height: 38px; height: 42px; box-sizing: border-box;">Reset</a>
            @endif
        </form>
    </div>

    <!-- List Data Bukti Donasi -->
    <div class="table-container">
        @if($donasi->count() > 0)
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tujuan Penerima</th>
                        <th>Kategori Makanan</th>
                        <th>Tanggal Update</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donasi as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->kategori_penerima }}</strong>
                            </td>
                            <td>{{ $item->kategori_makanan }}</td>
                            <td>{{ $item->updated_at ? $item->updated_at->format('d F Y') : '-' }}</td>
                            <td>
                                <span class="badge-status badge-success">
                                    <i class="fa-solid fa-circle-check"></i> {{ $item->status }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('bukti.donasi.detail', $item->id) }}" class="btn-detail">
                                    <i class="fa-solid fa-eye"></i> Detail Bukti
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Navigasi Paginasi Laravel -->
            <div class="pagination-wrapper">
                <div style="font-size: 13px; color: #718096;">
                    Menampilkan {{ $donasi->firstItem() }} - {{ $donasi->lastItem() }} dari {{ $donasi->total() }} data
                </div>
                <div>
                    {{ $donasi->appends(request()->input())->links() }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-folder-open"></i>
                <h3>Belum Ada Bukti Donasi</h3>
                <p>Data donasi dengan status 'Selesai' atau 'Disetujui' belum ditemukan.</p>
            </div>
        @endif
    </div>
</div>
@endsection