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

        <!-- NOTIFIKASI -->
        @if(session('success'))
            <div style="color:green">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div style="color:red">{{ session('error') }}</div>
        @endif

        <!-- STATISTIK -->
        <div class="stats">
            <div class="stat-card">
                <h2>{{ \App\Models\Donation::where('status','menunggu')->count() }}</h2>
                <p>Menunggu</p>
            </div>

            <div class="stat-card success">
                <h2>{{ \App\Models\Donation::where('status','disetujui')->count() }}</h2>
                <p>Disetujui</p>
            </div>

            <div class="stat-card danger">
                <h2>{{ \App\Models\Donation::where('status','ditolak')->count() }}</h2>
                <p>Ditolak</p>
            </div>
        </div>

        <!-- LIST DATA (REAL DATABASE) -->
        @forelse($donations as $item)
        <div class="card-donasi">

            <!-- Gambar -->
            <div class="thumb"></div>

            <!-- Info -->
            <div class="info">
                <h4>{{ $item->judul }}</h4>
                <p>{{ $item->kategori }}</p>
                <small>{{ $item->created_at->format('d M Y H:i') }}</small>

                <div class="badge-group">
                    <span class="badge">{{ $item->quantity ?? 0 }} Porsi</span>
                    <span class="badge gray">Layak konsumsi</span>
                </div>
            </div>

            <!-- Status DINAMIS -->
            <div class="status">
                {{ strtoupper($item->status) }}
            </div>

            <!-- Action -->
            <div class="action">

                <!-- SETUJUI -->
                <form action="{{ route('validasi.setujui', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn green"
                        onclick="return confirm('Setujui donasi ini?')">
                        Setujui
                    </button>
                </form>

                <!-- TOLAK -->
                <form action="{{ route('validasi.tolak', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn red"
                        onclick="return confirm('Tolak donasi ini?')">
                        Tolak
                    </button>
                </form>

                <!-- RETURN -->
                <form action="{{ route('validasi.return', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn gray"
                        onclick="return confirm('Kembalikan ke antrian?')">
                        Return
                    </button>
                </form>

            </div>

        </div>

        @empty
        <div class="empty">
            <h4>Tidak ada donasi yang perlu divalidasi</h4>
        </div>
        @endforelse

        <div class="pagination">
            Total: {{ $donations->count() }} data
        </div>

    </div>
</div>

</body>
</html>