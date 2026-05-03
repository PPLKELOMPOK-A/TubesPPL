<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donasi Disetujui</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin</h3>
        <ul>
            <li><a href="{{ route('validasi.index') }}">Validasi Donasi</a></li>
            <li class="active">Disetujui</li>
            <li><a href="{{ route('validasi.ditolak') }}">Ditolak</a></li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Donasi Disetujui</h2>
        <p class="sub">Daftar donasi yang telah disetujui</p>

        @forelse($donations as $item)
        <div class="card">

            <!-- Gambar -->
            <img src="{{ asset('images/default.png') }}" class="thumb">

            <!-- Info -->
            <div class="info">
                <h4>{{ $item->nama_makanan }}</h4>
                <p>{{ $item->donatur }}</p>
                <small>{{ $item->created_at->format('d M Y H:i') }}</small>

                <br><br>

                <!-- Badge -->
                <span style="background:green;color:white;padding:4px 10px;border-radius:5px;">
                    DISETUJUI
                </span>
            </div>

            <!-- Action -->
            <div class="action">
                <form action="{{ route('validasi.return', $item->id) }}" method="POST">
                    @csrf
                    <button onclick="return confirm('Kembalikan ke antrian?')" class="btn-secondary">
                        Return
                    </button>
                </form>
            </div>

        </div>

        @empty
        <div style="text-align:center; margin-top:30px;">
            <h4>Tidak ada donasi yang disetujui</h4>
        </div>
        @endforelse

        <!-- Info jumlah data -->
        <div class="pagination">
            <span>Total: {{ count($donations) }} data</span>
        </div>

    </div>
</div>

</body>
</html>