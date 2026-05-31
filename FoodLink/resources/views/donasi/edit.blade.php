@extends('layouts.app')

@section('title', 'Edit Donasi Makanan - FoodLink')

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

    .label-uppercase { font-weight: 700; font-size: 14px; letter-spacing: 0.7px; text-transform: uppercase; color: #57432C; display: flex; justify-content: space-between; }
    .badge-locked { font-size: 10px; background: #f1f2f6; color: #a4b0be; padding: 2px 6px; border-radius: 4px; text-transform: none; font-weight: 600; }

    input, select, textarea { padding: 14px 16px; border: 1px solid rgba(209, 196, 185, 0.4); background: #FFFFFF; font-size: 16px; color: #4E453D; border-radius: 4px; outline: none; font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.3s;}
    textarea { height: 146px; resize: vertical; }

    /* Style untuk Input Readonly (Tidak bisa diedit) */
    input[readonly] { background-color: #fcfcfc; color: #999; cursor: not-allowed; border-color: #eee; }
    input[readonly]:focus { border-color: #eee; }

    /* Custom Upload Box */
    .upload-area { position: relative; overflow: hidden; background: rgba(254, 243, 209, 0.3); border: 2px dashed rgba(128, 117, 108, 0.4); height: 312px; display: flex; justify-content: center; align-items: center; border-radius: 8px; cursor: pointer; }
    .upload-label { position: relative; z-index: 2; text-align: center; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; width: 100%; background: rgba(255, 255, 255, 0.7); padding: 20px; border-radius: 8px; margin: 0 20px;}
    .upload-icon-container { font-size: 40px; color: #80756C; margin-bottom: 5px; }
    .upload-text { display: block; font-weight: 700; font-size: 16px; color: #32220D; }
    .upload-subtext { font-size: 12px; color: #4E453D; }
    .input-hidden { display: none; }

    /* Action Bar */
    .action-bar { display: flex; justify-content: flex-end; align-items: center; padding-top: 40px; gap: 24px; border-top: 1px solid rgba(209, 196, 185, 0.2); margin-top: 40px; }
    .btn-cancel { background: none; border: none; font-weight: 700; color: #4E453D; text-transform: uppercase; cursor: pointer; text-decoration: none;}
    .btn-submit { background: #4A3721; color: #FFFFFF; padding: 16px 40px; border: none; font-weight: 700; text-transform: uppercase; border-radius: 4px; box-shadow: 0px 10px 15px -3px rgba(74, 55, 33, 0.1); cursor: pointer; transition: 0.2s; }
    .btn-submit:hover { background: #32220d; }

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
            <span>Riwayat Donasi</span>
            <div class="breadcrumb-icon"></div>
            <span class="active">Edit Donasi</span>
        </nav>
        
        <div class="heading-2">
            <h1>Edit Detail Donasi</h1>
        </div>
        
        <p class="header-text">
            Ubah informasi kategori makanan, foto, atau deskripsi di bawah ini. Informasi identitas dan tujuan lokasi bersifat tetap dan tidak dapat diubah kembali.
        </p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('donasi.update', $donasi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                
                <div class="left-column">
                    <div class="form-group">
                        <label class="label-uppercase">DOKUMENTASI MAKANAN <span class="badge-locked" style="background:#f39c12; color:white;">Bisa Diedit</span></label>
                        <div class="upload-area">
                            @if($donasi->foto_makanan)
                                <img id="image-preview" src="{{ asset('storage/'.$donasi->foto_makanan) }}" style="position: absolute; width: 100%; height: 100%; object-fit: cover; opacity: 0.4; z-index: 1;">
                            @else
                                <img id="image-preview" style="position: absolute; width: 100%; height: 100%; object-fit: cover; opacity: 0.4; z-index: 1; display:none;">
                            @endif

                            <input type="file" name="foto_makanan" id="foto_makanan" class="input-hidden" accept="image/*" onchange="previewName(this)">
                            
                            <label for="foto_makanan" class="upload-label">
                                <div class="upload-icon-container">
                                    <i class="fa-solid fa-camera icon-upload"></i>
                                </div>
                                <span class="upload-text" id="file-name-text">Ganti Foto (Opsional)</span>
                                <span class="upload-subtext">JPG, PNG atau WEBP (Maks. 5MB)</span>
                            </label>
                        </div>
                        @error('foto_makanan') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">KATEGORI PENERIMA <span class="badge-locked"><i class="fa-solid fa-lock"></i> Terkunci</span></label>
                        <input type="text" name="kategori_penerima" value="{{ $donasi->kategori_penerima }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">KATEGORI WILAYAH <span class="badge-locked"><i class="fa-solid fa-lock"></i> Terkunci</span></label>
                        <input type="text" name="kategori_wilayah" value="{{ $donasi->kategori_wilayah }}" readonly>
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label class="label-uppercase">NAMA DONATUR / INSTANSI <span class="badge-locked"><i class="fa-solid fa-lock"></i> Terkunci</span></label>
                        <input type="text" name="nama_donatur" value="{{ $donasi->nama_donatur }}" readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label-uppercase">NO. TELP <span class="badge-locked"><i class="fa-solid fa-lock"></i></span></label>
                            <input type="text" name="no_telp" value="{{ $donasi->no_telp }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="label-uppercase">EMAIL <span class="badge-locked"><i class="fa-solid fa-lock"></i></span></label>
                            <input type="email" name="email" value="{{ $donasi->email }}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">LOKASI DROPBOX <span class="badge-locked"><i class="fa-solid fa-lock"></i> Terkunci</span></label>
                        <input type="text" name="lokasi_dropbox" value="{{ $donasi->lokasi_dropbox }}" readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="label-uppercase">KATEGORI MAKANAN</label>
                            <select name="kategori_makanan">
                                <option value="Makanan Berat" {{ $donasi->kategori_makanan == 'Makanan Berat' ? 'selected' : '' }}>Makanan Berat</option>
                                <option value="Sembako" {{ $donasi->kategori_makanan == 'Sembako' ? 'selected' : '' }}>Sembako</option>
                                <option value="Makanan Ringan / Roti" {{ $donasi->kategori_makanan == 'Makanan Ringan / Roti' ? 'selected' : '' }}>Makanan Ringan / Roti</option>
                                <option value="Lainnya" {{ (!in_array($donasi->kategori_makanan, ['Makanan Berat', 'Sembako', 'Makanan Ringan / Roti'])) ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori_makanan') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="label-uppercase">WAKTU LAYAK MAKAN</label>
                            <select name="waktu_layak">
                                <option value="< 6 Jam" {{ $donasi->waktu_layak == '< 6 Jam' ? 'selected' : '' }}>< 6 Jam</option>
                                <option value="6 - 12 Jam" {{ $donasi->waktu_layak == '6 - 12 Jam' ? 'selected' : '' }}>6 - 12 Jam</option>
                                <option value="> 12 Jam" {{ (!in_array($donasi->waktu_layak, ['< 6 Jam', '6 - 12 Jam'])) ? 'selected' : '' }}>> 12 Jam</option>
                            </select>
                            @error('waktu_layak') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="label-uppercase">DESKRIPSI KEGIATAN / CATATAN</label>
                        <textarea name="deskripsi" placeholder="Berikan deskripsi singkat mengenai asal makanan atau instruksi khusus penanganan..." required>{{ old('deskripsi', $donasi->deskripsi) }}</textarea>
                        @error('deskripsi') <span style="color: #dc3545; font-size: 12px; margin-top:-8px;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('riwayat-donasi.index') }}" class="btn-cancel">Batal Kembali</a>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <div class="contextual-footer">
        <div class="info-card">
            <div class="info-header">
                <i class="fa-solid fa-pen-to-square"></i>
                <span class="info-title">Aturan Perubahan</span>
            </div>
            <p class="info-body">Hanya deskripsi, detail makanan, dan dokumentasi yang dapat Anda perbarui jika status masih Pending.</p>
        </div>
        <div class="info-card">
            <div class="info-header">
                <i class="fa-solid fa-shield-halved"></i>
                <span class="info-title">Identitas Terkunci</span>
            </div>
            <p class="info-body">Data pribadi dan tujuan donasi dikunci demi keamanan logistik dan pelacakan data awal.</p>
        </div>
        <div class="info-card">
            <div class="info-header">
                <i class="fa-solid fa-clipboard-list"></i>
                <span class="info-title">Kurasi Ulang</span>
            </div>
            <p class="info-body">Setelah diedit, tim kurator kami akan melakukan peninjauan ulang berdasarkan data terbaru.</p>
        </div>
    </div>
</div>

<script>
    // Script untuk mengganti nama file dan preview gambar secara real-time
    function previewName(input) {
        if(input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('file-name-text').innerHTML = fileName;
            
            // Tampilkan Live Preview Gambar Transparan di Background
            var reader = new FileReader();
            reader.onload = function(e) {
                var imgPreview = document.getElementById('image-preview');
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection