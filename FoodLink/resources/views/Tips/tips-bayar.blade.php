@extends('layouts.app')

@section('title', 'Pembayaran Tips - FoodLink')

@section('content')
<!-- LINK FONTS DAN ALPINEJS -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Container utama melebar maksimal sesuai halaman sebelumnya */
    .content-body { 
        padding: 40px; 
        max-width: 1200px; 
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
    
    /* Grid Utama: Membagi Ringkasan (Kiri) dan Metode Bayar (Kanan) */
    .tips-main-card { 
        background: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        border: 1px solid #E2E8F0;
        display: grid;
        grid-template-columns: 45% 55%; 
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Sisi Kiri: Khusus Gambar & Detail Nominal */
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
        margin-bottom: 20px;
    }
    
    .illustration-img { 
        width: 100%;
        max-width: 320px; 
        object-fit: contain;
    }
    
    .feature-heading { 
        font-size: 20px; 
        font-weight: 700; 
        color: #6B4F2A; 
        margin-bottom: 15px; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    /* Box Jumlah Nominal Krem Coklat */
    .summary-amount { 
        background: #6B4F2A; 
        color: #FFFFFF; 
        font-size: 26px; 
        font-weight: 800; 
        padding: 15px 30px; 
        border-radius: 12px; 
        margin-bottom: 15px; 
        text-align: center; 
        box-shadow: 0 4px 12px rgba(107, 79, 42, 0.15);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .summary-message { 
        background: #FFFFFF; 
        color: #4A5568; 
        font-size: 14px; 
        font-weight: 600; 
        padding: 12px 20px; 
        border-radius: 10px; 
        border: 1px solid #E2E8F0; 
        max-width: 90%;
        text-align: center;
        font-style: italic;
    }
    
    /* Sisi Kanan: Pilihan Metode Pembayaran */
    .tips-right-side {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .payment-section-title { 
        font-size: 13px; 
        font-weight: 700; 
        color: #4A5568; 
        text-align: left; 
        margin-bottom: 15px; 
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .payment-list { 
        display: flex; 
        flex-direction: column; 
        gap: 12px; 
        margin-bottom: 25px; 
    }
    
    .payment-item { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 16px 20px; 
        background: #F8FAFC; 
        border: 2px solid #E2E8F0; 
        border-radius: 12px; 
        cursor: pointer; 
        transition: all 0.2s ease; 
    }
    
    .payment-item:hover { 
        border-color: #6B4F2A; 
        background: #FFF9EE; 
    }
    
    /* State Aktif Metode Coklat Foodlink */
    .payment-item.selected { 
        border-color: #6B4F2A; 
        background: #FFF9EE; 
        box-shadow: 0 0 0 1px #6B4F2A; 
    }
    
    .payment-left { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
    }
    
    .payment-icon { 
        color: #6B4F2A; 
        font-size: 18px; 
        display: flex; 
        align-items: center; 
    }
    
    .payment-name { 
        font-size: 15px; 
        font-weight: 700; 
        color: #2D3748; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .payment-desc { 
        font-size: 13px; 
        color: #718096; 
        font-weight: 400; 
    }
    
    .btn-pay { 
        width: 100%; 
        background: #6B4F2A; 
        color: white; 
        border: none; 
        padding: 16px; 
        border-radius: 12px; 
        font-size: 16px; 
        font-weight: 700; 
        cursor: pointer; 
        box-shadow: 0 4px 14px rgba(107, 79, 42, 0.2); 
        transition: all 0.2s ease; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .btn-pay:hover { 
        background: #523B1F; 
        transform: translateY(-1px);
    }

    .btn-pay:active {
        transform: scale(0.99);
    }

    .footer-note { 
        color: #A0AEC0; 
        font-size: 13px; 
        margin-top: 20px; 
        text-align: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

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

<div x-data="{ metodeSelected: 'qris' }">
    <div class="content-body">
        <div class="content-title">Konfirmasi Pembayaran</div>
        
        <!-- Wrapper Grid Lebar -->
        <div class="tips-main-card">
            
            <!-- SISI KIRI: Detail Summary -->
            <div class="tips-left-side">
                <div class="illustration-container">
                    <img src="{{ asset('img/box-sayur.png') }}" class="illustration-img" alt="Ilustrasi Tips">
                </div>
                <div class="feature-heading">Ringkasan Tips Anda</div>
                
                <div class="summary-amount">
                    RP. {{ number_format($amount ?? 25000, 0, ',', '.') }}
                </div>

                <div class="summary-message">
                    "{{ $pesan ?? 'Semoga FoodLink semakin bermanfaat untuk sesama!' }}"
                </div>
            </div>
            
            <!-- SISI KANAN: Pilihan Metode & Submit -->
            <div class="tips-right-side">
                <div class="payment-section-title">Pilih Metode Pembayaran</div>

                <div class="payment-list">
                    <div class="payment-item" :class="metodeSelected === 'qris' ? 'selected' : ''" @click="metodeSelected = 'qris'">
                        <div class="payment-left">
                            <div class="payment-icon"><i class="fas fa-qrcode"></i></div>
                            <div class="payment-name">QRIS <span class="payment-desc">(Scan otomatis)</span></div>
                        </div>
                    </div>
                    
                    <!-- SUDAH DIUBAH KE E-WALLET -->
                    <div class="payment-item" :class="metodeSelected === 'ewallet' ? 'selected' : ''" @click="metodeSelected = 'ewallet'">
                        <div class="payment-left">
                            <div class="payment-icon"><i class="fas fa-wallet"></i></div>
                            <div class="payment-name">E-Wallet <span class="payment-desc">(OVO, Dana, LinkAja, Gopay)</span></div>
                        </div>
                    </div>
                    
                    <div class="payment-item" :class="metodeSelected === 'va' ? 'selected' : ''" @click="metodeSelected = 'va'">
                        <div class="payment-left">
                            <div class="payment-icon"><i class="fas fa-university"></i></div>
                            <div class="payment-name">Virtual Account</div>
                        </div>
                    </div>
                    
                    <div class="payment-item" :class="metodeSelected === 'transfer' ? 'selected' : ''" @click="metodeSelected = 'transfer'">
                        <div class="payment-left">
                            <div class="payment-icon"><i class="fas fa-exchange-alt"></i></div>
                            <div class="payment-name">Transfer Bank</div>
                        </div>
                    </div>
                </div>

                <!-- FORM SINGLE UNTUK MIDTRANS -->
                <form id="payment-form">
                    @csrf
                    <input type="hidden" name="final_amount" id="final_amount" value="{{ $amount ?? 25000 }}">
                    <input type="hidden" name="final_pesan" value="{{ $pesan ?? '' }}">
                    <input type="hidden" name="payment_method" :value="metodeSelected">
                    
                    <button type="button" id="pay-button" class="btn-pay">
                        Bayar Sekarang
                    </button>
                </form>

                <div class="footer-note">
                    Pembayaran aman & terenkripsi melalui Midtrans.
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- SCRIPT PANGGIL POPUP MIDTRANS -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function (e) {
        e.preventDefault();
        
        let formData = new FormData(document.getElementById('payment-form'));

        fetch("{{ route('tips.checkout') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result){
                        alert("Terima kasih! Pembayaran tips berhasil."); 
                        window.location.href = "/tips";
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran Anda."); 
                        window.location.reload();
                    },
                    onError: function(result){
                        alert("Waduh, pembayaran gagal! Silakan coba lagi.");
                    },
                    onClose: function(){
                        alert('Anda menutup halaman pembayaran sebelum selesai.');
                    }
                });
            } else {
                alert('Gagal mengambil data transaksi dari server.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
        });
    });
</script>
@endsection