<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Penugasan Relawan</title>

<!-- ICON -->
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
    font-size: 15px;
}

.menu a i {
    width: 20px;
    height: 20px;
}

.menu a:hover {
    background: #d2b48c;
}

.menu a.active {
    background: #5a3e1b;
    color: white;
}

/* LOGOUT */
.logout {
    margin-top: 20px;
}

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

.logout button:hover {
    background: #d2b48c;
}

/* CONTENT */
.content {
    flex: 1;
    padding: 0;
}

/* ===== TOPBAR BARU ===== */
.topbar {
    background: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.topbar h2 {
    margin: 0;
}

/* ICON KANAN */
.top-icons {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* ICON BULAT */
.icon-circle {
    width: 40px;
    height: 40px;
    background: #f1f1f1;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

/* ICON NOTIF */
.icon-circle i {
    width: 20px;
    height: 20px;
    color: #555;
}

/* PROFILE IMAGE */
.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
}

/* WRAPPER ISI */
.main-content {
    padding: 20px 30px;
}

/* BUTTON */
.action-bar {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}

.btn-add {
    background: #5a3e1b;
    color: white;
    padding: 10px 18px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
}

/* TABLE */
.table-box {
    background: white;
    padding: 15px;
    border-radius: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #5a3e1b;
    color: white;
    padding: 10px;
}

td {
    padding: 10px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

/* ACTION ICON */
.action-icons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

/* ALERT */
.alert {
    background: #d4edda;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}
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

    <!-- TOPBAR BARU -->
    <div class="topbar">
        <h2>Penugasan Relawan</h2>

        <div class="top-icons">
            <!-- NOTIF -->
             <div class="topbar-icons">
                <i data-lucide="bell"></i>
            </div>

            <!-- PROFILE -->
            <img src="https://i.pravatar.cc/100" class="profile-img">
        </div>
    </div>

    <div class="main-content">

        <!-- NOTIF -->
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

       <div class="action-bar">
    @if(isset($edit))
        <!-- MODE EDIT -->
        <form method="POST" action="/admin/penugasan/{{ $edit->id }}">
            @csrf
            @method('PUT')
            <button class="btn-add">Update</button>
        </form>
    @else
        <!-- MODE TAMBAH (PINDAH HALAMAN) -->
        <a href="{{ route('penugasan.create') }}">
            <button class="btn-add">Tambah Relawan</button>
        </a>
    @endif
</div>
        </div>

        <!-- TABLE -->
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>ID Penugasan</th>
                        <th>ID Donasi</th>
                        <th>Nama Donatur</th>
                        <th>Relawan</th>
                        <th>Lokasi Pengambilan</th>
                        <th>Lokasi Pengantaran</th>
                        <th>Tanggal Penugasan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $item->id_penugasan }}</td>
                        <td>{{ $item->id_donasi }}</td>
                        <td>{{ $item->nama_donatur }}</td>
                        <td>{{ $item->relawan }}</td>
                        <td>{{ $item->lokasi_pengambilan }}</td>
                        <td>{{ $item->lokasi_pengantaran }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d-m-Y') }}</td>

                        <td>
                            <div class="action-icons">
                                <a href="/admin/penugasan/edit/{{ $item->id }}">
                                    <i data-lucide="pencil"></i>
                                </a>

                                <form action="/admin/penugasan/{{ $item->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button style="border:none;background:none;">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
lucide.createIcons();
</script>

</body>
</html>