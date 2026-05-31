@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

/* ================= FIX ADMIN LAYOUT ================= */

html,
body,
.wrapper,
.content-wrapper,
.content,
.main-content{
    height:100%;
    background:transparent !important;
}

.content-wrapper{
    padding-top:0 !important;
    margin-top:0 !important;
}

.content-header{
    display:none !important;
}

.container-fluid{
    padding:0 !important;
}

/* ================= PAGE ================= */

.penugasan-page{
    position:fixed;
    top:0;
    left:250px; /* sesuaikan dengan lebar sidebar */

    right:0;
    bottom:0;

    overflow-y:auto;

    background-image:url('/img/BackgroundCreate.png');
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
}

/* ================= OVERLAY ================= */

.penugasan-overlay{
    width:100%;
    min-height:100vh;

    background:rgba(255,255,255,0.55);
    backdrop-filter:blur(3px);

    padding-top:40px;
}

/* ================= TITLE ================= */

.title-section{
    position:relative;
    z-index:999;
}

.page-title{
    font-size:18px;
    font-weight:700;
    color:#3a2a17;
}

/* ================= CONTENT ================= */

.content-area{
    padding:0 40px 40px;
}

/* ================= HEADER ================= */

.penugasan-header{
    background:#9A6827;
    color:white;

    width:fit-content;
    min-width:500px;

    border-radius:18px;

    padding:22px 35px;

    margin:0 auto;

    display:flex;
    align-items:center;
    justify-content:center;
    gap:16px;

    box-shadow:0 5px 14px rgba(0,0,0,0.15);
}

.penugasan-header i{
    width:34px;
    height:34px;
}

.header-text h2{
    font-size:20px;
    font-weight:700;
    margin-bottom:2px;
}

.header-text p{
    font-size:12px;
    margin:0;
}

/* ================= FORM ================= */

.container{
    display:flex;
    justify-content:center;
    align-items:flex-start;

    gap:30px;

    margin-top:40px;

    flex-wrap:wrap;
}

.card{
    background:rgba(255,255,255,0.95);

    width:380px;

    border:none;

    border-radius:22px;

    padding:28px;

    box-shadow:0 4px 14px rgba(0,0,0,0.08);
}

label{
    display:block;

    margin-top:14px;
    margin-bottom:8px;

    font-size:14px;
    font-weight:600;

    color:#333;
}

input{
    width:100%;

    padding:12px 14px;

    border-radius:10px;
    border:1px solid #ccc;

    background:white;

    font-size:13px;
}

input:focus{
    outline:none;
    border-color:#9A6827;
}

/* ================= BUTTON ================= */

.submit-wrapper{
    width:790px;

    margin:25px auto 0;

    display:flex;
    justify-content:flex-end;
}

.submit-btn{
    background:#9A6827;
    color:white;

    border:none;

    padding:12px 30px;

    border-radius:10px;

    font-size:14px;
    font-weight:600;

    cursor:pointer;

    transition:.2s;
}

.submit-btn:hover{
    background:#7a531f;
    transform:scale(1.03);
}

/* ================= RESPONSIVE ================= */

@media(max-width:1200px){

    .penugasan-page{
        left:0;
    }

    .submit-wrapper{
        width:100%;
        justify-content:center;
    }
}

@media(max-width:768px){

    .penugasan-header{
        min-width:auto;
        width:100%;
    }

    .card{
        width:100%;
    }

    .content-area{
        padding:20px;
    }
}

</style>

<div class="penugasan-page">

    <div class="penugasan-overlay">

        <div class="title-section"></div>

        <div class="content-area">

            <div class="penugasan-header">

                <i data-lucide="user-plus"></i>

                <div class="header-text">
                    <h2>Tambah Penugasan Relawan</h2>
                    <p>Lengkapi data untuk menambahkan relawan ke dalam sistem</p>
                </div>

            </div>

            <form action="{{ route('admin.penugasan.store') }}" method="POST">
                @csrf

                <div class="container">

                    <div class="card">

                        <label>ID Penugasan</label>
                        <input type="text" name="id_penugasan" placeholder="Masukkan ID Penugasan">

                        <label>ID Donasi</label>
                        <input type="text" name="id_donasi" placeholder="Masukkan ID Donasi">

                        <label>Nama Donatur</label>
                        <input type="text" name="nama_donatur" placeholder="Masukkan Nama Donatur">

                        <label>Nama Relawan</label>
                        <input type="text" name="relawan" placeholder="Masukkan Nama Relawan">

                    </div>

                    <div class="card">

                        <label>Lokasi Pengambilan</label>
                        <input type="text" name="lokasi_pengambilan" placeholder="Masukkan Lokasi">

                        <label>Lokasi Pengantaran</label>
                        <input type="text" name="lokasi_pengantaran" placeholder="Masukkan Lokasi">

                        <label>Tanggal Penugasan</label>
                        <input type="date" name="tanggal_penugasan">

                    </div>

                </div>

                <div class="submit-wrapper">
                    <button type="submit" class="submit-btn">
                        Submit
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
lucide.createIcons();
</script>

@endsection