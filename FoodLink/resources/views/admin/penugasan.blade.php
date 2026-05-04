<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Penugasan Relawan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            display: flex;
            background: #f5f5f5;
        }

        .sidebar {
            width: 220px;
            background: #e6cfa7;
            height: 100vh;
            padding: 20px;
        }

        .menu a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            color: black;
        }

        .menu a:hover {
            background: #d2b48c;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background: #5a3e1b;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #5a3e1b;
            color: white;
            padding: 10px;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .btn {
            border: none;
            padding: 5px 8px;
            cursor: pointer;
        }

        .edit {
            background: orange;
            color: white;
        }

        .delete {
            background: red;
            color: white;
        }

        .form-box {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
        }

        input {
            padding: 8px;
            margin: 5px;
        }

        .submit-btn {
            margin-top: 10px;
            background: #5a3e1b;
            color: white;
            padding: 10px 20px;
            border: none;
        }

        .alert {
            background: lightgreen;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Dashboard</h3>
    <div class="menu">
        <a href="/admin/dashboard">Dashboard</a>
        <a href="#">Validasi Donasi</a>
        <a href="#">Chat</a>
        <a href="#">Data Organisasi</a>
        <a href="/admin/penugasan">Penugasan Relawan</a>
    </div>
</div>

<!-- Content -->
<div class="content">

    <div class="header">
        <strong>Admin - Penugasan Relawan</strong>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM TAMBAH / EDIT -->
    <div class="form-box">
        <form method="POST"
              action="{{ isset($edit) ? '/admin/penugasan/'.$edit->id : '/admin/penugasan' }}">
            @csrf

            @if(isset($edit))
                @method('PUT')
            @endif

            <button class="submit-btn">
                {{ isset($edit) ? 'Update' : 'Tambah Karyawan' }}
            </button>
        </form>
    </div>

    <!-- TABEL -->
    <h3>Data Penugasan</h3>

    <table>
        <thead>
            <tr>
                <th>ID Penugasan</th>
                <th>ID Donasi</th>
                <th>Nama Donatur</th>
                <th>Relawan</th>
                <th>Lokasi Pengambilan</th>
                <th>Lokasi Pengantaran</th>
                <th>Tanggal</th>
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
                    <a href="/admin/penugasan/edit/{{ $item->id }}" class="btn edit">Edit</a>

                    <form action="/admin/penugasan/{{ $item->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn delete">Hapus</button>
                    </form>
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

</body>
</html>