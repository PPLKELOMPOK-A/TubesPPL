@extends('layouts.app')

@section('title', 'Foodlink Admin - Edit Donasi')

@section('content')
<style>
    /* Hanya mempertahankan style yang dibutuhkan untuk area form */
    .admin-edit-container { 
        padding: 30px 50px; 
        max-width: 1000px; 
        width: 100%; 
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin: 20px auto; /* Ketengah */
    }

    .form-group { margin-bottom: 25px; max-width: 700px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #444; margin-bottom: 8px; }
    
    input[type="text"], input[type="date"], select, textarea {
        width: 100%; padding: 12px 15px; border: 1px solid #D0D0D0; border-radius: 8px; font-size: 14px; color: #555; outline: none; background: white; font-family: inherit;
    }

    /* TANGGAL CUSTOM */
    .date-container { position: relative; }
    #date-display { cursor: pointer; background: white; }
    #real-date { position: absolute; opacity: 0; width: 100%; height: 100%; left: 0; top: 0; cursor: pointer; z-index: 2; }
    .calendar-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #888; pointer-events: none; z-index: 1; }

    /* Image Edit Section */
    .image-container { display: flex; align-items: flex-start; gap: 20px; margin-top: 10px; max-width: 700px; }
    .preview-box { width: 400px; height: 250px; border-radius: 12px; border: 1px solid #eee; overflow: hidden; background: #f5f5f5; }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .btn-edit-foto { background: white; border: 1px solid #D0D0D0; padding: 10px 15px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; color: #444; transition: 0.2s;}
    .btn-edit-foto:hover { background: #f9f9f9; }

    /* Actions */
    .footer-actions { 
        display: flex; 
        justify-content: flex-start; 
        gap: 15px; 
        margin-top: 40px; 
        padding-bottom: 20px;
        max-width: 700px;
    }
    .btn-base { padding: 12px 40px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; transition: 0.3s; min-width: 130px; display: inline-flex; align-items: center; justify-content: center; }
    .btn-kembali { background: white; color: #444; border: 1px solid #D0D0D0; }
    .btn-kembali:hover { background: #f5f5f5; }
    .btn-simpan { background-color: #6B4F2A; color: white; border: none; }
    .btn-simpan:hover { background-color: #563e21; }

    #file-input { display: none; }
</style>

<div class="main-content-canvas">
    <div class="admin-edit-container">
        
        <h2 style="font-weight: 800; font-size: 24px; color: #333; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #eee;">Edit Data Kegiatan Donasi</h2>

        <form action="{{ route('admin.donasi.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" value="{{ $data->judul_donasi ?? $data->judul }}">
            </div>

            <div class="form-group">
                <label>Kategori Penerima</label>
                <select name="kategori">
                    <option value="Organisasi (Yayasan)" {{ ($data->kategori_penerima ?? $data->kategori) == 'Organisasi (Yayasan)' ? 'selected' : '' }}>Organisasi (Yayasan)</option>
                    <option value="Kegiatan Keagamaan" {{ ($data->kategori_penerima ?? $data->kategori) == 'Kegiatan Keagamaan' ? 'selected' : '' }}>Kegiatan Keagamaan</option>
                    <option value="Individu" {{ ($data->kategori_penerima ?? $data->kategori) == 'Individu' || ($data->kategori_penerima ?? $data->kategori) == 'Individu/Umum' ? 'selected' : '' }}>Individu/Umum</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <div class="date-container">
                    <input type="date" name="tanggal" id="real-date" onchange="updateDateDisplay(this.value)" value="{{ $data->tanggal_kegiatan ?? $data->tanggal }}">
                    <input type="text" id="date-display" value="{{ \Carbon\Carbon::parse($data->tanggal_kegiatan ?? $data->tanggal)->translatedFormat('l, d F Y') }}" readonly>
                    <i class="fa-regular fa-calendar-days calendar-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Foto Dokumentasi</label>
                <div class="image-container">
                    <div class="preview-box">
                        <img id="img-preview" src="{{ !empty($data->foto_kegiatan) ? asset('storage/' . $data->foto_kegiatan) : (!empty($data->foto) ? asset('storage/' . $data->foto) : 'https://via.placeholder.com/400x250/f5f5f5/cccccc?text=Foto+Donasi') }}" alt="Preview">
                    </div>
                    <div>
                        <input type="file" id="file-input" name="foto" accept="image/*">
                        <button type="button" class="btn-edit-foto" onclick="document.getElementById('file-input').click();">
                            <i class="fa-regular fa-pen-to-square"></i> Edit Foto
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Kegiatan</label>
                <textarea name="deskripsi" rows="4">{{ $data->deskripsi }}</textarea>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="{{ $data->alamat_penyaluran ?? $data->alamat }}">
            </div>

            <div class="footer-actions">
                <a href="{{ route('admin.donasi.detail', ['id' => $data->id]) }}" class="btn-base btn-kembali">Kembali</a>
                <button type="submit" class="btn-simpan btn-base">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('file-input');
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { document.getElementById('img-preview').src = e.target.result; }
            reader.readAsDataURL(file);
        }
    });

    function updateDateDisplay(val) {
        if(!val) return;
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const d = new Date(val);
        document.getElementById('date-display').value = d.toLocaleDateString('id-ID', options);
    }
</script>
@endsection