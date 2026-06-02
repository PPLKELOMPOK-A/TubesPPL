@extends('layouts.app')

@section('title', 'Buat Donasi Baru - FoodLink')

@section('content')
<style>
    /* Layout & Canvas */
    .main-content-canvas { padding: 48px 64px; background: #FFF9EE; min-height: 100vh; }

    /* Breadcrumb */
    .nav-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #4E453D; margin-bottom: 16px; }
    .breadcrumb-icon { width: 4px; height: 6px; background: #4E453D; }
    .nav-breadcrumb .active { font-weight: 700; color: #32220D; }

    /* Typography */
    .heading-2 h1 { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 36px; color: #32220D; margin-bottom: 12px; }
    .header-text { font-size: 16px; color: #4E453D; max-width: 672px; margin-bottom: 40px; }

    /* Form Styling */
    .form-container { background: #FFFFFF; padding: 40px; border-radius: 8px; box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05); }
    .form-grid { display: flex; gap: 48px; }
    .left-column { flex: 1; display: flex; flex-direction: column; gap: 32px; }
    .right-column { flex: 1.5; display: flex; flex-direction: column; gap: 32px; }
    .form-group { display: flex; flex-direction: column; gap: 12px; }
    .form-row { display: flex; gap: 24px; }
    .form-row .form-group { flex: 1; }

    .label-uppercase { font-weight: 700; font-size: 14px; letter-spacing: 0.7px; text-transform: uppercase; color: #57432C; }

    input, select, textarea { padding: 14px 16px; border: 1px solid rgba(209, 196, 185, 0.4); background: #FFFFFF; font-size: 16px; color: #4E453D; border-radius: 4px; outline: none; font-family: 'Plus Jakarta Sans', sans-serif;}
    textarea { height: 146px; resize: vertical; }

    /* Custom Upload Box */
    .upload-area { background: rgba(254, 243, 209, 0.3); border: 2px dashed rgba(128, 117, 108, 0.4); height: 312px; display: flex; justify-content: center; align-items: center; border-radius: 8px; cursor: pointer; }
    .upload-label { text-align: center; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; width: 100%;}
    .upload-icon-container { font-size: 40px; color: #80756C; margin-bottom: 5px; }
    .upload-text { display: block; font-weight: 700; font-size: 16px; color: #32220D; }
    .upload-subtext { font-size: 12px; color: #4E453D; }
    .input-hidden { display: none; }

    /* Action Bar */
    .action-bar { display: flex; justify-content: flex-end; align-items: center; padding-top: 40px; gap: 24px; border-top: 1px solid rgba(209, 196, 185, 0.2); margin-top: 40px; }
    .btn-cancel { background: none; border: none; font-weight: 700; color: #4E453D; text-transform: uppercase; cursor: pointer; }
    .btn-submit { background: #4A3721; color: #FFFFFF; padding: 16px 40px; border: none; font-weight: 700; text-transform: uppercase; border-radius: 4px; box-shadow: 0px 10px 15px -3px rgba(74, 55, 33, 0.1); cursor: pointer; }

    /* Footer Cards */
    .contextual-footer { display: flex; gap: 24px; margin-top: 48px; }
    .info-card { flex: 1; background: #FEF3D1; padding: 24px; border-radius: 8px; display: flex; flex-direction: column; gap: 8px;}
    .info-header { display: flex; align-items: center; gap: 12px; color: #4A3721; }
    .info-title { font-weight: 700; color: #32220D; font-size: 16px;}
    .info-body { font-size: 14px; color: #4E453D; line-height: 1.6; margin-top: 4px;}
</style>

<div class="main-content-canvas">
    <div class="container-form-header">
        <nav class="nav-breadcrumb">
            <span>Beranda</span>
            <div class="breadcrumb-icon"></div>
            <span class="active">Buat Donasi Baru</span>
        </nav>
        
        <div class="heading-2">
            <h1>Buat Donasi Baru</h1>
        </div>
        
        <p class="header-text">
            Lengkapi detail informasi donasi makanan di bawah ini untuk memulai proses kurasi dan distribusi ke penerima manfaat.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('donasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="left-column">
                    <div class="form-group">
                        <label class="label-uppercase">DOKUMENTASI MAKANAN</label>
                        <div class="upload-area">
                            <input type="file" name="foto_makanan" id="foto_makanan" class="input-hidden" onchange="previewName(this)">
                            <label for="foto_makanan" class="upload-label">
                                <div class="upload-icon-container">
                                    <i class="fa-solid fa-camera icon-upload"></i>
                                </div>
                                <span class="upload-text" id="file-name-text">Upload Foto Makanan</span>
                                <span class="upload-subtext">JPG, PNG atau WEBP (Maks. 5MB)</span>
                            </label>
                        </div>
                        @error('foto_makanan') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">KATEGORI PENERIMA</label>
                        <select name="kategori_penerima">
                            <option value="">Pilih Kategori Penerima</option>
                            <option value="Panti Asuhan">Panti Asuhan</option>
                            <option value="Tunawisma">Tunawisma</option>
                            <option value="Korban Bencana">Korban Bencana</option>
                        </select>
                        @error('kategori_penerima') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">KATEGORI WILAYAH</label>
                        <select name="kategori_wilayah">
                            <option value="">Pilih Wilayah</option>
                            @foreach($dropboxes as $box)
                                @php
                                    $bagianAlamat = explode(',', $box->lokasi);
                                    $kota = "Wilayah Lain"; 
                                    foreach($bagianAlamat as $bagian) {
                                        if (str_contains(trim($bagian), 'Kota')) {
                                            $kota = trim($bagian);
                                            break;
                                        }
                                    }
                                @endphp
                                <option value="{{ $kota }}" {{ old('kategori_wilayah') == $kota ? 'selected' : '' }}>
                                    {{ $kota }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_wilayah') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label class="label-uppercase">NAMA DONATUR / INSTANSI</label>
                        <input type="text" name="nama_donatur" value="{{ old('nama_donatur') }}" placeholder="Masukkan nama lengkap">
                        @error('nama_donatur') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label-uppercase">NO. TELP</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="0812-xxxx-xxxx">
                            @error('no_telp') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="label-uppercase">EMAIL</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com">
                            @error('email') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">LOKASI DROPBOX</label>
                        <select name="lokasi_dropbox">
                            <option value="">Pilih Lokasi Dropbox</option>
                            @foreach($dropboxes as $box)
                                @php $namaJalan = explode(',', $box->lokasi)[0]; @endphp
                                <option value="{{ $box->nama }} - {{ $namaJalan }}" 
                                    {{ old('lokasi_dropbox') == ($box->nama . ' - ' . $namaJalan) ? 'selected' : '' }}>
                                    {{ $box->nama }} - {{ $namaJalan }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi_dropbox') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="label-uppercase">KATEGORI MAKANAN</label>
                            <select name="kategori_makanan">
                                <option value="">Jenis Makanan</option>
                                <option value="Makanan Berat">Makanan Berat</option>
                                <option value="Sembako">Sembako</option>
                            </select>
                            @error('kategori_makanan') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="label-uppercase">WAKTU LAYAK MAKAN</label>
                            <select name="waktu_layak">
                                <option value="">Pilih Durasi</option>
                                <option value="< 6 Jam">< 6 Jam</option>
                                <option value="6 - 12 Jam">6 - 12 Jam</option>
                            </select>
                            @error('waktu_layak') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">DESKRIPSI KEGIATAN / CATATAN</label>
                        <textarea name="deskripsi" placeholder="Berikan deskripsi singkat...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ url('/dashboard') }}" class="btn-cancel" style="text-decoration: none; padding: 12px 24px;">BATALKAN</a>
                <button type="submit" class="btn-submit">SIMPAN DONASI</button>
            </div>
        </form>
    </div>
    
    <div class="contextual-footer">
        <div class="info-card">
            <div class="info-header">
                <i class="fa-solid fa-shield-halved"></i>
                <span class="info-title">Keamanan Terjamin</span>
            </div>
            <p class="info-body">Seluruh data donatur dilindungi dan hanya digunakan untuk keperluan koordinasi logistik.</p>
        </div>
        <div class="info-card">
            <div class="info-header">
                <i class="fa-solid fa-bolt"></i>
                <span class="info-title">Respon Cepat</span>
            </div>
            <p class="info-body">Tim kurasi kami akan melakukan verifikasi maksimal 30 menit setelah data dikirim.</p>
        </div>
        <div class="info-card">
            <div class="info-header">
                <i class="fa-solid fa-clipboard-list"></i>
                <span class="info-title">Kurasi Presisi</span>
            </div>
            <p class="info-body">Setiap donasi diklasifikasikan berdasarkan standar kelayakan pangan yang ketat.</p>
        </div>
    </div>
</div>

<script>
    function previewName(input) {
        if(input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('file-name-text').innerHTML = fileName;
        }
    }
</script>
@endsection