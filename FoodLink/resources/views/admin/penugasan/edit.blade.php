<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Edit Penugasan Relawan</title>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body {
    margin: 0;
    font-family: Arial;
    display: flex;
    background: #f5f5f5;
}

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
    font-size: 15px;
}

.menu a:hover { background: #d2b48c; }
.menu a.active { background: #5a3e1b; color: white; }

.logout { margin-top: 20px; }

.logout button {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: none;
    background: none;
    cursor: pointer;
    border-radius: 8px;
}

.logout button:hover { background: #d2b48c; }

.content { flex: 1; padding: 0; }

.topbar {
    background: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.topbar h2 { margin: 0; }

.top-icons {
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.main-content { padding: 20px 30px; }

/* CARD FORM */
.form-card {
    background: white;
    padding: 25px 30px;
    border-radius: 10px;
}

.form-title {
    font-size: 16px;
    font-weight: bold;
    color: #5a3e1b;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px 30px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

label {
    font-size: 12px;
    color: #666;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

input[type="text"],
input[type="date"] {
    padding: 9px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 13px;
    font-family: Arial;
    outline: none;
}

input[type="text"]:focus,
input[type="date"]:focus {
    border-color: #5a3e1b;
}

.field-readonly {
    background: #f0ede8;
    color: #999;
    cursor: not-allowed;
}

.error-msg {
    color: red;
    font-size: 12px;
}

/* ALERT */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 10px 16px;
    border-radius: 6px;
    margin-bottom: 16px;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px 16px;
    border-radius: 6px;
    margin-bottom: 16px;
}

/* BUTTONS */
.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.btn-update {
    padding: 10px 24px;
    background: #5a3e1b;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
}

.btn-update:hover { background: #3e2a10; }

.btn-cancel {
    padding: 10px 20px;
    background: white;
    color: #5a3e1b;
    border: 1px solid #ccc;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
}

.btn-cancel:hover { background: #f5f5f5; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="menu">
        <a href="/admin/dashboard">
            <i data-lucide="home"></i>
            Beranda
        </a>
        <a href="#">
            <i data-lucide="file-check"></i>
            Validasi Donasi
        </a>
        <a href="#">
            <i data-lucide="mail"></i>
            Chat
        </a>
        <a href="#">
            <i data-lucide="corner-down-left"></i>
            Retur Donasi
        </a>
        <a href="/admin/penugasan" class="active">
            <i data-lucide="users"></i>
            Penugasan Relawan
        </a>
    </div>

    <div class="logout">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">
                <i data-lucide="log-out"></i>
                Logout
            </button>
        </form>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar">
        <h2>Edit Penugasan Relawan</h2>
        <div class="top-icons">
            <i data-lucide="bell"></i>
            <img src="https://i.pravatar.cc/100" class="profile-img">
        </div>
    </div>

    <div class="main-content">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left:1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <div class="form-title">Data Penugasan #{{ $penugasan->id_penugasan }}</div>

            <form action="/admin/penugasan/{{ $penugasan->id }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group">
                        <label>ID Penugasan</label>
                        <input type="text" name="id_penugasan"
                               value="{{ old('id_penugasan', $penugasan->id_penugasan) }}"
                               class="field-readonly" readonly />
                    </div>

                    <div class="form-group">
                        <label>ID Donasi</label>
                        <input type="text" name="id_donasi"
                               value="{{ old('id_donasi', $penugasan->id_donasi) }}"
                               class="field-readonly" readonly />
                    </div>

                    <div class="form-group">
                        <label>Nama Donatur</label>
                        <input type="text" name="nama_donatur"
                               value="{{ old('nama_donatur', $penugasan->nama_donatur) }}" />
                        @error('nama_donatur')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Relawan</label>
                        <input type="text" name="relawan"
                               value="{{ old('relawan', $penugasan->relawan) }}" />
                        @error('relawan')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Lokasi Pengambilan</label>
                        <input type="text" name="lokasi_pengambilan"
                               value="{{ old('lokasi_pengambilan', $penugasan->lokasi_pengambilan) }}" />
                    </div>

                    <div class="form-group">
                        <label>Lokasi Pengantaran</label>
                        <input type="text" name="lokasi_pengantaran"
                               value="{{ old('lokasi_pengantaran', $penugasan->lokasi_pengantaran) }}" />
                        @error('lokasi_pengantaran')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tanggal Penugasan</label>
                        <input type="date" name="tanggal_penugasan"
                               value="{{ old('tanggal_penugasan', $penugasan->tanggal_penugasan) }}" />
                        @error('tanggal_penugasan')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn-update">Update</button>
                    <a href="/admin/penugasan" class="btn-cancel">Batal</a>
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