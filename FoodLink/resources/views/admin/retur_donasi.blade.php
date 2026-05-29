@extends('layouts.app')

@section('content')
<style>
   .main-body h1,
.main-body h2 {
    margin-top: 0;
}

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 30px 35px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .form-title {
        font-size: 15px;
        font-weight: bold;
        color: #5a3e1b;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eee;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 30px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    label {
        font-size: 12px;
        color: #666;
        font-weight: bold;
        text-transform: uppercase;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 7px;
        border: 1px solid #ccc;
        font-size: 13px;
    }

    .upload-box {
    width: 100%;
    min-height: 450px;
    border-radius: 12px;
    background: #f0ede8;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
    border: 2px dashed #c9b99a;
    cursor: pointer;
    padding: 10px;
}

  .upload-box img {
    width: 100%;
    height: auto;
    max-height: 700px;
    object-fit: contain;
    border-radius: 10px;
    display: none;
    z-index: 10;
}

    .submit-btn {
        padding: 10px 28px;
        background: #5a3e1b;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
</style>

<div class="main-content-canvas">

    <h1 style="
        font-size:28px;
        font-weight:700;
        color:#2C1A0E;
        margin-bottom:25px;
    ">
        Retur Donasi
    </h1>

    <div class="main-body">

    {{-- SUCCESS ALERT --}}
    @if(session('success'))

        <div style="
            background: #d1e7dd;
            color: #0f5132;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #badbcc;
            font-size: 14px;
            font-weight: 500;
        ">
            {{ session('success') }}
        </div>

    @endif


    {{-- ERROR ALERT --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

            <div class="form-card">

                <div class="form-title">
                    Form Pengajuan Retur Donasi
                </div>

                <form action="{{ route('retur.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="form-grid">

                        <div class="form-group">
                            <label>ID Donasi</label>

                            <input
                                type="text"
                                name="id_donasi"
                                placeholder="Masukkan ID Donasi"
                                value="{{ old('id_donasi') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label>Nama Donasi</label>

                            <input
                                type="text"
                                name="nama_makanan"
                                placeholder="Masukkan Nama Makanan"
                                value="{{ old('nama_makanan') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label>Jumlah yang Diretur</label>

                            <input
                                type="number"
                                name="jumlah"
                                placeholder="Masukkan jumlah"
                                value="{{ old('jumlah') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label>Kategori Doanasi</label>

                            <select name="kategori">
                                <option value="">Pilih kategori</option>

                                <option {{ old('kategori') == 'Makanan Berat' ? 'selected' : '' }}>
                                    Makanan Berat
                                </option>

                                <option {{ old('kategori') == 'Makanan Ringan' ? 'selected' : '' }}>
                                    Makanan Ringan
                                </option>

                                <option {{ old('kategori') == 'Minuman' ? 'selected' : '' }}>
                                    Minuman
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Alasan Retur</label>

                            <input
                                type="text"
                                name="alasan"
                                placeholder="Contoh: Tidak Sesuai"
                                value="{{ old('alasan') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label>Tanggal Pengajuan</label>

                            <input
                                type="date"
                                name="tanggal_pengajuan"
                                value="{{ old('tanggal_pengajuan') }}"
                            >
                        </div>

                        <div class="form-group full">
                            <label>Deskripsi Retur</label>

                            <textarea
                                name="deskripsi"
                                placeholder="Masukkan alasan dikembalikan"
                            >{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="form-group full">

                            <label>Upload Bukti</label>

                            <input
    type="file"
    name="bukti"
    id="buktiInput"
    accept="image/*"
    onchange="previewImage(event)"
    style="display: none;"
>

                            <div
    class="upload-box"
    onclick="document.getElementById('buktiInput').click()"
>

    <i data-lucide="upload-cloud" id="uploadIcon"></i>

    <img
        id="preview"
        src=""
        alt="Preview"
    >

</div>

                        </div>

                    </div>

                  <div style="
    display: flex;
    justify-content: flex-end;
    margin-top: 24px;
">

                        <!-- <a href="{{ route('retur.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a> -->

                       <button type="submit" class="submit-btn" style="
    padding: 12px 28px;
    border-radius: 8px;
">
    Retur
</button>

                    </div>

                </form>

            </div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();

    function previewImage(event) {

        const input = event.target;

        const preview = document.getElementById('preview');

        const icon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {

            const reader = new FileReader();

            reader.onload = function(e) {

                preview.src = e.target.result;

                preview.style.display = 'block';

                icon.style.display = 'none';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection