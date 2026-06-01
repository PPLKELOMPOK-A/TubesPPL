@extends('layouts.app')

@section('title', 'Edit Penugasan Relawan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght=300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
/* ================= GLOBAL RESETS & SCOPE ================= */
.penugasan-scope {
    font-family: 'Poppins', sans-serif;
    margin: -32px -48px -35px -48px; 
    min-height: calc(100vh - 70px);
    background-image: url('{{ asset("img/BackgroundCreate.png") }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* ================= OVERLAY BLUR ================= */
.penugasan-overlay {
    width: 100%;
    min-height: calc(100vh - 70px);
    background: rgba(255, 255, 255, 0.55);
    backdrop-filter: blur(3px);
    padding: 40px;
    box-sizing: border-box;
    
    /* MANTRA UNTUK KETENGAH (VERTIKAL & HORIZONTAL) */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Memastikan form membentang rapi dan lebih besar di tengah */
.form-wrapper {
    width: 100%;
    max-width: 1000px; /* DIPERBESAR DARI 820px */
}

/* ================= HEADER COKELAT ================= */
.penugasan-header {
    background: #9A6827;
    color: white;
    width: 100%;
    max-width: 1000px; /* DIPERBESAR SEJAJAR DENGAN FORM */
    border-radius: 18px;
    padding: 25px 40px;
    margin: 0 auto 40px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    box-shadow: 0 5px 14px rgba(0,0,0,0.15);
    box-sizing: border-box;
}

.penugasan-header i, 
.penugasan-header svg {
    width: 38px;
    height: 38px;
    color: white;
}

.header-text h2 {
    font-size: 22px; /* Teks diperbesar */
    font-weight: 700;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.header-text p {
    font-size: 13px; /* Teks diperbesar */
    margin: 0;
    opacity: 0.9;
}

/* ================= KOTAK FORM BERJAJAR KESAMPING ================= */
.form-container {
    display: flex;
    flex-direction: row; 
    justify-content: center;
    align-items: stretch; 
    gap: 40px; /* Jarak antar kotak diperlebar */
    width: 100%;
}

.form-card {
    background: rgba(255, 255, 255, 0.95);
    flex: 1; 
    border-radius: 22px;
    padding: 35px; /* Padding di dalam kotak diperbesar */
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.form-group {
    margin-bottom: 20px; /* Jarak antar input diperbesar */
    text-align: left;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-card label {
    display: block;
    margin-bottom: 10px;
    font-size: 15px; /* Huruf label diperbesar */
    font-weight: 600;
    color: #333;
}

.form-card input {
    width: 100%;
    padding: 14px 16px; /* Kotak input diperbesar */
    border-radius: 12px;
    border: 1px solid #ccc;
    background: white;
    font-size: 14px; /* Huruf input diperbesar */
    box-sizing: border-box;
    transition: border-color 0.2s;
}

.form-card input:focus {
    outline: none;
    border-color: #9A6827;
}

/* READONLY FIELD */
.field-readonly {
    background: #efefef !important;
    cursor: not-allowed;
}

/* ================= SUBMIT & ACTION BUTTONS ================= */
.submit-outer-wrapper {
    width: 100%;
    max-width: 1000px;
    margin: 30px auto 0 auto;
}

.submit-wrapper {
    display: flex;
    justify-content: flex-end; 
    gap: 15px;
}

.submit-btn {
    background: #9A6827;
    color: white;
    border: none;
    padding: 14px 45px; /* Tombol lebih besar */
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.submit-btn:hover {
    background: #7a531f;
    transform: translateY(-2px);
}

.cancel-btn {
    background: #e4e4e4;
    color: #333;
    text-decoration: none;
    padding: 14px 35px; /* Tombol lebih besar */
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
}

.cancel-btn:hover {
    background: #d5d5d5;
}

/* ALERT MESSAGES */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 16px;
    border-radius: 12px;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto 20px auto;
    font-size: 15px;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 16px;
    border-radius: 12px;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto 20px auto;
    font-size: 15px;
}

.error-msg {
    color: #dc3545;
    font-size: 13px;
    margin-top: 6px;
    display: block;
}

/* RESPONSIVE JIKA LAYAR KECIL */
@media(max-width: 1050px) {
    .form-container {
        flex-direction: column;
        align-items: center;
    }
    .form-card {
        width: 100%;
        max-width: 550px;
    }
    .submit-outer-wrapper, .penugasan-header, .alert-success, .alert-error {
        max-width: 550px;
    }
}
</style>

<div class="penugasan-scope">
    <div class="penugasan-overlay">

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="penugasan-header">
            <i data-lucide="user-check"></i>
            <div class="header-text">
                <h2>Edit Penugasan Relawan</h2>
                <p>Lengkapi data untuk memperbarui penugasan relawan</p>
            </div>
        </div>

        <div class="form-wrapper">
            <form action="{{ route('admin.penugasan.update', $penugasan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-container">
                    
                    <div class="form-card">
                        <div class="form-group">
                            <label>ID Penugasan</label>
                            <input type="text" name="id_penugasan" value="{{ old('id_penugasan', $penugasan->id_penugasan) }}" class="field-readonly" readonly>
                        </div>

                        <div class="form-group">
                            <label>ID Donasi</label>
                            <input type="text" name="id_donasi" value="{{ old('id_donasi', $penugasan->id_donasi) }}" class="field-readonly" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Donatur</label>
                            <input type="text" name="nama_donatur" value="{{ old('nama_donatur', $penugasan->nama_donatur) }}" placeholder="Masukkan Nama Donatur">
                            @error('nama_donatur')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nama Relawan</label>
                            <input type="text" name="relawan" value="{{ old('relawan', $penugasan->relawan) }}" placeholder="Masukkan Nama Relawan">
                            @error('relawan')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-card">
                        <div class="form-group">
                            <label>Lokasi Pengambilan</label>
                            <input type="text" name="lokasi_pengambilan" value="{{ old('lokasi_pengambilan', $penugasan->lokasi_pengambilan) }}" placeholder="Masukkan Lokasi">
                            @error('lokasi_pengambilan')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Lokasi Pengantaran</label>
                            <input type="text" name="lokasi_pengantaran" value="{{ old('lokasi_pengantaran', $penugasan->lokasi_pengantaran) }}" placeholder="Masukkan Lokasi">
                            @error('lokasi_pengantaran')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Penugasan</label>
                            <input type="datetime-local" name="tanggal_penugasan" value="{{ old('tanggal_penugasan', \Carbon\Carbon::parse($penugasan->tanggal_penugasan)->format('Y-m-d\TH:i')) }}">
                            @error('tanggal_penugasan')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="submit-outer-wrapper">
                    <div class="submit-wrapper">
                        <a href="{{ route('admin.penugasan.index') }}" class="cancel-btn">
                            Batal
                        </a>
                        <button type="submit" class="submit-btn">
                            Update
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();
</script>

@endsection