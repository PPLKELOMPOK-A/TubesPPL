{{-- Sesuaikan 'layouts.app' dengan lokasi dan nama file master layout-mu --}}
{{-- Misalnya jika file masternya bernama app.blade.php di dalam folder resources/views/layouts --}}
@extends('layouts.app') 

@section('title', 'Foodlink Admin - Tambah Donasi')

@section('content')
<style>
    /* --- STYLES KHUSUS UNTUK FORM TAMBAH DONASI --- */
    .container { max-width: 800px; width: 100%; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #333; }
    .form-group input[type="text"],
    .form-group select,
    .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; color: #333; transition: 0.2s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #6B4F2A; }
    
    .date-container { position: relative; display: flex; align-items: center; }
    .date-container input[type="date"] { position: absolute; left: 0; top: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .date-container input[type="text"] { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background-color: #fff; pointer-events: none; }
    .calendar-icon { position: absolute; left: 15px; color: #aaa; pointer-events: none; }

    .image-container { border: 2px dashed #ddd; border-radius: 12px; padding: 20px; text-align: center; background: #fff; }
    .preview-box img { max-width: 100%; max-height: 250px; border-radius: 8px; margin-bottom: 15px; object-fit: cover; }
    #file-input { display: none; }
    .btn-edit-foto { padding: 10px 20px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; color: #444; }
    
    .footer-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; padding-bottom: 50px;}
    .btn-base { padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.2s; border: none; }
    .btn-kembali { background: #f5f5f5; color: #555; border: 1px solid #ddd; }
    .btn-kembali:hover { background: #eaeaea; }
    .btn-simpan { background: #6B4F2A; color: white; }
    .btn-simpan:hover { background: #5a4122; }
</style>

<div class="main-content-canvas">
    <div class="container">
        <h2 style="color: #6B4F2A; margin-bottom: 20px;">Buat Posting Donasi</h2>
        
        <form action="{{ route('admin.donasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" required placeholder="Masukkan judul donasi">
            </div>

            <div class="form-group">
                <label>Kategori Penerima</label>
                <select name="kategori" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Organisasi (Yayasan)">Organisasi (Yayasan)</option>
                    <option value="Kegiatan Keagamaan">Kegiatan Keagamaan</option>
                    <option value="Individu">Individu</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <div class="date-container">
                    <input type="date" name="tanggal" id="real-date" onchange="updateDateDisplay(this.value)" required>
                    <input type="text" id="date-display" placeholder="Pilih Tanggal" readonly>
                    <i class="fa-regular fa-calendar-days calendar-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Unggah Foto</label>
                <div class="image-container">
                    <div class="preview-box">
                        <img id="img-preview" src="https://via.placeholder.com/400x250/f5f5f5/cccccc?text=Pratinjau+Foto" alt="Preview">
                    </div>
                    <input type="file" id="file-input" name="foto" accept="image/*">
                    <button type="button" class="btn-edit-foto" onclick="document.getElementById('file-input').click();">
                        <i class="fa-regular fa-image"></i> Pilih Foto
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Kegiatan</label>
                <textarea name="deskripsi" rows="4" required placeholder="Jelaskan detail makanan yang didonasikan..."></textarea>
            </div>

            <div class="form-group">
                <label>Alamat Pengambilan</label>
                <input type="text" name="alamat" required placeholder="Masukkan alamat lengkap">
            </div>

            <div class="footer-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-base btn-kembali">Batal</a>
                <button type="submit" class="btn-simpan btn-base">Simpan Postingan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush