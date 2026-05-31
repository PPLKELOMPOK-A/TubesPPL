@extends('layouts.app')

@section('title', 'Bukti Penyelesaian Donasi')

@section('styles')
<style>
    .page-content-container {
        width: 100%;
        max-width: 100%;
        margin-left: 0;
        padding: 30px 40px;
        box-sizing: border-box;
    }

    h2 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #111111;
    }

    .sub {
        color: #888888;
        margin: 0 0 25px 0;
        font-size: 14px;
    }

    .search-wrapper {
        display: flex;
        gap: 0;
        align-items: center;
        width: 100%;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-radius: 8px;
    }

    .search {
        flex: 1;
        padding: 12px 18px;
        border-radius: 8px 0 0 8px;
        border: 1px solid #e2e8f0;
        border-right: none;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
        background: #ffffff;
    }

    .btn-search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 47px;
        width: 48px;
        background: #5b3a1e;
        color: white;
        border: 1px solid #5b3a1e;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.2s;
    }

    .btn-search-icon:hover { background: #422a16; }

    .card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        border-bottom: 1px solid #ededed;
        width: 100%;
    }

    .left {
        display: flex;
        align-items: center;
    }

    .info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #111111;
    }

    .info p {
        margin: 2px 0;
        color: #888888;
        font-size: 13px;
    }

    .actions {
        display: flex;
        gap: 12px;
    }

    .btn-primary {
        background: #5b3a1e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        font-size: 13px;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #422a16; }

    .btn-secondary {
        background: #ffffff;
        color: #46854d;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: #fcfdfc;
        border-color: #46854d;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        font-size: 13px;
        color: #666666;
    }

    .empty {
        text-align: center;
        padding: 40px;
        color: #888888;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #d4edda;
        color: #155724;
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div class="page-content-container">

    <h2>Bukti Penyelesaian Donasi</h2>
    <p class="sub">Lihat dan Verifikasi hasil distribusi donasi makanan</p>

    <form method="GET" action="{{ route('bukti.donasi') }}">
        <div class="search-wrapper">
            <input type="text"
                   name="search"
                   value="{{ $search ?? request('search') }}"
                   class="search"
                   placeholder="Cari bukti donasi...">
            <button type="submit" class="btn-search-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>

    @forelse($donasi as $item)
    <div class="card">
        <div class="left">
            <div class="info">
                <h4>Donasi ke {{ $item->kategori_penerima }}</h4>
                <p>{{ $item->kategori_makanan }}</p>
                <p>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</p>
                <span class="status-badge">✅ {{ ucfirst($item->status) }}</span>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('bukti-donasi.bukti', $item->id) }}" class="btn-primary">
                Detail
            </a>
        </div>
    </div>
    @empty
    <div class="empty">Belum ada donasi yang selesai.</div>
    @endforelse

    <div class="pagination-wrapper">
        <span>
            @if($donasi instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $donasi->firstItem() ?? 0 }}-{{ $donasi->lastItem() ?? 0 }} dari {{ $donasi->total() }}
            @else
                {{ $donasi->count() }} data
            @endif
        </span>
        <div>
            @if($donasi instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $donasi->links() }}
            @endif
        </div>
    </div>

</div>
@endsection