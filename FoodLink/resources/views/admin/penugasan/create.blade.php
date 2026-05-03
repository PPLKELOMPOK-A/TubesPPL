<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tambah Penugasan Relawan</title>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body {
    margin: 0;
    font-family: Arial;
    display: flex;
    background: #f5f5f5;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    background: #e6cfa7;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px 15px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    margin-bottom: 10px;
    text-decoration: none;
    color: black;
    border-radius: 8px;
}

.menu a.active {
    background: #5a3e1b;
    color: white;
}

.menu a:hover {
    background: #d2b48c;
}

/* LOGOUT */
.logout button {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    width: 100%;
    border: none;
    background: none;
    cursor: pointer;
    border-radius: 8px;
}

.logout button:hover {
    background: #d2b48c;
}

/* CONTENT */
.content {
    flex: 1;
}

/* TOPBAR */
.topbar {
    background: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.top-icons {
    display: flex;
    align-items: center;
    gap: 15px;
}

.top-icons i {
    width: 22px;
    height: 22px;
    color: #555;
}

.profile-img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
}

/* BACKGROUND */
.bg-wrapper {
    background:  url('/img/BackgroundCreate.png') center/cover;
    height: calc(100vh - 70px);
}

.bg-overlay {
    background: rgba(255,255,255,0.4);
    backdrop-filter: blur(px);
    height: 100%;
    padding-top: 30px;
}

/* ✅ HEADER: tengah, icon gede */
.header {
    background: #6b4f1d;
    color: white;
    width: fit-content;
    min-width: 320px;
    border-radius: 10px;
    padding: 16px 28px;
    margin: 0 auto; /* ✅ center horizontal */

    display: flex;
    align-items: center;
    justify-content: center; /* ✅ isi header center */
    gap: 16px;

    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* ✅ ICON GEDE */
.header i {
    width: 42px;
    height: 42px;
    stroke-width: 2;
    flex-shrink: 0;
}


/* TEXT HEADER */
.header-text h2 {
    margin: 0;
    font-size: 18px;
}

.header-text p {
    margin: 3px 0 0;
    font-size: 13px;
}

/* FORM */
.container {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 30px;
}

/* CARD */
.card {
    background: #eee;
    padding: 30px;
    border-radius: 15px;
    width: 350px;
}

/* INPUT */
input {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

/* BUTTON */
.submit-wrapper {
    display: flex;
    justify-content: flex-end;
    width: 750px;
    margin: 20px auto 0;
}

button.submit-btn {
    background: #6b4f1d;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="menu">
        <a href="/admin/dashboard">
            <i data-lucide="home"></i> Beranda
        </a>

        <a href="#">
            <i data-lucide="file-check"></i> Validasi Donasi
        </a>

        <a href="#">
            <i data-lucide="mail"></i> Chat
        </a>

        <a href="#">
            <i data-lucide="corner-down-left"></i> Retur Donasi
        </a>

        <a href="/admin/penugasan" class="active">
            <i data-lucide="users"></i> Penugasan Relawan
        </a>
    </div>

    <div class="logout">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">
                <i data-lucide="log-out"></i> Logout
            </button>
        </form>
    </div>

</div>

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar">
        <h2>Tambah Penugasan Relawan</h2>

        <div class="top-icons">
            <i data-lucide="bell"></i>
            <img src="https://i.pravatar.cc/40" class="profile-img">
        </div>
    </div>

    <!-- BACKGROUND -->
    <div class="bg-wrapper">
        <div class="bg-overlay">

            <!-- HEADER -->
            <div class="header">
                <i data-lucide="user-plus"></i>

                <div class="header-text">
                    <h2>Tambah Penugasan Relawan</h2>
                    <p>Lengkapi data untuk menambahkan relawan ke dalam sistem</p>
                </div>
            </div>

            <!-- FORM -->
            <form action="{{ route('penugasan.store') }}" method="POST">
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

            <!-- BUTTON -->
            <div class="submit-wrapper">
                <button type="submit" class="submit-btn">Submit</button>
            </div>

            </form>

        </div>
    </div>

</div>

<script>
lucide.createIcons();
</script>

</body>
</html>