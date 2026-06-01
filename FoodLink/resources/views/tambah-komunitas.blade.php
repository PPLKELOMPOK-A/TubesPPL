@extends('layouts.app')

@section('content')

<style>
    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: white;
        color: #6B4F2A;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .btn-kembali:hover {
        background-color: #6B4F2A;
        color: white;
    }
</style>

<div class="main-content-canvas" style="padding: 30px 40px 40px 40px;">

    <div style="max-width: 800px; margin: 0 auto;">

        <div style="margin-bottom: 24px;">
            <a href="{{ route('komunitas.index') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div style="
            background: white;
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.05);
        ">
            <h2 style="font-size: 28px; font-weight: 700; color: #333; margin-bottom: 8px;">Buat Postingan Baru</h2>
            <p style="color: #777; margin-bottom: 30px;">Bagikan cerita, inspirasi, atau kebingunganmu seputar donasi makanan.</p>

            <form action="{{ route('komunitas.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 600; color: #555; margin-bottom: 10px;">Judul Postingan</label>
                    <input type="text" name="judul" required placeholder="Contoh: Pengalaman pertama ikut donasi..."
                           style="
                                width: 100%;
                                padding: 16px 20px;
                                border: 1px solid #ddd;
                                border-radius: 14px;
                                background: #fafafa;
                                font-size: 16px;
                                outline: none;
                                transition: 0.2s;
                           ">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 600; color: #555; margin-bottom: 10px;">Kategori Topik</label>
                    <select name="kategori" required style="
                            width: 100%;
                            padding: 16px 20px;
                            border: 1px solid #ddd;
                            border-radius: 14px;
                            background: #fafafa;
                            font-size: 16px;
                            outline: none;
                            color: #444;
                    ">
                        <option value="" disabled selected>-- Pilih Kategori Topik --</option>
                        <option value="Inspirasi">Inspirasi</option>
                        <option value="Donasi">Donasi</option>
                        <option value="Relawan">Relawan</option>
                        <option value="Pertanyaan">Pertanyaan</option>
                    </select>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-weight: 600; color: #555; margin-bottom: 10px;">Isi Cerita</label>
                    <textarea name="isi" required rows="6" placeholder="Ceritakan pengalamanmu di sini secara detail..."
                              style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 1px solid #ddd;
                                    border-radius: 14px;
                                    background: #fafafa;
                                    font-size: 16px;
                                    outline: none;
                                    resize: vertical;
                              "></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" style="
                        background: #6B4F2A;
                        color: white;
                        padding: 16px 36px;
                        border: none;
                        border-radius: 14px;
                        font-size: 16px;
                        font-weight: 600;
                        cursor: pointer;
                        box-shadow: 0 4px 12px rgba(107,79,42,0.2);
                        transition: 0.3s;
                    ">
                        Bagikan Sekarang <i class="fas fa-paper-plane" style="margin-left: 8px;"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection