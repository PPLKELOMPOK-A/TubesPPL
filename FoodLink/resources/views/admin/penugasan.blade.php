@extends('layouts.app')

@section('content')
<style>
    .admin-container { display: flex; min-height: 100vh; margin-top: -24px; }
    
    /* SIDEBAR */
    .sidebar {
        width: 230px; background: #e8d3a5;
        display: flex; flex-direction: column; 
        justify-content: space-between;
        padding: 20px 10px;
    }
    .sidebar-menu a, .logout-btn {
        display: flex; align-items: center; gap: 14px; padding: 14px;
        margin-bottom: 10px; text-decoration: none; color: #333;
        border-radius: 12px; font-size: 15px; transition: all 0.25s ease;
        border: none; background: none; width: 100%; cursor: pointer;
        text-align: left;
    }
    .sidebar-menu a:hover, .logout-btn:hover { background: rgba(90, 62, 27, 0.1); }
    .sidebar-menu a.active { background: #5a3e1b; color: white; }
    .logout-btn { color: #842029; margin-top: auto; }

    /* TOPBAR */
    .topbar {
        width: 100%; height: 65px; background: #fff;
        border-bottom: 1px solid #ddd; display: flex;
        justify-content: space-between; align-items: center;
        padding: 0 30px; position: sticky; top: 0; z-index: 10;
    }
    .topbar h2 { margin: 0; font-size: 18px; font-weight: bold; color: #2C1A0E; }
    .topbar-right { display: flex; align-items: center; gap: 20px; }

    /* CONTENT */
    .content-area { flex: 1; background: #f5f5f5; display: flex; flex-direction: column; }
    .main-body { padding: 30px 40px; }

    /* BUTTON */
    .action-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
    .btn-add {
        background: #5a3e1b; color: white;
        padding: 10px 20px; border: none;
        border-radius: 8px; text-decoration: none;
        font-size: 14px;
    }

    /* TABLE */
    .table-box {
        background: white; padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow-x: auto;
    }
    table { width: 100%; border-collapse: collapse; }
    th {
        background: #5a3e1b; color: white;
        padding: 12px; text-align: center;
        font-size: 13px;
    }
    td {
        padding: 12px; text-align: center;
        border-bottom: 1px solid #eee;
    }

    .action-icons {
        display: flex;
        justify-content: center;
        gap: 12px;
    }

    .alert {
        background: #d4edda;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
</style>

<div class="admin-container">
    
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}">
                <i data-lucide="home"></i> Beranda
            </a>
            <a href="{{ route('validasi.index') }}">
                <i data-lucide="file-check"></i> Validasi Donasi
            </a>
            <a href="{{ url('/admin/chat') }}">
                <i data-lucide="mail"></i> Chat
            </a>
            <a href="{{ route('admin.retur.index') }}">
                <i data-lucide="corner-down-left"></i> Retur Donasi
            </a>
            <a href="{{ route('penugasan.index') }}" class="active">
                <i data-lucide="users"></i> Penugasan Relawan
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i data-lucide="log-out"></i> Logout
            </button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content-area">
        <div class="topbar">
            <h2>Penugasan Relawan</h2>
        </div>

        <div class="main-body">

            @if(session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- BUTTON -->
            <div class="action-bar">
                <a href="{{ route('penugasan.create') }}" class="btn-add">
                    Tambah Relawan
                </a>
            </div>

            <!-- TABLE -->
            <div class="table-box">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Donasi</th>
                            <th>Donatur</th>
                            <th>Relawan</th>
                            <th>Pengambilan</th>
                            <th>Pengantaran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td>{{ $item->id_penugasan }}</td>
                            <td>{{ $item->id_donasi }}</td>
                            <td>{{ $item->nama_donatur }}</td>
                            <td>{{ $item->relawan }}</td>
                            <td>{{ $item->lokasi_pengambilan }}</td>
                            <td>{{ $item->lokasi_pengantaran }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d-m-Y') }}</td>

                            <td>
                                <div class="action-icons">
                                    <a href="/admin/penugasan/edit/{{ $item->id }}">
                                        ✏️
                                    </a>

                                    <form action="/admin/penugasan/{{ $item->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="8">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection