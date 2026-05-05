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
    .profile-circle { width: 38px; height: 38px; border-radius: 50%; overflow: hidden; background: #ccc; border: 1px solid #ddd; }
    .profile-circle img { width: 100%; height: 100%; object-fit: cover; }

    /* CONTENT BODY */
    .content-area { flex: 1; background: #f5f5f5; display: flex; flex-direction: column; }
    .main-body { padding: 30px 40px; }
    
    /* TABLE STYLING */
    .action-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
    .btn-add { background: #5a3e1b; color: white; padding: 10px 20px; border: none; border-radius: 8px; text-decoration: none; font-size: 14px; transition: 0.3s; }
    .btn-add:hover { background: #3d2a12; }

    .table-box { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #5a3e1b; color: white; padding: 12px; text-align: center; font-size: 13px; text-transform: uppercase; }
    td { padding: 12px; text-align: center; border-bottom: 1px solid #eee; font-size: 14px; color: #444; }
    tr:hover { background-color: #fafafa; }

    .action-icons { display: flex; justify-content: center; gap: 12px; }
    .action-icons a { color: #5a3e1b; transition: 0.2s; }
    .action-icons button { color: #dc3545; border: none; background: none; cursor: pointer; padding: 0; }
    .action-icons a:hover { color: #000; }
    
    .alert { background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 8px; border-left: 5px solid #28a745; }
</style>

<div class="admin-container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-top">
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
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i data-lucide="log-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content-area">
        <!-- TOPBAR -->
        <div class="topbar">
            <h2>Penugasan Relawan</h2>
            <div class="topbar-right">
                <i data-lucide="bell" style="cursor: pointer; color: #555;"></i>
                <div class="profile-circle">
                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <div class="main-body">
            <!-- NOTIFIKASI SUKSES -->
            @if(session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="action-bar">
                @if(isset($edit))
                    <form method="POST" action="/admin/penugasan/{{ $edit->id }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-add">Update Data</button>
                    </form>
                @else
                    <a href="{{ route('penugasan.create') }}" class="btn-add">
                        <button type="submit" class="btn-add">Tambah Relawan</button>
                    </a>
                @endif
            </div>

            <!-- TABLE -->
            <div class="table-box">
                <table>
                    <thead>
                        <tr>
                            <th>ID Penugasan</th>
                            <th>ID Donasi</th>
                            <th>Nama Donatur</th>
                            <th>Relawan</th>
                            <th>Lokasi Pengambilan</th>
                            <th>Lokasi Pengantaran</th>
                            <th>Tanggal Penugasan</th>
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
                                        <i data-lucide="pencil" style="width: 18px;"></i>
                                    </a>
                                    <form action="/admin/penugasan/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <i data-lucide="trash-2" style="width: 18px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">Belum ada data penugasan relawan.</td>
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