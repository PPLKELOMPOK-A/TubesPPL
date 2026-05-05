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

    /* CONTENT AREA */
    .content-area { flex: 1; background: #f5f5f5; display: flex; flex-direction: column; }
    .main-body { padding: 30px 40px; }

    /* FORM CARD */
    .form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        max-width: 900px;
        margin: 0 auto;
    }

    .form-header-title {
        font-size: 18px;
        font-weight: bold;
        color: #5a3e1b;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0ede8;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 30px;
    }

    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group label { font-size: 12px; color: #777; font-weight: bold; text-transform: uppercase; }
    
    .form-group input {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-group input:focus { border-color: #5a3e1b; outline: none; box-shadow: 0 0 0 3px rgba(90, 62, 27, 0.1); }
    .field-readonly { background: #f9f9f9; color: #888; cursor: not-allowed; border-color: #eee; }

    /* ERRORS & ALERTS */
    .error-msg { color: #dc3545; font-size: 12px; margin-top: 4px; }
    .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; }

    /* ACTION BUTTONS */
    .action-row {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-save { background: #5a3e1b; color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; }
    .btn-save:hover { background: #3e2a10; }
    .btn-back { background: white; color: #5a3e1b; border: 1px solid #ddd; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-size: 14px; transition: 0.3s; }
    .btn-back:hover { background: #f8f8f8; }
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
            <h2>Edit Penugasan</h2>
            <div class="topbar-right">
                <i data-lucide="bell" style="cursor: pointer; color: #555;"></i>
                <div class="profile-circle">
                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <div class="main-body">
            @if($errors->any())
                <div class="alert-error">
                    <ul style="margin:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-card">
                <div class="form-header-title">
                    <i data-lucide="edit-3"></i> 
                    Data Penugasan #{{ $penugasan->id_penugasan }}
                </div>

                <form action="/admin/penugasan/{{ $penugasan->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label>ID Penugasan</label>
                            <input type="text" value="{{ $penugasan->id_penugasan }}" class="field-readonly" readonly>
                        </div>

                        <div class="form-group">
                            <label>ID Donasi</label>
                            <input type="text" value="{{ $penugasan->id_donasi }}" class="field-readonly" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Donatur</label>
                            <input type="text" name="nama_donatur" value="{{ old('nama_donatur', $penugasan->nama_donatur) }}">
                            @error('nama_donatur') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Relawan</label>
                            <input type="text" name="relawan" value="{{ old('relawan', $penugasan->relawan) }}">
                            @error('relawan') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Lokasi Pengambilan</label>
                            <input type="text" name="lokasi_pengambilan" value="{{ old('lokasi_pengambilan', $penugasan->lokasi_pengambilan) }}">
                        </div>

                        <div class="form-group">
                            <label>Lokasi Pengantaran</label>
                            <input type="text" name="lokasi_pengantaran" value="{{ old('lokasi_pengantaran', $penugasan->lokasi_pengantaran) }}">
                            @error('lokasi_pengantaran') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Penugasan</label>
                            <input type="date" name="tanggal_penugasan" value="{{ old('tanggal_penugasan', $penugasan->tanggal_penugasan) }}">
                            @error('tanggal_penugasan') <span class="error-msg">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="action-row">
                        <a href="{{ route('penugasan.index') }}" class="btn-back">Batal</a>
                        <button type="submit" class="btn-save">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection