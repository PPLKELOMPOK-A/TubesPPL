@extends('layouts.app') {{-- Sesuaikan dengan nama file master layout Anda --}}

@section('title', 'Foodlink - Dashboard Laporan')

@section('content')
<!-- Memuat Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Memuat html2pdf.js untuk fitur unduh PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* --- OVERRIDE BACKGROUND MAIN PANEL --- */
    .main-panel {
        background-color: #FFF8F3 !important;
    }

    /* --- DASHBOARD WRAPPER --- */
    .dashboard-wrapper {
        padding: 40px 50px;
        display: flex;
        flex-direction: column;
        gap: 30px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* --- HEADER DASHBOARD --- */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .header-title h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 8px;
        font-weight: 700;
    }
    .header-title p {
        color: #777;
        font-size: 14px;
    }
    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .btn-outline, .btn-primary {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-outline {
        background: #fff;
        border: 1px solid #ddd;
        color: #333;
    }
    .btn-primary {
        background: #5C4033;
        border: 1px solid #5C4033;
        color: #fff;
    }

    /* --- KPI GRID --- */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .kpi-card {
        background: #FFF2E5;
        padding: 25px 20px;
        border-radius: 12px;
        position: relative;
    }
    .kpi-card h3 {
        font-size: 11px;
        text-transform: uppercase;
        color: #666;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }
    .kpi-card .value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
    .kpi-card .trend {
        font-size: 12px;
        font-weight: 600;
    }
    .trend.positive { color: #2E7D32; }
    .trend.negative { color: #D32F2F; }
    .kpi-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 35px;
        height: 35px;
        background: rgba(92, 64, 51, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #5C4033;
    }

    /* --- CHARTS SECTION --- */
    .charts-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }
    .chart-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
    }
    .chart-header {
        margin-bottom: 25px;
    }
    .chart-header h3 {
        font-size: 13px;
        text-transform: uppercase;
        color: #333;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .chart-header p {
        font-size: 12px;
        color: #888;
    }

    /* Segmentasi Bars */
    .segment-item { margin-bottom: 20px; }
    .segment-info {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }
    .segment-info span:last-child { color: #333; }
    .progress-bar-bg {
        width: 100%;
        height: 10px;
        background: #F0E6DA;
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 5px;
    }

    /* --- TABLE SECTION --- */
    .table-container {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    .table-header {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }
    .table-header h3 {
        font-size: 14px;
        text-transform: uppercase;
        color: #333;
        letter-spacing: 0.5px;
    }
    .table-header a {
        font-size: 12px;
        color: #5C4033;
        font-weight: 600;
        text-decoration: none;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 15px 25px;
        text-align: left;
        font-size: 13px;
    }
    th {
        background: #FFFBF7;
        color: #777;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
    }
    td {
        color: #333;
        font-weight: 500;
        border-bottom: 1px solid #f9f9f9;
    }
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge.terkirim { background: #E8F5E9; color: #2E7D32; }
    .badge.proses { background: #FFF3E0; color: #E65100; }
    .badge.retur { background: #FFEBEE; color: #C62828; }
</style>

<div class="dashboard-wrapper" id="pdf-content">
    
    <!-- Bagian Header -->
    <div class="dashboard-header">
        <div class="header-title">
            <h1>Dashboard Logistik Pangan</h1>
            <p>Tinjauan performa penyaluran donasi dan metrik operasional secara real-time.</p>
        </div>
        <div class="header-actions">
            <!-- Form Filter Rentang Waktu -->
            <form action="{{ route('admin.report.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <input type="date" name="start_date" value="{{ request('start_date') }}" required style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; color: #555; outline: none;">
                <span style="color: #888;">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" required style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; color: #555; outline: none;">
                <button type="submit" class="btn-outline"><i class="fa-solid fa-filter"></i> Filter</button>
                
                {{-- Tombol Reset muncul jika data sedang difilter --}}
                @if(request()->has('start_date'))
                    <a href="{{ route('admin.report.index') }}" class="btn-outline" style="color: #d9534f; border-color: #d9534f; text-decoration: none;">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </form>

            <!-- Tombol Unduh Laporan PDF -->
            <button class="btn-primary" onclick="downloadPDF()"><i class="fa-solid fa-download"></i> Unduh Laporan</button>
        </div>
    </div>

    <!-- Bagian KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
            <h3>Total Transaksi Berhasil</h3>
            <div class="value">{{ $totalBerhasil ?? 0 }}</div>
            <div class="trend positive"><i class="fa-solid fa-arrow-trend-up"></i> Terkalkulasi otomatis</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
            <h3>Penerima Manfaat</h3>
            <div class="value">{{ $penerimaManfaat ?? 0 }} <span style="font-size:16px;">Donasi</span></div>
            <div class="trend positive"><i class="fa-solid fa-arrow-trend-up"></i> Jangkauan donasi sukses</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fa-solid fa-arrow-rotate-left"></i></div>
            <h3>Tingkat Donasi Diretur</h3>
            <div class="value">{{ $persentaseRetur ?? 0 }}%</div>
            <div class="trend {{ ($persentaseRetur ?? 0) < 5 ? 'positive' : 'negative' }}">
                <i class="fa-solid fa-arrow-trend-down"></i> Evaluasi sistem
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon"><i class="fa-solid fa-user-check"></i></div>
            <h3>Partisipasi Pengguna Aktif</h3>
            <div class="value">{{ $penggunaAktif ?? 0 }}</div>
            <div class="trend positive"><i class="fa-solid fa-arrow-trend-up"></i> Donatur unik</div>
        </div>
    </div>

    <!-- Bagian Charts -->
    <div class="charts-grid">
        
        <!-- Grafik Garis -->
        <div class="chart-box">
            <div class="chart-header">
                <h3>Tren Penyaluran Makanan (6 Bulan)</h3>
                <p>Volume transaksi donasi sukses per bulan</p>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Segmentasi Progress Bar -->
        <div class="chart-box">
            <div class="chart-header">
                <h3>Segmentasi Alokasi Pangan</h3>
            </div>
            
            @forelse($segmentasi ?? [] as $seg)
                @php
                    // Amankan data: cek apakah $seg berupa Array atau Object dari Controller
                    $kategori = is_array($seg) ? ($seg['kategori'] ?? 'Umum') : ($seg->kategori ?? 'Umum');
                    $persentase = is_array($seg) ? ($seg['persentase'] ?? 0) : ($seg->persentase ?? 0);
                    $total = is_array($seg) ? ($seg['total'] ?? 0) : ($seg->total ?? 0);

                    // Variasi warna
                    $colors = ['#6B4F2A', '#9E7D5A', '#C8B097', '#EEDAC5'];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div class="segment-item">
                    <div class="segment-info">
                        <span><i class="fa-solid fa-building" style="color:#888; margin-right:5px;"></i> {{ $kategori }}</span>
                        <span>{{ $persentase }}% / {{ $total }} Transaksi</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-fill" style="width: {{ $persentase }}%; background: {{ $color }};"></div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #888; padding: 20px 0;">
                    Belum ada data distribusi.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bagian Tabel Sesuai Kesepakatan -->
    <div class="table-container">
        <div class="table-header">
            <h3>Log Penyaluran Pangan Terakhir</h3>
            <a href="#">Lihat Semua Log</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Log</th>
                    <th>Kategori Penerima</th>
                    <th>Tanggal</th>
                    <th>Status Pengiriman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logPenyaluran ?? [] as $log)
                <tr>
                    <td>#LOG-{{ $log->id ?? '-' }}</td>
                    <td>{{ $log->kategori_penerima ?? 'Umum' }}</td>
                    <td>{{ isset($log->created_at) ? \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') : '-' }}</td>
                    <td>
                        @if(($log->status ?? '') == 'selesai')
                            <span class="badge terkirim">Selesai</span>
                        @elseif(($log->status ?? '') == 'diproses')
                            <span class="badge proses">Proses</span>
                        @elseif(($log->status ?? '') == 'diretur')
                            <span class="badge retur">Diretur</span>
                        @elseif(($log->status ?? '') == 'pending')
                            <span class="badge" style="background:#FFF3E0; color:#E65100;">Pending</span>
                        @else
                            <span class="badge" style="background:#eee; color:#666;">{{ ucfirst($log->status ?? 'Menunggu') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color:#888; padding: 30px;">Belum ada log penyaluran donasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- Script Inisiasi Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('lineChart').getContext('2d');
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(107, 79, 42, 0.4)');
        gradient.addColorStop(1, 'rgba(107, 79, 42, 0)');

        // Mengambil variabel PHP untuk grafik (Lebih aman menggunakan json_encode)
        const labelsData = {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!};
        const chartValues = {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Transaksi Sukses',
                    data: chartValues,
                    borderColor: '#5C4033',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    pointBackgroundColor: '#5C4033',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false, drawBorder: false },
                        ticks: { display: false } 
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#888', font: { size: 12 } }
                    }
                }
            }
        });
    });

    // --- SCRIPT UNTUK UNDUH PDF ---
    function downloadPDF() {
        // 1. Sembunyikan bagian header-actions agar tidak ikut tercetak
        const actionArea = document.querySelector('.header-actions');
        actionArea.style.display = 'none';

        // 2. Pilih elemen wrapper dashboard
        const element = document.getElementById('pdf-content');

        // 3. Konfigurasi PDF
        const opt = {
            margin:       [10, 10, 10, 10],
            filename:     'Laporan_Logistik_FoodLink.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true }, 
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        // 4. Render ke PDF lalu kembalikan tombol filter
        html2pdf().set(opt).from(element).save().then(() => {
            actionArea.style.display = 'flex';
        });
    }
</script>
@endsection