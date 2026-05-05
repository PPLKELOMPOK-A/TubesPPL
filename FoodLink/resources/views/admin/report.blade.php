<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan - FoodLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

    <div class="p-6 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Laporan FoodLink</h1>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-gray-500 text-sm font-medium uppercase">Total Donasi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_donations']) }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 shadow-sm">
                    <p class="text-gray-500 text-sm font-medium uppercase">Total Berat Terkumpul</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_weight'], 1) }} <span class="text-lg font-normal text-gray-500">Kg</span></p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-gray-500 text-sm font-medium uppercase">Relawan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_volunteers'] }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-gray-500 text-sm font-medium uppercase">Distribusi Selesai</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['completed_donations'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Tren Donasi Bulanan</h2>
                    <span class="text-xs font-medium bg-green-100 text-green-700 px-3 py-1 rounded-full">Data 6 Bulan Terakhir</span>
                </div>
                
                <div class="relative" style="height: 400px;">
                    <canvas id="donationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('donationChart').getContext('2d');
            
            // Mengambil data dari Laravel
            const labels = {!! json_encode($monthlyData->pluck('month')) !!};
            const dataCounts = {!! json_encode($monthlyData->pluck('total')) !!};

            // Jika data kosong, tampilkan placeholder
            if (labels.length === 0) {
                labels.push('Belum ada data');
                dataCounts.push(0);
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Donasi Masuk',
                        data: dataCounts,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10B981',
                        pointRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            padding: 12,
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>