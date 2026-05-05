<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Validasi Proses Donasi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin</h3>
        <ul>
            <li class="active">Validasi Donasi</li>
            <li><a href="{{ route('validasi.disetujui') }}">Disetujui</a></li>
            <li><a href="{{ route('validasi.ditolak') }}">Ditolak</a></li>
        </ul>

        <div class="logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button>Logout</button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Validasi Proses Donasi</h2>
        <p class="sub">Kelola dan verifikasi donasi yang masuk</p>

        <!-- 🔥 STATISTIK -->
        <div style="display:flex; gap:20px; margin:20px 0;">
            <div style="background:#f3e5d0; padding:15px; border-radius:10px; text-align:center;">
                <h2>{{ \App\Models\Donation::where('status','menunggu')->count() }}</h2>
                <p>Menunggu</p>
            </div>

            <div style="background:#d4edda; padding:15px; border-radius:10px; text-align:center;">
                <h2>{{ \App\Models\Donation::where('status','disetujui')->count() }}</h2>
                <p>Disetujui</p>
            </div>

            <div style="background:#f8d7da; padding:15px; border-radius:10px; text-align:center;">
                <h2>{{ \App\Models\Donation::where('status','ditolak')->count() }}</h2>
                <p>Ditolak</p>
            </div>
        </div>

        <!-- Search -->
        <input type="text" class="search" placeholder="Search">

        <!-- List -->
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
                <span style="background:orange;color:white;padding:4px 10px;border-radius:5px;">
                    MENUNGGU
                </span>
            </div>

            <!-- Action -->
            <div class="action">

                <!-- Setujui -->
                <form action="{{ route('validasi.setujui', $item->id) }}" method="POST">
                    @csrf
                    <button onclick="return confirm('Setujui donasi ini?')" class="btn-primary">
                        Setujui
                    </button>
                </form>

                <!-- Tolak -->
                <form action="{{ route('validasi.tolak', $item->id) }}" method="POST">
                    @csrf
                    <button onclick="return confirm('Tolak donasi ini?')" class="btn-danger">
                        Tolak
                    </button>
                </form>

                <!-- Return -->
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
            <h4>Tidak ada donasi yang perlu divalidasi</h4>
        </div>
        @endforelse

        <!-- Pagination -->
        <div class="pagination">
            <span>Total: {{ count($donations) }} data</span>
        </div>

    </div>
</div>

</body>
</html>