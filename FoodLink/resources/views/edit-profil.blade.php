@extends('layouts.app')

@section('title', 'Foodlink - Edit Profil')

@section('content')
<style>
    /* --- CONTAINER EDIT PROFIL --- */
    .container-profil { padding: 40px 50px; max-width: 900px; width: 100%; margin-left: 0; }

    .profile-header-title { display: flex; align-items: center; gap: 20px; margin-bottom: 45px; }
    .back-nav { color: #111; text-decoration: none; font-size: 20px; transition: 0.2s; }
    .back-nav:hover { color: #6B4F2A; transform: translateX(-3px); }
    .page-title { font-size: 24px; font-weight: 700; color: #111; }

    /* Hero Edit Foto */
    .profile-hero { display: flex; align-items: center; gap: 35px; margin-bottom: 50px; }
    .user-avatar-large { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    
    .btn-upload-foto { display: inline-flex; align-items: center; gap: 10px; background: #ffffff; border: 1px solid #dcdcdc; color: #111; font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.2s; }
    .btn-upload-foto i { font-size: 14px; }
    .btn-upload-foto:hover { background: #fafafa; border-color: #ccc; }

    /* Input Form Grid */
    .profile-details-list { display: flex; flex-direction: column; gap: 20px; max-width: 600px; margin-bottom: 50px; }
    .detail-row { display: grid; grid-template-columns: 150px 1fr; align-items: center; }
    .detail-label { font-size: 14px; font-weight: 500; color: #444; }
    
    .form-input { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; color: #111; font-weight: 600; outline: none; transition: 0.2s; background: #fafafa; }
    .form-input:focus { border-color: #6B4F2A; background: #ffffff; }
    
    /* Input border merah saat error */
    .form-input.is-invalid { border-color: #e53e3e; background: #fff5f5; }

    /* Style Pesan Error Merah */
    .error-text { display: none; color: #e53e3e; font-size: 12px; font-weight: 600; margin-top: 5px; grid-column: 2; }

    /* Tombol Aksi Bawah */
    .action-buttons { display: flex; gap: 15px; }
    .btn-batal { background-color: #ffffff; color: #444; border: 1px solid #ddd; padding: 12px 30px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; display: inline-block; }
    .btn-batal:hover { background-color: #f5f5f5; }
    
    .btn-simpan { background-color: #5C4322; color: #ffffff; border: none; padding: 12px 45px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s; }
    .btn-simpan:hover { background-color: #4a351a; }
    
    /* Style tombol saat terkunci (disabled) */
    .btn-simpan:disabled { background-color: #a8a096; color: #f0ebd9; cursor: not-allowed; opacity: 0.7; }
</style>

<div class="main-content-canvas">
    <div class="container-profil">
        <div class="profile-header-title">
            <a href="{{ route('profil.index') }}" class="back-nav"><i class="fa-solid fa-arrow-left"></i></a>
            <h1 class="page-title">Edit Profil</h1>
        </div>

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" id="editProfilForm">
            @csrf
            
            <div class="profile-hero">
                @if(!empty(Auth::user()->foto_profil))
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" id="avatarPreview" class="user-avatar-large" alt="Foto">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=2C3E50&color=fff&size=200" id="avatarPreview" class="user-avatar-large" alt="Foto">
                @endif
                
                <div class="hero-text">
                    <button type="button" class="btn-upload-foto" onclick="document.getElementById('foto_profil').click();">
                        <i class="fa-solid fa-camera"></i> Ubah Foto
                    </button>
                    <input type="file" name="foto_profil" id="foto_profil" accept="image/*" style="display:none;">
                </div>
            </div>

            <div class="profile-details-list">
                <div class="detail-row">
                    <div class="detail-label">Nama</div>
                    <input type="text" name="name" class="form-input" value="{{ Auth::user()->name ?? '' }}" placeholder="Masukkan Nama" required>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">NIK</div>
                    <input type="text" name="nik" id="nikInput" class="form-input" value="{{ Auth::user()->nik ?? '' }}" placeholder="Masukkan NIK">
                    <div id="nikError" class="error-text"><i class="fa-solid fa-triangle-exclamation"></i> NIK hanya boleh berisi angka!</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <input type="email" name="email" class="form-input" value="{{ Auth::user()->email ?? '' }}" placeholder="Masukkan Email" required>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Telepon</div>
                    <input type="text" name="telepon" id="teleponInput" class="form-input" value="{{ Auth::user()->telepon ?? '' }}" placeholder="Masukkan No. Telepon">
                    <div id="teleponError" class="error-text"><i class="fa-solid fa-triangle-exclamation"></i> No. Telepon hanya boleh berisi angka!</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Lokasi</div>
                    <input type="text" name="lokasi" class="form-input" value="{{ Auth::user()->lokasi ?? '' }}" placeholder="Masukkan Lokasi Anda">
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Alamat</div>
                    <input type="text" name="alamat" class="form-input" value="{{ Auth::user()->alamat ?? '' }}" placeholder="Masukkan Alamat Anda">
                </div>
            </div>

            <div class="action-buttons">
                <button type="submit" id="btnSimpan" class="btn-simpan">Simpan Perubahan</button>
                <a href="{{ route('profil.index') }}" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview Foto Profil
    const fotoInput = document.getElementById('foto_profil');
    const avatarPreview = document.getElementById('avatarPreview');

    fotoInput.onchange = evt => {
        const [file] = fotoInput.files;
        if (file) {
            avatarPreview.src = URL.createObjectURL(file);
        }
    }

    // REAL-TIME VALIDATION
    const nikInput = document.getElementById('nikInput');
    const teleponInput = document.getElementById('teleponInput');
    
    const nikError = document.getElementById('nikError');
    const teleponError = document.getElementById('teleponError');
    const btnSimpan = document.getElementById('btnSimpan');

    function checkValidation() {
        const regexAngka = /^[0-9]*$/;

        let statusNikValid = regexAngka.test(nikInput.value);
        let statusTeleponValid = regexAngka.test(teleponInput.value);

        // 1. Validasi Kolom NIK
        if (!statusNikValid) {
            nikInput.classList.add('is-invalid');
            nikError.style.display = 'block';
        } else {
            nikInput.classList.remove('is-invalid');
            nikError.style.display = 'none';
        }

        // 2. Validasi Kolom Telepon
        if (!statusTeleponValid) {
            teleponInput.classList.add('is-invalid');
            teleponError.style.display = 'block';
        } else {
            teleponInput.classList.remove('is-invalid');
            teleponError.style.display = 'none';
        }

        // 3. Kunci atau Buka Tombol Simpan
        if (!statusNikValid || !statusTeleponValid) {
            btnSimpan.disabled = true;
        } else {
            btnSimpan.disabled = false;
        }
    }

    nikInput.addEventListener('input', checkValidation);
    teleponInput.addEventListener('input', checkValidation);
</script>
@endsection