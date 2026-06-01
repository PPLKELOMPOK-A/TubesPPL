@extends('layouts.app')

@section('title', 'Rating & Review')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    font-family: 'Poppins', sans-serif;
}

.main-content{
    padding: 30px 40px 50px;
    
}

/* CARD */
.rating-container{
    max-width: 650px;
    margin: auto;
    border-radius: 30px;

    background: rgba(255,255,255,.9);

    backdrop-filter: blur(15px);

    box-shadow:
        0 15px 40px rgba(0,0,0,.08),
        0 5px 15px rgba(0,0,0,.05);

    overflow: visible;
    position: relative;

    transition: .3s ease;
}

.rating-container:hover{
    transform: translateY(-3px);
}

/* HEADER */
.rating-header{
    background: linear-gradient(
        135deg,
        #6B4F2A,
        #7A5930,
        #8A673A
    );

    padding: 30px 20px 20px;

    text-align: center;

    border-top-left-radius: 30px;
    border-top-right-radius: 30px;

    position: relative;
    overflow: hidden;
}

.rating-header::before{
    content: "";
    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);

    top: -80px;
    right: -60px;
}

.rating-header::after{
    content: "";
    position: absolute;

    width: 150px;
    height: 150px;

    border-radius: 50%;

    background: rgba(255,255,255,.05);

    bottom: -60px;
    left: -40px;
}

.rating-header h2{
    color: white;
    margin: 0;

    font-size: 30px;
    font-weight: 700;

    position: relative;
    z-index: 2;
}

/* BOX GAMBAR */
.planet-box{
    width: 400px;
    height: 190px;

    margin: 30px auto -95px;

    background: rgba(255,255,255,.28);

    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);

    border: 1px solid rgba(255,255,255,.45);

    border-radius: 30px;

    display: flex;
    justify-content: center;
    align-items: center;

    position: relative;
    z-index: 10;

    box-shadow:
        0 15px 35px rgba(0,0,0,.12);
}

/* GAMBAR */
.rating-img{
    width: 250px;

    transform: translateY(-65px);

    filter:
        drop-shadow(
            0 15px 25px rgba(0,0,0,.15)
        );

    transition: .4s ease;
}

.rating-img:hover{
    transform: translateY(-50px) scale(1.03);
}

/* FORM */
.rating-form{
    padding: 45px 35px 35px;
}

/* STAR */
.star-rating{
    display: flex;
    justify-content: center;
    flex-direction: row-reverse;

    gap: 10px;

    margin-top: -60px;
    margin-bottom: 40px;

    position: relative;
    z-index: 20;
}

.star-rating input{
    display: none;
}

.star-rating label{
    font-size: 42px;
    color: #d6d6d6;

    cursor: pointer;

    transition: .25s ease;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label{
    color: #FFD54F;

    transform: scale(1.15);

    text-shadow:
        0 0 10px rgba(255,213,79,.8),
        0 0 25px rgba(255,213,79,.4);
}

/* INPUT */
.form-group{
    margin-bottom: 18px;
}

.form-control{
    width: 100%;

    padding: 14px 18px;

    border-radius: 14px;

    border: 1px solid #e5e5e5;

    background: #fff;

    font-size: 14px;

    transition: .3s ease;
}

.form-control:hover{
    border-color: #c7b39a;
}

.form-control:focus{
    outline: none;

    border-color: #8A673A;

    box-shadow:
        0 0 0 4px rgba(138,103,58,.12);

    transform: translateY(-2px);
}

/* TEXTAREA */
textarea{
    resize: none;
    min-height: 110px;
}

/* BUTTON */
.btn-box{
    text-align: center;
    margin-top: 25px;
}

.btn-submit{
    background: linear-gradient(
        135deg,
        #6B4F2A,
        #8A673A
    );

    color: white;

    border: none;

    padding: 14px 40px;

    border-radius: 50px;

    font-size: 15px;
    font-weight: 600;

    cursor: pointer;

    transition: .3s ease;

    box-shadow:
        0 10px 25px rgba(107,79,42,.25);
}

.btn-submit:hover{
    transform: translateY(-3px);

    box-shadow:
        0 15px 30px rgba(107,79,42,.35);
}

.btn-submit:active{
    transform: scale(.98);
}

/* ALERT */
.success{
    background: #d4edda;
    color: #155724;

    padding: 12px 15px;

    border-radius: 12px;

    margin-bottom: 20px;
}

/* RESPONSIVE */
@media(max-width:768px){

    .main-content{
        padding: 20px;
    }

    .planet-box{
        width: 90%;
        height: 170px;
    }

    .rating-img{
        width: 220px;
    }

    .rating-header h2{
        font-size: 24px;
    }

    .rating-form{
        padding: 40px 20px 25px;
    }
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
                        Kirim Review
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection