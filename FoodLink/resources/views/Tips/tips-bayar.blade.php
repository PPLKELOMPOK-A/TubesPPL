@extends('layouts.app')

@section('title', 'Pembayaran Tips - FoodLink')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    .content-body { padding: 40px 60px; width: 100%; box-sizing: border-box; }
    .content-title { font-weight: 800; font-size: 28px; color: #000; text-align: left; margin-bottom: 10px; }
    .form-center-wrapper { max-width: 650px; margin: 0 auto; text-align: center; }
    .illustration-container { display: flex; justify-content: center; width: 100%; margin-bottom: 25px; }
    .illustration-img { width: 480px; max-width: 100%; border-radius: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .feature-heading { font-size: 24px; font-weight: 700; color: #1e4620; margin-bottom: 25px; }
    .summary-amount { background: #fff5e6; color: #1e4620; font-size: 22px; font-weight: 800; padding: 16px; border-radius: 12px; border: 1px solid #f3e1c3; margin-bottom: 15px; text-align: center; }
    .summary-message { background: #f8f9fa; color: #444; font-size: 14px; font-weight: 600; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .payment-section-title { font-size: 18px; font-weight: 700; color: #1e4620; text-align: left; margin-bottom: 15px; }
    .payment-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 30px; }
    .payment-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: 0.2s; }
    .payment-item:hover { border-color: #46854d; background: #fafdfa; }
    .payment-item.selected { border-color: #46854d; background: rgba(70, 133, 77, 0.02); box-shadow: 0 0 0 1px #46854d; }
    .payment-left { display: flex; align-items: center; gap: 20px; }
    .payment-icon { width: 28px; text-align: center; color: #46854d; font-size: 20px; display: flex; align-items: center; justify-content: center; }
    .payment-name { font-size: 15px; font-weight: 700; color: #333; text-align: left; }
    .payment-desc { font-size: 13px; color: #888; font-weight: 400; margin-left: 5px; }
    .payment-arrow { color: #aaa; font-size: 14px; }
    .btn-pay { width: 100%; background: #46854d; color: white; border: none; padding: 16px; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 6px rgba(70, 133, 77, 0.2); transition: 0.3s; }
    .btn-pay:hover { background: #396d3e; }
    .footer-note { color: #888; font-size: 14px; margin-top: 25px; font-style: italic; }
</style>
@endsection

@section('content')
<div x-data="{ metodeSelected: 'qris' }">
<div class="content-body">
    <div class="content-title">Tips</div>
    <div class="form-center-wrapper">
        <div class="illustration-container">
            <img src="{{ asset('img/box-sayur.png') }}" class="illustration-img" alt="Ilustrasi Tips">
        </div>
        <div class="feature-heading">Berikan Tips Sepenuh Hati Untuk FoodLink</div>

        <div class="summary-amount">
            RP. {{ number_format($amount ?? 25000, 0, ',', '.') }}
        </div>

        <div class="summary-message">
            <i class="far fa-smile" style="color: #46854d; font-size: 18px;"></i>
            <span>{{ $pesan ?? 'Semoga FoodLink semakin bermanfaat untuk sesama!' }}</span>
        </div>

        <div class="payment-section-title">Pilih Metode Pembayaran</div>

        <div class="payment-list">
            <div class="payment-item" :class="metodeSelected === 'qris' ? 'selected' : ''" @click="metodeSelected = 'qris'">
                <div class="payment-left">
                    <div class="payment-icon"><i class="fas fa-qrcode"></i></div>
                    <div class="payment-name">QRIS <span class="payment-desc">Scan kode QR</span></div>
                </div>
                <div class="payment-arrow"><i class="fas fa-chevron-right"></i></div>
            </div>
            <div class="payment-item" :class="metodeSelected === 'gopay' ? 'selected' : ''" @click="metodeSelected = 'gopay'">
                <div class="payment-left">
                    <div class="payment-icon"><i class="fas fa-wallet"></i></div>
                    <div class="payment-name">Gopay</div>
                </div>
                <div class="payment-arrow"><i class="fas fa-chevron-right"></i></div>
            </div>
            <div class="payment-item" :class="metodeSelected === 'va' ? 'selected' : ''" @click="metodeSelected = 'va'">
                <div class="payment-left">
                    <div class="payment-icon"><i class="fas fa-university"></i></div>
                    <div class="payment-name">Virtual Account</div>
                </div>
                <div class="payment-arrow"><i class="fas fa-chevron-right"></i></div>
            </div>
            <div class="payment-item" :class="metodeSelected === 'transfer' ? 'selected' : ''" @click="metodeSelected = 'transfer'">
                <div class="payment-left">
                    <div class="payment-icon"><i class="fas fa-exchange-alt"></i></div>
                    <div class="payment-name">Transfer Bank</div>
                </div>
                <div class="payment-arrow"><i class="fas fa-chevron-right"></i></div>
            </div>
        </div>

        <form action="#" method="POST">
            @csrf
            <input type="hidden" name="final_amount" value="{{ $amount ?? 25000 }}">
            <input type="hidden" name="final_pesan" value="{{ $pesan ?? '' }}">
            <input type="hidden" name="payment_method" :value="metodeSelected">
            <button type="submit" class="btn-pay">Bayar Sekarang</button>
        </form>

        <div class="footer-note">Terima kasih untuk dukungan tulus anda!</div>
    </div>
</div>
</div>
@endsection