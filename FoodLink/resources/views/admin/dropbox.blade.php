@extends('layouts.admin')

@section('title', 'Drop Box')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-popup-content-wrapper { border-radius: 12px; }
    .leaflet-popup-content { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Drop Box Live Tracking</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $totalLokasi }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Total Lokasi</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $tersedia }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Tersedia</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $hampirPenuh }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Hampir Penuh</div>
        </div>
        <div class="bg-[#FFF9F0] p-6 rounded-2xl border border-[#FBEBCE]">
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $penuh }}</div>
            <div class="text-xs font-bold text-gray-600 uppercase tracking-widest">Penuh</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 h-96 relative mb-8 overflow-hidden shadow-sm z-0">
        <div id="map" class="w-full h-full"></div>
    </div>

    <div class="flex justify-between items-center mb-6 gap-4">
        <form action="{{ route('dropbox.index') }}" method="GET" class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Lokasi Drop Box..." class="w-full pl-12 pr-6 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-300 shadow-sm bg-white">
        </form>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-6 py-3 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 bg-white hover:bg-gray-50 shadow-sm transition">
            + Tambah Lokasi
        </button>
    </div>

    <div class="space-y-5">
        @forelse($dropboxes as $item)
        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row items-center gap-6">
            <div class="w-28 h-28 bg-[#FFF9F0] rounded-xl flex items-center justify-center flex-shrink-0 border border-[#FBEBCE]">
                <svg class="w-10 h-10 text-[#4299E1]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <div class="flex-1 w-full">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-xl font-bold text-gray-800">{{ $item->nama }}</h2>
                    @if($item->status == 'tersedia')
                        <span class="bg-[#C6F6D5] text-[#2F855A] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Tersedia</span>
                    @elseif($item->status == 'hampir_penuh')
                        <span class="bg-[#FEEBC8] text-[#C05621] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Hampir Penuh</span>
                    @else
                        <span class="bg-[#FED7D7] text-[#C53030] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Penuh</span>
                    @endif
                </div>
                
                <p class="text-xs text-gray-600 mb-1">{{ $item->lokasi }} · Mitra: {{ $item->mitra }}</p>
                
                @if($item->status == 'tersedia')
                    <p class="text-sm font-bold text-[#48BB78] mb-3">{{ $item->kapasitas }}%</p>
                @elseif($item->status == 'hampir_penuh')
                    <p class="text-sm font-bold text-[#ED8936] mb-3">{{ $item->kapasitas }}%</p>
                @else
                    <p class="text-sm font-bold text-[#F56565] mb-3">{{ $item->kapasitas }}%</p>
                @endif
                
                <p class="text-xs text-[#6B4F2A] font-bold bg-[#FDF4E3] inline-block px-3 py-1 rounded-lg shadow-sm">Status: {{ $item->update }}</p>
            </div>

            <div class="flex flex-col gap-3 w-full md:w-40">
                <button onclick="bukaDetail('{{ $item->nama }}', '{{ $item->lokasi }}', '{{ $item->mitra }}', '{{ $item->kapasitas }}', '{{ json_encode($item->history ?? []) }}')" class="bg-[#545454] text-white font-bold py-2.5 rounded-xl text-xs shadow-sm hover:bg-gray-700 transition">Detail</button>
                <button type="button" onclick="bukaModalJemput('{{ $item->id }}', '{{ $item->nama }}')" class="bg-[#6B4F2A] w-full text-white font-bold py-2.5 rounded-xl text-xs shadow-sm hover:bg-[#5A3D2B] transition">Jadwalkan Jemput</button>
            </div>
        </div>
        @empty
        <div class="text-center py-10 bg-white rounded-2xl border border-gray-100">
            <p class="text-gray-400 font-bold">Pencarian tidak ditemukan atau belum ada Drop Box.</p>
        </div>
        @endforelse
    </div>
</div>

