@extends('layouts.app')

@section('title', 'Rating & Review')

@section('content')

<style>

.main-content{
    padding:20px 40px 40px;
}

/* CARD */
.rating-container{
    background:white;
    border-radius:20px;
    overflow:hidden;
    max-width:700px;
    margin:auto;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

/* HEADER */
.rating-header{
    background:#6B4F2A;
    padding:25px 20px 35px;
    text-align:center;
    border-bottom-left-radius:50% 40px;
    border-bottom-right-radius:50% 40px;
}

/* GAMBAR PLANET */
.rating-img{
    width:330px;
    margin-top:20px;
    margin-bottom:-20px;
}

/* BOX TRANSPARAN BELAKANG GAMBAR */
.planet-box{
    width:420px;
    height:210px;

    /* KOTAK DITURUNKAN */
    margin:60px auto -120px;

    background:rgba(255,255,255,0.55);

    border-radius:30px;

    backdrop-filter:blur(12px);

    border:1px solid rgba(255,255,255,0.7);

    display:flex;
    justify-content:center;
    align-items:center;

    box-shadow:0 10px 25px rgba(0,0,0,0.18);
}

/* JUDUL */
.rating-header h2{
    color:white;
    margin:0;
    font-size:26px;
    font-weight:bold;
}

/* FORM */
.rating-form{
    padding:30px;
}

/* STAR */
.star-rating{
    display:flex;
    justify-content:center;
    flex-direction:row-reverse;
    gap:8px;

    /* BINTANG DITURUNKAN */
    margin-top:90px;

    margin-bottom:25px;
}

.star-rating input{
    display:none;
}

.star-rating label{
    font-size:40px;
    color:#ccc;
    cursor:pointer;
    transition:0.2s;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label{
    color:gold;
}

/* INPUT */
.form-group{
    margin-bottom:18px;
}

.form-control{
    width:100%;
    padding:12px 15px;
    border:1px solid #ddd;
    border-radius:10px;
    outline:none;
    font-size:14px;
}

.form-control:focus{
    border-color:#6B4F2A;
}

/* TEXTAREA */
textarea{
    resize:none;
    height:100px;
}

/* BUTTON */
.btn-box{
    text-align:center;
    margin-top:10px;
}

.btn-submit{
    background:#6B4F2A;
    color:white;
    border:none;
    padding:12px 28px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

.btn-submit:hover{
    background:#8A673A;
}

/* ALERT */
.success{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
}

</style>

<div class="main-content">

    <div class="rating-container">

        <div class="rating-header">

    <h2>Rating dan Review FoodLink</h2>

    {{-- BOX TRANSPARAN --}}
    <div class="planet-box">

        <img src="{{ asset('img/gambar2.png') }}"
             alt="Rating Mascot"
             class="rating-img">

    </div>

</div>
        {{-- FORM --}}
        <div class="rating-form">


            <form action="{{ route('review.store') }}" method="POST">

                @csrf

                {{-- STAR --}}
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

                {{-- NAMA --}}
                <div class="form-group">

                    <input type="text"
                           name="nama"
                           class="form-control"
                           placeholder="Nama Donatur">

                </div>

                {{-- KATEGORI --}}
                <div class="form-group">

                    <select name="kategori" class="form-control">

                        <option value="">Pilih Kategori</option>
                        <option value="Pelayanan">Pelayanan</option>
                        <option value="Relawan">Relawan</option>
                        <option value="Pengiriman">Pengiriman</option>

                    </select>

                </div>

                {{-- REVIEW --}}
                <div class="form-group">

                    <textarea name="review"
                              class="form-control"
                              placeholder="Tulis review"></textarea>

                </div>

                {{-- FEEDBACK --}}
                <div class="form-group">

                    <textarea name="feedback"
                              class="form-control"
                              placeholder="Masukkan feedback"></textarea>

                </div>

                {{-- BUTTON --}}
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