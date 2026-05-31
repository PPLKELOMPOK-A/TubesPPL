<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tips - FoodLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { margin: 0; font-family: 'Poppins', sans-serif; background: #ffffff; }
        .container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR STYLE --- */
        .sidebar { 
            width: 280px; 
            background: #fdecc6; 
            padding: 30px 15px; 
            border-right: 1px solid #e0e0e0; 
            position: fixed; 
            height: 100%; 
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand { display: flex; align-items: center; gap: 10px; padding: 10px 20px; margin-bottom: 20px; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; width: 100%; flex-grow: 1; }
        .sidebar-menu li { margin-bottom: 10px; width: 100%; }
        .sidebar-menu li a { 
            display: flex; align-items: center; gap: 15px; padding: 15px 20px; 
            text-decoration: none; color: #5b3a1e; font-size: 14px; font-weight: 600; border-radius: 12px; 
        }
        .sidebar-menu li a:hover { background: rgba(91, 58, 30, 0.05); }
        .logout { color: #5b3a1e; font-weight: bold; cursor: pointer; text-decoration: none; padding: 20px; display: flex; align-items: center; gap: 15px; }

        /* --- CONTENT SECTION LAYOUT --- */
        .main-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; }
        .topbar { display: flex; justify-content: flex-end; align-items: center; padding: 20px 40px; gap: 25px; border-bottom: 1px solid #f0f0f0; }
        .topbar .notification { font-size: 20px; color: #888; }
        .topbar .user-profile { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }

        /* Perbaikan: Content body meluas penuh agar judul bisa mepet ke kiri */
        .content-body { 
            padding: 40px 60px; 
            width: 100%; 
            box-sizing: border-box; 
        }
        
        /* Judul "Tips" murni di pojok kiri atas */
        .content-title { 
            font-weight: 800; 
            font-size: 28px; 
            color: #000; 
            text-align: left; 
            margin-bottom: 10px; 
        }
        
        /* Pembungkus komponen utama form agar tetap berada di tengah */
        .form-center-wrapper {
            max-width: 650px;
            margin: 0 auto;
            text-align: center;
        }
        
        /* Modifikasi Ilustrasi Gambar Box Sayur Besar */
        .illustration-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 25px;
        }
        .illustration-img { 
            width: 480px; 
            max-width: 100%; 
            border-radius: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .feature-heading { font-size: 24px; font-weight: 700; color: #1e4620; margin-bottom: 25px; }

        /* --- SUMMARY BOXES --- */
        .summary-amount {
            background: #fff5e6;
            color: #1e4620;
            font-size: 22px;
            font-weight: 800;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #f3e1c3;
            margin-bottom: 15px;
            text-align: center;
        }
        .summary-message {
            background: #f8f9fa;
            color: #444;
            font-size: 14px;
            font-weight: 600;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* --- METODE PEMBAYARAN --- */
        .payment-section-title { font-size: 18px; font-weight: 700; color: #1e4620; text-align: left; margin-bottom: 15px; }
        .payment-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 30px; }
        
        .payment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s;
        }
        .payment-item:hover { border-color: #46854d; background: #fafdfa; }
        .payment-item.selected { border-color: #46854d; background: rgba(70, 133, 77, 0.02); box-shadow: 0 0 0 1px #46854d; }
        
        .payment-left { display: flex; align-items: center; gap: 20px; }
        .payment-icon { width: 28px; text-align: center; color: #46854d; font-size: 20px; display: flex; align-items: center; justify-content: center; }
        .payment-name { font-size: 15px; font-weight: 700; color: #333; text-align: left; }
        .payment-desc { font-size: 13px; color: #888; font-weight: 400; margin-left: 5px; }
        .payment-arrow { color: #aaa; font-size: 14px; }

        /* --- BUTTONS --- */
        .btn-pay {
            width: 100%;
            background: #46854d;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(70, 133, 77, 0.2);
            transition: 0.3s;
        }
        .btn-pay:hover { background: #396d3e; }
        .footer-note { color: #888; font-size: 14px; margin-top: 25px; font-style: italic; }
    </style>
</head>
<body x-data="{ metodeSelected: 'qris' }">

<div class="container">
    <div class="sidebar">
        <div class="sidebar-brand">
            <span style="font-weight: 800; font-size: 20px; color: #5b3a1e;">FoodLink</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="{{ route('riwayat-donasi.index') }}"><i class="fas fa-history"></i> Riwayat Donasi</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Riwayat Koordinasi</a></li>
            <li><a href="#"><i class="fas fa-file-alt"></i> Bukti Donasi</a></li>
        </ul>
        <a href="#" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <i class="far fa-bell notification"></i>
            <img src="https://via.placeholder.com/40" class="user-profile" alt="User Profile">
        </div>

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
</div>

</body>
</html>