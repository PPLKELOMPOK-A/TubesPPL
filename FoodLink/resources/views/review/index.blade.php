@extends('layouts.app')

@section('title', 'Rating & Review')

@section('content')

<style>

.main-content{
    padding: 20px 40px 40px;
}

/* CARD */
.rating-container{
    background: white;
    border-radius: 20px;
    /* overflow: hidden;  <-- DIHAPUS agar efek meluap/pop-out gambar tidak terpotong */
    max-width: 620px;
    margin: auto;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    position: relative; /* Menjaga konteks tata letak */
}

/* HEADER */
.rating-header{
    background: #6B4F2A;
    padding: 25px 20px 20px; /* Sedikit disesuaikan */
    text-align: center;
   border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    position: relative; /* Agar z-index bekerja jika dibutuhkan */
}

/* JUDUL */
.rating-header h2{
    color: white;
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

/* BOX GAMBAR (PLANET) */
.planet-box{
    width: 380px;
    height: 180px;
    margin: 25px auto -90px; /* Mengatur jarak bawah negatif agar melompati header */

    background: rgba(255,255,255,0.45);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.6);
    border-radius: 28px;

    display: flex;
    justify-content: center;
    align-items: center;

    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    
    /* SOLUSI UTAMA: Mengangkat box ke paling depan */
    position: relative;
    z-index: 10; 
}

/* GAMBAR PLANET */
.rating-img{
    width: 250px;
    transform: translateY(-50px); /* Disesuaikan posisi vertikal maskotnya */
    margin-top: -20px;
    margin-bottom: -10px;

    filter: drop-shadow(
        0 10px 20px rgba(0,0,0,.15)
    );
}

/* --- FORM --- */
.rating-form {
    /* Kembalikan padding atas menjadi normal agar tidak mendorong isi form terlalu jauh */
    padding: 30px 30px 30px; 
}

/* --- STAR RATING --- */
.star-rating {
    display: flex;
    justify-content: center;
    flex-direction: row-reverse;
    gap: 8px;

    /* SOLUSI: Berikan margin-top negatif untuk menarik bintang ke atas */
    margin-top: -30px; 
    margin-bottom: 60px;

    /* Pastikan bintang berada di lapisan atas agar tidak tertutup */
    position: relative;
    z-index: 15; 
}

.star-rating input{
    display: none;
}

.star-rating label{
    font-size: 38px;
    color: #d1d1d1;
    cursor: pointer;
    transition: .2s;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label{
    color: #FFC107;
}

/* INPUT */
.form-group{
    margin-bottom: 15px;
}

.form-control{
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    font-size: 14px;
    transition: .2s;
}

.form-control:focus{
    border-color: #6B4F2A;
    box-shadow: 0 0 0 3px rgba(107,79,42,.08);
}

/* TEXTAREA */
textarea{
    resize: none;
    height: 90px;
}

/* BUTTON */
.btn-box{
    text-align: center;
    margin-top: 10px;
}

.btn-submit{
    background: #6B4F2A;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: .3s;
}

.btn-submit:hover{
    background: #8A673A;
    transform: translateY(-2px);
}

/* ALERT */
.success{
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
}

</style>

<div class="main-content">

    <div class="rating-container">

        <div class="rating-header">

            <h2>Rating dan Review FoodLink</h2>

            <div class="planet-box">

                <img src="{{ asset('img/gambar2.png') }}"
                     alt="Rating Mascot"
                     class="rating-img">

            </div>

        </div>

        <div class="rating-form">

            <form action="{{ route('review.store') }}" method="POST">

                @csrf

                <div class="star-rating">

                    <input type="radio" name="rating" id="star5" value="5">
                    <label for="star5">★</label>

                    <input type="radio" name="rating" id="star4" value="4">
                    <label for="star4">★</label>

                    <input type="radio" name="rating" id="star3" value="3">
                    <label for="star3">★</label>

                    <input type="radio" name="rating" id="star2" value="2">
                    <label for="star2">★</label>

                    <input type="radio" name="rating" id="star1" value="1">
                    <label for="star1">★</label>

                </div>

                <div class="form-group">
                    <input type="text"
                           name="nama"
                           class="form-control"
                           placeholder="Nama Donatur">
                </div>

                <div class="form-group">
                    <select name="kategori" class="form-control">
                        <option value="">Pilih Kategori</option>
                        <option value="Pelayanan">Pelayanan</option>
                        <option value="Relawan">Relawan</option>
                        <option value="Pengiriman">Pengiriman</option>
                    </select>
                </div>

                <div class="form-group">
                    <textarea name="review"
                              class="form-control"
                              placeholder="Tulis review"></textarea>
                </div>

                <div class="form-group">
                    <textarea name="feedback"
                              class="form-control"
                              placeholder="Masukkan feedback"></textarea>
                </div>

                <div class="btn-box">
                    <button type="submit" class="btn-submit">
                        Kirim
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection