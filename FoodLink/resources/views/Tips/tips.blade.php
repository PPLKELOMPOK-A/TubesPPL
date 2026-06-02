@extends('layouts.app')

@section('title', 'Tips - FoodLink')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Container utama dibuat melebar maksimal */
    .content-body { 
        padding: 40px; 
        max-width: 1200px; /* Kita lebarkan batas maksimalnya */
        margin-left: 0; 
        margin-right: auto; 
        box-sizing: border-box; 
    }
    
    .content-title { 
        font-weight: 800; 
        font-size: 28px; 
        color: #6B4F2A; 
        text-align: left; 
        margin-bottom: 25px; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    /* Grid Utama: Membagi Gambar (Kiri) dan Form (Kanan) */
    .tips-main-card { 
        background: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        border: 1px solid #E2E8F0;
        display: grid;
        grid-template-columns: 45% 55%; /* Kolom Kiri 45%, Kolom Kanan 55% */
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Sisi Kiri: Khusus Gambar & Deskripsi Fitur */
    .tips-left-side {
        background: #FFF9EE;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-right: 1px solid #F8E7C1;
    }

    .illustration-container { 
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 25px;
    }
    
    .illustration-img { 
        width: 100%;
        max-width: 360px; 
        object-fit: contain;
    }
    
    .feature-heading { 
        font-size: 22px; 
        font-weight: 700; 
        color: #6B4F2A; 
        margin-bottom: 12px; 
        text-align: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .feature-desc { 
        color: #718096; 
        font-size: 14px; 
        line-height: 1.6; 
        text-align: center;
        margin: 0;
    }
    
    /* Sisi Kanan: Khusus Form Transaksi */
    .tips-right-side {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    /* Grid Pilihan Nominal dibuat melebar horizontal */
    .nominal-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 12px; 
        margin-bottom: 25px; 
    }
    
    .btn-nominal { 
        background: #F8FAFC; 
        border: 2px solid #E2E8F0; 
        color: #4A5568; 
        padding: 15px 5px; 
        border-radius: 12px; 
        font-size: 14px; 
        font-weight: 700; 
        cursor: pointer; 
        transition: all 0.2s ease; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .btn-nominal:hover {
        border-color: #6B4F2A;
        background-color: #FFF9EE;
        color: #6B4F2A;
    }
    
    .btn-nominal.selected { 
        background: #6B4F2A; 
        color: white; 
        border-color: #6B4F2A; 
        box-shadow: 0 4px 12px rgba(107, 79, 42, 0.2);
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #4A5568;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .input-custom, .input-message { 
        width: 100%; 
        background: #F8FAFC; 
        border: 1px solid #E2E8F0; 
        padding: 14px 20px; 
        border-radius: 12px; 
        box-sizing: border-box; 
        margin-bottom: 22px; 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        transition: all 0.3s ease;
    }
    
    .input-custom:focus-within, .input-message:focus-within {
        border-color: #6B4F2A;
        background-color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(107, 79, 42, 0.1);
    }

    .input-custom input, .input-message input { 
        border: none; 
        background: transparent; 
        width: 100%; 
        outline: none; 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        font-weight: 600; 
        color: #2D3748; 
        font-size: 15px;
    }
    
    .btn-submit { 
        width: 100%; 
        background: #6B4F2A; 
        color: white; 
        border: none; 
        padding: 16px; 
        border-radius: 12px; 
        font-size: 16px; 
        font-weight: 700; 
        cursor: pointer; 
        margin-top: 5px; 
        box-shadow: 0 4px 14px rgba(107, 79, 42, 0.2); 
        transition: all 0.2s ease; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .btn-submit:hover { 
        background: #523B1F; 
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: scale(0.99);
    }

    .footer-note { 
        color: #A0AEC0; 
        font-size: 13px; 
        margin-top: 20px; 
        font-style: italic; 
        text-align: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Responsif untuk layar hp jika dibuka di device kecil */
    @media (max-width: 992px) {
        .tips-main-card {
            grid-template-columns: 1fr;
        }
        .tips-left-side {
            border-right: none;
            border-bottom: 1px solid #F8E7C1;
        }
    }
</style>

<div x-data="{ nominalSelected: null, customNominal: '', pesan: '' }">
    <div class="content-body">
        <div class="content-title">Beri Tips</div>
        
        <div class="tips-main-card">
            
            <div class="tips-left-side">
                <div class="illustration-container">
                    <img src="{{ asset('img/box-sayur.png') }}" class="illustration-img" alt="Ilustrasi Tips">
                </div>
                <div class="feature-heading">Berikan Tips Sepenuh Hati Untuk FoodLink</div>
                <p class="feature-desc">Bantu kami terus mengurangi food waste dan bantu sesama. Donasi tips anda akan digunakan untuk pengembangan dan operasional FoodLink.</p>
            </div>
            
            <div class="tips-right-side">
                @if ($errors->any())
                    <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; text-align: left;">
                        <i class="fas fa-exclamation-circle"></i> {{ $errors->first('amount') }}
                    </div>
                @endif

                <form action="{{ route('tips.proses') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" :value="customNominal ? customNominal : nominalSelected">
                    
                    <label class="form-label">Pilih Nominal Instan</label>
                    <div class="nominal-grid">
                        <button type="button" class="btn-nominal" :class="nominalSelected === 10000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 10000; customNominal = ''">RP. 10.000</button>
                        <button type="button" class="btn-nominal" :class="nominalSelected === 25000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 25000; customNominal = ''">RP. 25.000</button>
                        <button type="button" class="btn-nominal" :class="nominalSelected === 50000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 50000; customNominal = ''">RP. 50.000</button>
                        <button type="button" class="btn-nominal" :class="nominalSelected === 100000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 100000; customNominal = ''">RP. 100.000</button>
                    </div>
                    
                    <label class="form-label">Masukkan Nominal Lain</label>
                    <div class="input-custom">
                        <i class="fas fa-plus" style="color: #6B4F2A; font-size: 14px;"></i>
                        <span style="font-weight: 700; color: #4A5568; font-size: 14px;">RP</span>
                        <input type="number" x-model.number="customNominal" @input="nominalSelected = null" placeholder="Contoh: 15000">
                    </div>
                    
                    <label class="form-label">Pesan Tambahan</label>
                    <div class="input-message">
                        <i class="far fa-smile" style="color: #6B4F2A; font-size: 18px;"></i>
                        <input type="text" name="pesan" x-model="pesan" placeholder="Tulis pesan penyemangat...">
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        Lanjut Pembayaran
                    </button>
                </form>

                <div class="footer-note">
                    Terima kasih untuk dukungan tulus anda!
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection