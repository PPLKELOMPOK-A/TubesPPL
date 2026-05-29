@extends('layouts.app')

@section('title', 'Review Berhasil')

@section('content')

<style>

.success-wrapper{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:80vh;
}

.success-card{
    width:500px;
    background:#f5f2ef;
    border-radius:15px;
    padding:40px 30px;
    text-align:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.success-img{
    width:230px;
    margin-top:-90px;
}

.success-card h2{
    margin-top:10px;
    font-size:34px;
    color:#222;
    font-weight:bold;
    line-height:1.4;
}

.btn-selesai{
    display:inline-block;
    margin-top:30px;
    background:#6B4F2A;
    color:white;
    padding:10px 30px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}

</style>

<div class="success-wrapper">

    <div class="success-card">

        <img src="{{ asset('img/gambar3.png') }}"
             class="success-img">

        <h2>
            Yeay, Terima Kasih Sudah Memberikan
            Rating dan Review Untuk FoodLink!
        </h2>

        <a href="{{ route('review.index') }}"
           class="btn-selesai">
            Selesai
        </a>

    </div>

</div>

@endsection