<div id="modalJemput" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-8 bg-[#FFF9F0] border-b border-[#FBEBCE] flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800">Form Penjemputan</h3>
            <button type="button" onclick="document.getElementById('modalJemput').classList.add('hidden')" class="text-gray-400 text-3xl">&times;</button>
        </div>
        <form id="formJemput" method="POST" class="p-8 space-y-5">
            @csrf
            <p class="text-sm text-gray-500 font-medium mb-4">Lokasi: <span id="jemputNamaLoks" class="font-bold text-[#6B4F2A]"></span></p>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Relawan / Petugas</label>
                <input type="text" name="petugas" required placeholder="Cth: Relawan Budi" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mulai Penjemputan Jam</label>
                <p class="text-[10px] font-semibold text-orange-500 mb-2">*Trek animasi di peta akan otomatis berjalan mengikuti jalan asli</p>
                <input type="time" name="waktu" required class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-[#6B4F2A] text-white font-bold py-4 rounded-2xl shadow-lg mt-2">Mulai Penjemputan Otomatis</button>
        </form>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-8 bg-[#FFF9F0] border-b border-[#FBEBCE] flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800">Tambah Lokasi Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 text-3xl">&times;</button>
        </div>
        <form action="{{ route('dropbox.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Drop Box</label>
                <input type="text" name="nama" required placeholder="Cth: Drop Box Monas" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Detail</label>
                <input type="text" name="lokasi" required placeholder="Cth: Pintu Masuk Selatan" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Mitra Terkait</label>
                <input type="text" name="mitra" required placeholder="Cth: PT Makmur" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-[#6B4F2A] text-white font-bold py-4 rounded-2xl shadow-lg mt-2">Simpan Lokasi</button>
        </form>
    </div>
</div>

<div id="modalDetail" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-[#FDF4E3] rounded-2xl mx-auto flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-[#6B4F2A]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            </div>
            <h3 id="detNama" class="text-xl font-black text-gray-800 mb-2">Nama</h3>
            <p id="detLokasi" class="text-sm text-gray-500 mb-1">Lokasi</p>
            <p class="text-sm font-bold text-gray-700 mb-4">Mitra: <span id="detMitra"></span></p>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-4 text-left">
                <p class="text-xs text-gray-500 font-bold uppercase mb-2">Riwayat Penjemputan Selesai:</p>
                <ul id="detHistory" class="text-xs text-gray-700 space-y-2 list-disc pl-4 max-h-32 overflow-y-auto">
                </ul>
            </div>
            
            <button type="button" onclick="document.getElementById('modalDetail').classList.add('hidden')" class="w-full bg-gray-200 text-gray-800 font-bold py-3 rounded-xl">Tutup</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/polyline-encoded@0.0.9/Polyline.encoded.js"></script>

<script>
    // 1. Inisialisasi Peta (Center Jakarta)
    var map = L.map('map').setView([-6.2088, 106.8456], 11);
    
    // Tampilan Peta OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 2. Definisi Ikon Kustom
    
    // Ikon Motor Kurir Bawa Box
    var motorIcon = L.icon({
        iconUrl: 'https://img.icons8.com/color/100/motorcycle-delivery-single-box.png', 
        iconSize: [40, 40], // Ukuran diperbesar
        iconAnchor: [20, 40], // Titik jangkar di ban motor
        popupAnchor: [0, -40] // Posisi popup di atas motor
    });

    // Ikon Bendera Finish (Tujuan Antar)
    var finishIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/1053/1053155.png', 
        iconSize: [32, 32],
        iconAnchor: [6, 32], 
        popupAnchor: [10, -32]
    });

    // 3. Ambil data dropbox dari PHP ke JS
    var dropboxes = @json($dropboxes->values()); 

    // 4. Taruh Marker Drop Box Statis & Jalankan Logika Tracking
    dropboxes.forEach(function(db) {
        // Render Marker Drop Box Asli
        var color = db.status == 'tersedia' ? '#48BB78' : (db.status == 'hampir_penuh' ? '#ED8936' : '#F56565');
        L.circleMarker([db.lat, db.lng], {
            color: color, fillColor: color, fillOpacity: 0.8, radius: 8
        }).addTo(map)
        .bindPopup("<div class='text-center'><b class='text-sm'>" + db.nama + "</b><br><span class='text-xs text-gray-500'>" + db.kapasitas + "% Terisi</span></div>");

        // === LOGIKA LIVE TRACKING PADA JALAN ASLI ===
        if (db.active_task) {
            let task = db.active_task;
            let now = Math.floor(Date.now() / 1000); // Waktu saat ini (detik)

            // Hanya proses trek jika relawan belum selesai (masih dalam perjalanan)
            if (now >= task.waktu_mulai && now < task.waktu_selesai) {
                
                // Masukkan marker bendera finish di titik tujuan antar
                L.marker([task.lat_tujuan, task.lng_tujuan], {icon: finishIcon}).addTo(map)
                    .bindPopup("<b>Alamat Antar Donasi</b><br>Tujuan Akhir Relawan");

                // === FUNGSI AMBIL RUTE JALAN ASLI (GRATIS OSRM) ===
                let osrmUrl = `https://router.project-osrm.org/route/v1/driving/${task.lng_awal},${task.lat_awal};${db.lng},${db.lat};${task.lng_tujuan},${task.lat_tujuan}?overview=full`;

                fetch(osrmUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.code === 'Ok') {
                        // Decode geometri rute yang rumit dari OSRM menjadi titik-titik koordinat Leaflet
                        let ruteCoords = L.Polyline.fromEncoded(data.routes[0].geometry).getLatLngs();
                        
                        // Gambar garis rute jalan asli (abu-abu putus-putus)
                        let routeLine = L.polyline(ruteCoords, {color: '#888', dashArray: '5, 10', weight: 3}).addTo(map);

                        // Buat Marker Relawan dengan ikon MOTOR
                        let motorMarker = L.marker([task.lat_awal, task.lng_awal], {icon: motorIcon}).addTo(map)
                            .bindPopup("<b>Relawan: " + task.petugas + "</b><br>Sedang dalam perjalanan...");

                        // Loop Animasi real-time mengikuti trek jalan asli
                        setInterval(() => {
                            let currentNow = Math.floor(Date.now() / 1000);
                            
                            // Jika tugas sudah selesai (waktu komputermu melewati jam selesai)
                            if (currentNow >= task.waktu_selesai) {
                                map.removeLayer(motorMarker); // Hapus motor
                                map.removeLayer(routeLine);  // Hapus garis
                            } else {
                                // Hitung total durasi trek
                                let totalTaskDuration = task.waktu_selesai - task.waktu_mulai;
                                // Hitung berapa lama trek sudah berjalan
                                let elapsed = currentNow - task.waktu_mulai;
                                // Hitung % progres trek
                                let progress = elapsed / totalTaskDuration;

                                // Temukan indeks koordinat di dalam rute OSRM yang sesuai dengan progres % waktu
                                let indexTarget = Math.floor(ruteCoords.length * progress);
                                
                                // Pastikan index valid
                                if (indexTarget >= 0 && indexTarget < ruteCoords.length) {
                                    // Pindahkan motor ke titik jalan asli yang sesuai waktu saat ini!
                                    motorMarker.setLatLng(ruteCoords[indexTarget]);
                                }
                            }
                        }, 1000); // Update setiap 1 detik
                    }
                }).catch(e => console.error("OSRM Routing Error: ", e));
            }
        }
    });

    // Buka Modal Detail (History tidak akan terhapus)
    function bukaDetail(nama, lokasi, mitra, kapasitas, historyJson) {
        document.getElementById('detNama').textContent = nama;
        document.getElementById('detLokasi').textContent = lokasi;
        document.getElementById('detMitra').textContent = mitra;
        
        let history = JSON.parse(historyJson || "[]");
        let historyHtml = history.length > 0 
            ? history.map(h => "<li class='pb-1 font-semibold'>" + h + "</li>").join('') 
            : "<li class='text-gray-400 italic'>Belum ada riwayat penjemputan selesai.</li>";
            
        document.getElementById('detHistory').innerHTML = historyHtml;
        document.getElementById('modalDetail').classList.remove('hidden');
    }

    // Buka Modal Jemput
    function bukaModalJemput(id, namaLokasi) {
        document.getElementById('jemputNamaLoks').textContent = namaLokasi;
        document.getElementById('formJemput').action = "/admin/drop-box/" + id + "/jemput";
        document.getElementById('modalJemput').classList.remove('hidden');
    }
</script>
@endsection