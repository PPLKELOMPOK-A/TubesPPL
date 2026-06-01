@extends('layouts.app')

@section('title', 'Review Berhasil')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

.success-wrapper{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 85vh;
    padding: 30px;
    background: #f5efe6;
}

/* CARD UTAMA: EFEK GLOSSY & BORDER GRADASI */
.success-card{
    position: relative;
    width: 100%;
    max-width: 620px;
    
    /* Efek Glassmorphism Glossy */
    background: rgba(252, 250, 247, 0.45); 
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    
    border-radius: 30px;
    padding: 70px 50px 45px;
    text-align: center;
    
    /* Shadow lembut & Inner Shadow untuk efek kilauan kaca */
    box-shadow:
        0 15px 35px rgba(107, 79, 42, 0.06),
        inset 0 1px 2px rgba(255, 255, 255, 0.6);
    
    /* Border Tepi Gradasi Cokelat */
    border: 2px solid transparent;
    background-image: linear-gradient(rgba(252, 250, 247, 0.45), rgba(252, 250, 247, 0.45)), linear-gradient(135deg, #bd9662, #6b4f2a);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    
    /* Transisi Halus untuk Semua Properti Interaktif */
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), 
                background-color 0.4s ease, 
                background-image 0.4s ease,
                box-shadow 0.4s ease;
}

/* KONDISI SAAT CARD DI-HOVER / DI-KLIK (ACTIVE) */
.success-card:hover,
.success-card:active {
    transform: translateY(-4px);
    
    /* Tetap transparan transisi ke putih semi-pekat, bukan putih solid jenuh */
    background-color: rgba(255, 255, 255, 0.65); 
    
    /* Lapisan gradasi diselaraskan dengan kepekatan background baru */
    background-image: linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)), linear-gradient(135deg, #bd9662, #6b4f2a);
    
    box-shadow:
        0 20px 45px rgba(107, 79, 42, 0.1),
        inset 0 1px 2px rgba(255, 255, 255, 0.9);
}

/* Gambar Planet Mascot */
.success-img{
    width: 190px;
    display: block;
    margin: -130px auto 15px;
    filter: drop-shadow(0 12px 18px rgba(107, 79, 42, 0.18));
}

/* Judul */
.success-card h2{
    font-size: 22px;
    font-weight: 700;
    line-height: 1.5;
    color: #4b3520;
    margin-bottom: 15px;
}

/* Deskripsi */
.success-subtitle{
    font-size: 15px;
    color: #7a6a53;
    line-height: 1.8;
    max-width: 470px;
    margin: auto;
}

/* Tombol Selesai */
.btn-selesai{
    display: inline-block;
    margin-top: 30px;
    padding: 14px 50px;
    border-radius: 15px;
    background: linear-gradient(135deg, #8a673a, #6b4f2a);
    color: white;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    box-shadow: 0 6px 15px rgba(107, 79, 42, 0.2);
    transition: all 0.3s ease;
}

.btn-selesai:hover{
    background: linear-gradient(135deg, #9c7645, #7a5a31);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(107, 79, 42, 0.3);
}

/* Responsive Desain Mobile */
@media(max-width:768px){
    .success-wrapper{
        padding: 20px;
    }

    .success-card{
        padding: 60px 25px 35px;
        border-width: 1.5px; /* Menyesuaikan tebal border di layar kecil */
    }

    .success-img{
        width: 150px;
        margin-top: -100px;
    }

    .success-card h2{
        font-size: 20px;
    }

    .success-subtitle{
        font-size: 14px;
    }

    .btn-selesai{
        width: 100%;
    }
}

</style>

<div class="success-wrapper">

    <div class="success-card">

        <img src="{{ asset('img/gambar3.png') }}"
             alt="Success"
             class="success-img">

        <h2>
            Yeay, Terima Kasih Sudah Memberikan
            Rating dan Review Untuk FoodLink!
        </h2>

        <p class="success-subtitle">
            Setiap ulasanmu sangat berarti dalam membantu FoodLink membangun
            komunitas zero food waste yang lebih besar dan berdampak bagi sesama.
        </p>

        <a href="{{ route('review.index') }}" class="btn-selesai">
            Selesai
        </a>

    </div>

</div>

@endsection