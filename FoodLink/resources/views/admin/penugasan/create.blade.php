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
    
    /* BACKGROUND WRAPPER */
    .bg-wrapper {
        background: url('/img/BackgroundCreate.png') center/cover;
        flex: 1;
        position: relative;
    }
    .bg-overlay {
        background: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(2px);
        height: 100%;
        padding: 40px 20px;
    }

    /* HEADER STYLE */
    .form-header {
        background: #6b4f1d;
        color: white;
        width: fit-content;
        min-width: 400px;
        border-radius: 12px;
        padding: 20px 30px;
        margin: 0 auto 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .form-header i { width: 45px; height: 45px; }
    .form-header h2 { margin: 0; font-size: 20px; }
    .form-header p { margin: 5px 0 0; font-size: 13px; opacity: 0.9; }

    /* FORM CONTAINER */
    .form-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        max-width: 900px;
        margin: 0 auto;
    }
    .form-card {
        background: rgba(240, 240, 240, 0.95);
        padding: 25px;
        border-radius: 15px;
        width: 100%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; font-size: 14px; }
    .form-group input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: 0.3s;
    }
    .form-group input:focus { outline: none; border-color: #6b4f1d; box-shadow: 0 0 5px rgba(107,79,29,0.2); }

    /* SUBMIT BUTTON */
    .submit-row {
        max-width: 900px;
        margin: 25px auto 0;
        display: flex;
        justify-content: flex-end;
    }
    .submit-btn {
        background: #6b4f1d;
        color: white;
        padding: 12px 40px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        transition: 0.3s;
    }
    .submit-btn:hover { background: #4d3815; transform: translateY(-2px); }
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
            <h2>Tambah Penugasan Relawan</h2>
            <div class="topbar-right">
                <i data-lucide="bell" style="cursor: pointer; color: #555;"></i>
                <div class="profile-circle">
                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <!-- BACKGROUND WITH FORM -->
        <div class="bg-wrapper">
            <div class="bg-overlay">
                
                <!-- HEADER FORM -->
                <div class="form-header">
                    <i data-lucide="user-plus"></i>
                    <div class="header-text">
                        <h2>Tambah Penugasan Relawan</h2>
                        <p>Lengkapi data untuk menambahkan relawan ke dalam sistem</p>
                    </div>
                </div>

                <!-- FORM START -->
                <form action="{{ route('penugasan.store') }}" method="POST">
                    @csrf
                    <div class="form-container">
                        <!-- KOLOM KIRI -->
                        <div class="form-card">
                            <div class="form-group">
                                <label>ID Penugasan</label>
                                <input type="text" name="id_penugasan" placeholder="Masukkan ID Penugasan" required>
                            </div>
                            <div class="form-group">
                                <label>ID Donasi</label>
                                <input type="text" name="id_donasi" placeholder="Masukkan ID Donasi" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Donatur</label>
                                <input type="text" name="nama_donatur" placeholder="Masukkan Nama Donatur" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Relawan</label>
                                <input type="text" name="relawan" placeholder="Masukkan Nama Relawan" required>
                            </div>
                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="form-card">
                            <div class="form-group">
                                <label>Lokasi Pengambilan</label>
                                <input type="text" name="lokasi_pengambilan" placeholder="Masukkan Lokasi" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Pengantaran</label>
                                <input type="text" name="lokasi_pengantaran" placeholder="Masukkan Lokasi" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Penugasan</label>
                                <input type="date" name="tanggal_penugasan" required>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTON SUBMIT -->
                    <div class="submit-row">
                        <button type="submit" class="submit-btn">Simpan Data</button>
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