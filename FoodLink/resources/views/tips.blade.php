<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tips - FoodLink</title>
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
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            margin-bottom: 20px;
        }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; width: 100%; flex-grow: 1; }
        .sidebar-menu li { margin-bottom: 10px; width: 100%; }
        .sidebar-menu li a { 
            display: flex; 
            align-items: center;
            gap: 15px;
            padding: 15px 20px; 
            text-decoration: none; 
            color: #5b3a1e; 
            font-size: 14px; 
            font-weight: 600; 
            border-radius: 12px; 
            transition: 0.3s;
        }
        .sidebar-menu li.active a { background: #5b3a1e; color: white !important; }
        .sidebar-menu li a:hover:not(.active) { background: rgba(91, 58, 30, 0.05); }
        .logout { color: #5b3a1e; font-weight: bold; cursor: pointer; text-decoration: none; padding: 20px; display: flex; align-items: center; gap: 15px; }

        /* --- MAIN CONTENT & TOPBAR --- */
        .main-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; }
        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 20px 40px;
            gap: 25px;
            border-bottom: 1px solid #f0f0f0;
        }
        .topbar .notification { font-size: 20px; color: #888; cursor: pointer; }
        .topbar .user-profile { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }

        /* --- LAYOUT UTAMA (Teks Kiri, Form Center) --- */
        .content-body { 
            padding: 40px 60px; 
            width: 100%; 
            box-sizing: border-box; 
        }
        
        /* Judul "Tips" murni mepet di pojok kiri atas */
        .content-title { 
            font-weight: 800; 
            font-size: 28px; 
            color: #000; 
            text-align: left;
            margin-bottom: 10px; 
        }
        
        /* Pembungkus form utama agar berada di tengah halaman */
        .form-center-wrapper {
            max-width: 650px;
            margin: 0 auto;
            text-align: center;
        }
        
        /* Mengubah ukuran gambar ilustrasi menjadi besar sesuai mockup */
        .illustration-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 30px;
        }
        .illustration-img { 
            width: 480px; 
            max-width: 100%;
            border-radius: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .feature-heading { 
            font-size: 26px; 
            font-weight: 700; 
            color: #1e4620; 
            margin: 15px 0 10px 0; 
        }
        .feature-desc { 
            color: #555; 
            font-size: 15px; 
            line-height: 1.6; 
            margin: 0 auto 35px auto; 
        }

        /* --- NOMINAL & INPUT BOXES --- */
        .nominal-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; }
        .btn-nominal { 
            background: #f8f9fa; 
            border: 1px solid #e2e8f0; 
            color: #333; 
            padding: 14px; 
            border-radius: 10px; 
            font-size: 14px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.2s;
        }
        .btn-nominal.selected { background: #46854d; color: white; border-color: #46854d; }
        
        .input-custom, .input-message {
            width: 100%;
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            box-sizing: border-box;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .input-custom input, .input-message input {
            border: none;
            background: transparent;
            width: 100%;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #333;
        }

        .btn-submit {
            width: 100%;
            background: #46854d;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(70, 133, 77, 0.2);
            transition: 0.3s;
        }
        .btn-submit:hover { background: #396d3e; }
        .footer-note { color: #888; font-size: 14px; margin-top: 25px; font-style: italic; }
    </style>
</head>
<body x-data="{ 
    nominalSelected: 25000, 
    customNominal: '', 
    pesan: ''
}">

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
                <p class="feature-desc">
                    Bantu kami terus mengurangi food waste dan bantu sesama. Donasi tips anda akan digunakan untuk pengembangan dan operasional FoodLink.
                </p>

                @if ($errors->any())
                    <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; text-align: left;">
                        <i class="fas fa-exclamation-circle"></i> {{ $errors->first('amount') }}
                    </div>
                @endif

                <form action="{{ route('tips.proses') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" :value="customNominal ? customNominal : nominalSelected">

                    <div class="nominal-grid">
                        <button type="button" class="btn-nominal" :class="nominalSelected === 10000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 10000; customNominal = ''">RP. 10.000</button>
                        <button type="button" class="btn-nominal" :class="nominalSelected === 25000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 25000; customNominal = ''">RP. 25.000</button>
                        <button type="button" class="btn-nominal" :class="nominalSelected === 50000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 50000; customNominal = ''">RP. 50.000</button>
                        <button type="button" class="btn-nominal" :class="nominalSelected === 100000 && !customNominal ? 'selected' : ''" @click="nominalSelected = 100000; customNominal = ''">RP. 100.000</button>
                    </div>

                    <div class="input-custom">
                        <i class="fas fa-plus" style="color: #46854d; font-size: 14px;"></i>
                        <span style="font-weight: 700; color: #333; font-size: 14px;">RP</span>
                        <input type="number" x-model.number="customNominal" @input="nominalSelected = null" placeholder="Masukkan nominal lain...">
                    </div>

                    <div class="input-message">
                        <i class="far fa-smile" style="color: #46854d; font-size: 18px;"></i>
                        <input type="text" name="pesan" x-model="pesan" placeholder="Tambahkan pesan...">
                    </div>

                    <button type="submit" class="btn-submit">Lanjut Pembayaran</button>
                </form>
                   
                <div class="footer-note">Terima kasih untuk dukungan tulus anda!</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>