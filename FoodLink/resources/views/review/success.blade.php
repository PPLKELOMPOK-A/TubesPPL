@extends('layouts.app')

@section('title', 'Review Berhasil')

@section('content')

<style>

.success-wrapper{
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 75vh;
    overflow: hidden;
}

/* BACKGROUND BLUR */
.blur-circle{
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    z-index: 0;
}

.blur-1{
    width: 280px;
    height: 280px;
    background: rgba(255, 196, 120, 0.25);
    top: 25%;
    left: 30%;
}

.blur-2{
    width: 220px;
    height: 220px;
    background: rgba(139, 106, 61, 0.18);
    bottom: 20%;
    right: 30%;
}

/* GLASS CARD */
.success-card{
    position: relative;
    z-index: 2;

    width: 100%;
    max-width: 600px;

    background: rgba(255,255,255,0.38);

    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);

    border: 1px solid rgba(255,255,255,0.45);

    border-radius: 28px;

    padding: 40px 35px 35px;

    text-align: center;

    box-shadow:
        0 10px 40px rgba(0,0,0,0.08),
        inset 0 1px 1px rgba(255,255,255,0.4);

    transition: all .3s ease;
}

.success-card:hover{
    transform: translateY(-4px);

    box-shadow:
        0 18px 45px rgba(0,0,0,0.12),
        inset 0 1px 1px rgba(255,255,255,0.4);
}

/* IMAGE */
.success-img{
    width: 250px;
    display: block;
    margin: -100px auto 15px;

    filter: drop-shadow(
        0 10px 20px rgba(0,0,0,0.15)
    );
}

/* TITLE */
.success-card h2{
    font-size: 22px;
    font-weight: 700;
    line-height: 1.5;
    color: #2F2F2F;

    max-width: 480px;
    margin: auto;
}

/* BUTTON */
.btn-selesai{
    position: relative;
    z-index: 2;

    display: inline-block;
    margin-top: 40px;

    padding: 12px 38px;

    border-radius: 14px;

    text-decoration: none;
    color: white;

    font-size: 14px;
    font-weight: 600;

    background: linear-gradient(
        135deg,
        #9A7440,
        #6B4F2A
    );

    box-shadow:
        0 8px 20px rgba(107,79,42,0.25);

    transition: all .3s ease;
}

.btn-selesai:hover{
    transform: translateY(-2px);

    color: white;

    box-shadow:
        0 12px 25px rgba(107,79,42,0.35);
}

/* RESPONSIVE */
@media (max-width: 768px){

    .success-card{
        max-width: 420px;
        padding: 30px 25px;
    }

    .success-img{
        width: 200px;
        margin-top: -80px;
    }

    .success-card h2{
        font-size: 18px;
    }
}

</style>

<div class="success-wrapper">

    <!-- Blur Background -->
    <div class="blur-circle blur-1"></div>
    <div class="blur-circle blur-2"></div>

    <!-- Card -->
    <div class="success-card">

        <img src="{{ asset('img/gambar3.png') }}"
             alt="Success"
             class="success-img">

        <h2>
            Yeay, Terima Kasih Sudah Memberikan
            Rating dan Review Untuk FoodLink!
        </h2>

    </div>

    <!-- Button -->
    <a href="{{ route('review.index') }}"
       class="btn-selesai">
        Selesai
    </a>

</div>

@endsection