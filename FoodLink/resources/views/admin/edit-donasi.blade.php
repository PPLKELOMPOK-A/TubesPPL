@extends('layouts.app')

@section('title', 'Edit Kegiatan Donasi - Admin FoodLink')

@section('content')
<style>
    /* Layout Utama */
    .main-content-canvas { padding: 40px 50px; background: #FFF9EE; min-height: 100vh; width: 100%; }

    /* PERBAIKAN: Header Area Jarak Atas & Samping */
    .container-form-header { margin-top: 10px; margin-bottom: 24px; display: flex; flex-direction: column; gap: 8px; }

    /* Header & Typography */
    .nav-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #4E453D; margin-bottom: 4px; }
    .nav-breadcrumb .active { font-weight: 700; color: #32220D; }
    
    .heading-2 h1 { font-family: 'Manrope', sans-serif; font-weight: 900; font-size: 30px; color: #32220D; margin-bottom: 4px; letter-spacing: -0.5px; }
    .header-text { font-family: 'Manrope', sans-serif; font-size: 16px; color: #4E453D; margin-bottom: 12px; }

    /* Form Container */
    .form-container { background: #FFFFFF; padding: 40px; border-radius: 8px; box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05); }
    .form-grid { display: flex; gap: 48px; }
    
    /* Grid Columns */
    .left-column { flex: 0.8; display: flex; flex-direction: column; gap: 16px; }
    .right-column { flex: 1.2; display: flex; flex-direction: column; gap: 24px; }
    
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-row { display: flex; gap: 24px; }
    .form-row .form-group { flex: 1; }

    .label-uppercase { font-family: 'Manrope', sans-serif; font-weight: 900; font-size: 12px; letter-spacing: 1.2px; text-transform: uppercase; color: #4E453D; }

    /* Input Fields */
    input, select, textarea { 
        padding: 13px 16px; 
        border: 1px solid rgba(209, 196, 185, 0.4); 
        background: #FFFFFF; 
        font-size: 16px; 
        color: #32220D; 
        border-radius: 4px; 
        outline: none; 
        font-family: 'Manrope', sans-serif;
        transition: 0.2s;
        width: 100%;
    }
    input::placeholder, textarea::placeholder { color: #D1C4B9; }
    input:focus, select:focus, textarea:focus { border-color: #6B4F2A; }
    textarea { height: 122px; resize: vertical; }

    /* Input border merah khusus error tanggal lampau */
    input.is-invalid { border-color: #dc3545; background-color: #fff5f5; }

    /* Input Group for Icon */
    .input-icon-wrapper { position: relative; display: flex; align-items: center; }
    .input-icon-wrapper i { position: absolute; left: 16px; color: #4E453D; font-size: 16px; }
    .input-icon-wrapper input { width: 100%; padding-left: 44px; } 

    /* Custom Upload Box / Preview Box */
    .upload-area { 
        background: #FEF3D1; 
        border: 2px dashed rgba(209, 196, 185, 0.6); 
        height: 325px; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        border-radius: 8px; 
        cursor: pointer; 
        transition: 0.3s;
        overflow: hidden;
        position: relative;
    }
    .upload-area:hover { background: #faeab8; }
    .upload-label { text-align: center; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px; width: 100%; height: 100%; justify-content: center; }
    
    /* Image Preview Style */
    #img-preview { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }
    .upload-overlay-text { position: relative; z-index: 2; background: rgba(254, 243, 209, 0.9); padding: 8px 16px; border-radius: 4px; font-weight: 700; color: #32220D; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .input-hidden { display: none; }
    
    .upload-note { font-family: 'Manrope', sans-serif; font-size: 12px; color: #4E453D; line-height: 1.6; margin-top: 8px; }

    /* Action Bar */
    .action-bar { display: flex; justify-content: flex-end; align-items: center; padding-top: 32px; gap: 16px; border-top: 1px solid rgba(209, 196, 185, 0.2); margin-top: 32px; }
    .btn-cancel { background: none; border: none; font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 14px; color: #201B07; text-transform: uppercase; cursor: pointer; padding: 12px 32px; text-decoration: none; }
    .btn-submit { background: #4A3721; color: #FFFFFF; padding: 12px 32px; border: none; font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 14px; text-transform: uppercase; border-radius: 2px; box-shadow: 0px 4px 6px -4px rgba(50, 34, 13, 0.1); cursor: pointer; transition: 0.3s; text-decoration: none; }
    .btn-submit:hover { background: #32220D; }
    
    /* Style Tombol saat Terkunci (Disabled) */
    .btn-submit:disabled { background-color: #a8a096; color: #f0ebd9; cursor: not-allowed; opacity: 0.7; box-shadow: none; }

    /* Footer Cards */
    .contextual-footer { display: flex; gap: 24px; margin-top: 40px; }
    .info-card { flex: 1; background: #FEF3D1; padding: 32px; border-radius: 8px; display: flex; flex-direction: column; gap: 12px;}
    .info-header { display: flex; align-items: center; gap: 12px; color: #32220D; }
    .info-icon { background: #FFFFFF; width: 48px; height: 48px; border-radius: 4px; display: flex; align-items: center; justify-content: center; box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05); color: #32220D; font-size: 20px;}
    .info-title { font-family: 'Manrope', sans-serif; font-weight: 700; color: #32220D; font-size: 16px;}
    .info-body { font-family: 'Manrope', sans-serif; font-size: 14px; color: #4E453D; line-height: 1.6; }
</style>

<div class="main-content-canvas">
    <div class="container-form-header">
        
        <div class="heading-2">
            <h1>Edit Data Postingan Donasi</h1>
        </div>
        
        <p class="header-text">
            Perbarui detail informasi postingan donasi Anda.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('admin.donasi.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data" id="editKegiatanForm">
            @csrf
            <div class="form-grid">
                
                <div class="left-column">
                    <div class="form-group">
                        <label class="label-uppercase">FOTO KEGIATAN / BARANG</label>
                        <div class="upload-area" onclick="document.getElementById('file-input').click();">
                            <input type="file" id="file-input" name="foto" accept="image/*" class="input-hidden">
                            <label class="upload-label">
                                <img id="img-preview" src="{{ !empty($data->foto_kegiatan) ? asset('storage/' . $data->foto_kegiatan) : (!empty($data->foto) ? asset('storage/' . $data->foto) : 'https://via.placeholder.com/400x250/f5f5f5/cccccc?text=Foto+Donasi') }}" alt="Preview">
                                <span class="upload-overlay-text"><i class="fa-regular fa-pen-to-square"></i> Ubah Foto</span>
                            </label>
                        </div>
                        @error('foto') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
                        <p class="upload-note">Pastikan foto memiliki pencahayaan yang baik dan menunjukkan kondisi barang atau momen kegiatan secara jelas untuk proses verifikasi.</p>
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label class="label-uppercase">JUDUL DONASI</label>
                        <input type="text" name="judul" value="{{ old('judul', $data->judul_donasi ?? $data->judul) }}" required>
                        @error('judul') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label-uppercase">KATEGORI PENERIMA</label>
                            <select name="kategori" required>
                                <option value="Organisasi (Yayasan)" {{ old('kategori', $data->kategori_penerima ?? $data->kategori) == 'Organisasi (Yayasan)' ? 'selected' : '' }}>Organisasi (Yayasan)</option>
                                <option value="Kegiatan Keagamaan" {{ old('kategori', $data->kategori_penerima ?? $data->kategori) == 'Kegiatan Keagamaan' ? 'selected' : '' }}>Kegiatan Keagamaan</option>
                                <option value="Individu/Umum" {{ old('kategori', $data->kategori_penerima ?? $data->kategori) == 'Individu' || old('kategori', $data->kategori_penerima ?? $data->kategori) == 'Individu/Umum' ? 'selected' : '' }}>Individu/Umum</option>
                            </select>
                            @error('kategori') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="label-uppercase">TANGGAL KEGIATAN</label>
                            <input type="date" name="tanggal" id="tanggal_kegiatan" value="{{ old('tanggal', $data->tanggal_kegiatan ?? $data->tanggal) }}" required>
                            <span id="date-error-text" style="color: #dc3545; font-size: 12px; font-weight: 600; display: none;">
                                <i class="fa-solid fa-circle-exclamation"></i> Tanggal tidak boleh di masa lampau!
                            </span>
                            @error('tanggal') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">DESKRIPSI KEGIATAN</label>
                        <textarea name="deskripsi" required>{{ old('deskripsi', $data->deskripsi) }}</textarea>
                        @error('deskripsi') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">ALAMAT PENYALURAN</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-location-dot"></i>
                            <input type="text" name="alamat" value="{{ old('alamat', $data->alamat_penyaluran ?? $data->alamat) }}" required>
                        </div>
                        @error('alamat') <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('admin.donasi.detail', ['id' => $data->id]) }}" class="btn-cancel">BATALKAN</a>
                <button type="submit" id="btnSubmitKegiatan" class="btn-submit">SIMPAN PERUBAHAN</button>
            </div>
        </form>
    </div>

    <div class="contextual-footer">
        <div class="info-card">
            <div class="info-header">
                <div class="info-icon"><i class="fa-solid fa-shield-halved"></i></div>
            </div>
            <span class="info-title">Keamanan Terjamin</span>
            <p class="info-body">Seluruh data donatur dan penerima dilindungi dengan enkripsi tingkat tinggi sesuai standar keamanan data global.</p>
        </div>
        <div class="info-card">
            <div class="info-header">
                <div class="info-icon"><i class="fa-solid fa-bolt"></i></div>
            </div>
            <span class="info-title">Respon Cepat</span>
            <p class="info-body">Tim verifikasi kami akan meninjau setiap pengajuan donasi dalam waktu maksimal 30 menit setelah dikirimkan.</p>
        </div>
        <div class="info-card">
            <div class="info-header">
                <div class="info-icon"><i class="fa-solid fa-gear"></i></div>
            </div>
            <span class="info-title">Kurasi Presisi</span>
            <p class="info-body">Kami menerapkan standar keamanan pangan dan kualitas barang yang ketat untuk memastikan kebermanfaatan donasi.</p>
        </div>
    </div>
</div>

<script>
    // Live Preview Gambar
    const fileInput = document.getElementById('file-input');
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { 
                document.getElementById('img-preview').src = e.target.result; 
            }
            reader.readAsDataURL(file);
        }
    });

    // Validasi Tanggal Lampau
    document.addEventListener("DOMContentLoaded", function () {
        const inputTanggal = document.getElementById("tanggal_kegiatan");
        const txtError = document.getElementById("date-error-text");
        const btnSubmit = document.getElementById("btnSubmitKegiatan");
        const formKegiatan = document.getElementById("editKegiatanForm");

        const hariIni = new Date();
        const yyyy = hariIni.getFullYear();
        let mm = hariIni.getMonth() + 1; 
        let dd = hariIni.getDate();

        if (mm < 10) mm = '0' + mm;
        if (dd < 10) dd = '0' + dd;

        const formatHariIni = yyyy + '-' + mm + '-' + dd;
        inputTanggal.setAttribute('min', formatHariIni);

        function validasiTanggal() {
            const tanggalPilihan = inputTanggal.value;
            if (tanggalPilihan) {
                if (tanggalPilihan < formatHariIni) {
                    inputTanggal.classList.add("is-invalid");
                    txtError.style.display = "block";
                    btnSubmit.disabled = true;
                    return false;
                }
            }
            inputTanggal.classList.remove("is-invalid");
            txtError.style.display = "none";
            btnSubmit.disabled = false;
            return true;
        }

        inputTanggal.addEventListener("input", validasiTanggal);
        inputTanggal.addEventListener("change", validasiTanggal);

        formKegiatan.addEventListener("submit", function (e) {
            if (!validasiTanggal()) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection