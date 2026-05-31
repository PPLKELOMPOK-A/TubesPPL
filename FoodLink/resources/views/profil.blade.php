@extends('layouts.app')

@section('title', 'Foodlink - Profil Pengguna')

@section('content')
<style>
    /* --- CONTAINER PROFIL --- */
    .container-profil { padding: 40px 50px; max-width: 900px; width: 100%; margin-left: 0; }
    .alert-success { background-color: #E6F4EA; border: 1px solid #1E8E3E; color: #1E8E3E; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-size: 14px; display: flex; align-items: center; gap: 10px; max-width: 600px; }

    .profile-header-title { display: flex; align-items: center; gap: 20px; margin-bottom: 45px; }
    .back-nav { color: #111; text-decoration: none; font-size: 20px; transition: 0.2s; }
    .back-nav:hover { color: #6B4F2A; transform: translateX(-3px); }
    .page-title { font-size: 24px; font-weight: 700; color: #111; }

    .profile-hero { display: flex; align-items: center; gap: 35px; margin-bottom: 50px; }
    .user-avatar-large { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .hero-text h2 { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 0; }

    .profile-details-list { display: flex; flex-direction: column; gap: 25px; max-width: 600px; margin-bottom: 50px; }
    .detail-row { display: grid; grid-template-columns: 150px 1fr; align-items: center; }
    .detail-label { font-size: 14px; font-weight: 500; color: #444; }
    .detail-value { font-size: 14px; font-weight: 700; color: #111; }

    .btn-edit-bottom { background-color: #5C4322; color: #ffffff; border: none; padding: 12px 45px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.2s; }
    .btn-edit-bottom:hover { background-color: #4a351a; }
</style>

<div class="main-content-canvas">
    <div class="container-profil">
        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="profile-header-title">
            <a href="{{ route('dashboard') }}" class="back-nav"><i class="fa-solid fa-arrow-left"></i></a>
            <h1 class="page-title">Profil Saya</h1>
        </div>

        <div class="profile-hero">
            @if(!empty(Auth::user()->foto_profil))
                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="user-avatar-large" alt="Foto">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=2C3E50&color=fff&size=200" class="user-avatar-large" alt="Foto">
            @endif
            <div class="hero-text">
                <h2>{{ Auth::user()->name ?? 'User' }}</h2>
            </div>
        </div>

        <div class="profile-details-list">
            <div class="detail-row">
                <div class="detail-label">Nama</div>
                <div class="detail-value">{{ Auth::user()->name ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">NIK</div>
                <div class="detail-value">{{ Auth::user()->nik ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ Auth::user()->email ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Telepon</div>
                <div class="detail-value">{{ Auth::user()->telepon ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Lokasi</div>
                <div class="detail-value">{{ Auth::user()->lokasi ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Alamat</div>
                <div class="detail-value">{{ Auth::user()->alamat ?? '-' }}</div>
            </div>
        </div>

        <a href="{{ route('profil.edit') }}" class="btn-edit-bottom">Edit</a>
    </div>
</div>
@endsection