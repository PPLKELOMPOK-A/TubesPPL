@extends('layouts.app')

@section('title', 'Drop Box - Foodlink')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Container Utama */
    .dropbox-canvas { padding: 40px 50px; background-color: #FFF9EE; min-height: 100vh; width: 100%; }
    .dropbox-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 28px; color: #1A1A1A; margin-bottom: 30px; }

    /* Card Statistik */
    .stats-container { display: flex; gap: 20px; margin-bottom: 30px; }
    .stat-card { background-color: #FEF3D1; border-radius: 12px; padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: center; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.02); }
    .stat-number { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 32px; color: #32220D; margin-bottom: 8px; }
    .stat-label { font-size: 13px; font-weight: 700; color: #4E453D; text-transform: uppercase; letter-spacing: 0.8px; }

    /* Map Container */
    .map-container { width: 100%; height: 400px; background-color: #FFFFFF; border-radius: 16px; border: 1px solid rgba(209, 196, 185, 0.3); box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.03); margin-bottom: 30px; z-index: 0; position: relative; padding: 5px; }
    #map { width: 100%; height: 100%; border-radius: 12px; z-index: 1; }
    .leaflet-popup-content-wrapper { border-radius: 12px; }
    .leaflet-popup-content { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Baris Pencarian & Tombol */
    .filter-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; }
    .search-box { position: relative; display: flex; align-items: center; flex: 1; max-width: 400px; }
    .search-box i { position: absolute; left: 16px; color: #A0AEC0; font-size: 14px; }
    .search-input { width: 100%; padding: 12px 16px 12px 40px; border-radius: 8px; border: 1px solid #E2E8F0; font-size: 13px; outline: none; color: #4A5568; }
    .btn-add { padding: 12px 24px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 13px; font-weight: 700; color: #1A202C; cursor: pointer; box-shadow: 0px 1px 2px rgba(0,0,0,0.02); transition: 0.2s; }
    .btn-add:hover { background-color: #F7FAFC; }

    /* List Drop Box */
    .dropbox-list { display: flex; flex-direction: column; gap: 20px; }
    .dropbox-card { background-color: #FFFFFF; border-radius: 16px; padding: 24px; display: flex; gap: 24px; box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.04); border: 1px solid rgba(209, 196, 185, 0.2); align-items: center; }
    
    .dropbox-icon { width: 100px; height: 100px; background-color: #FEF3D1; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #6B4F2A; flex-shrink: 0; }
    
    .dropbox-content { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .dropbox-header-row { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
    .dropbox-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 18px; color: #1A202C; margin: 0; }
    
    /* Status Badges */
    .badge-status { padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-tersedia { background-color: #C6F6D5; color: #2F855A; }
    .status-hampir_penuh { background-color: #FEEBC8; color: #C05621; }
    .status-penuh { background-color: #FED7D7; color: #C53030; }

    .dropbox-meta { font-size: 13px; color: #718096; margin-bottom: 12px; }
    .dropbox-kapasitas { font-size: 14px; font-weight: 700; margin-bottom: 12px; }
    .cap-tersedia { color: #48BB78; }
    .cap-hampir { color: #ED8936; }
    .cap-penuh { color: #F56565; }

    .dropbox-update { font-size: 12px; font-weight: 700; color: #6B4F2A; background-color: #FDF4E3; padding: 6px 14px; border-radius: 8px; display: inline-block; width: fit-content; }

    /* Tombol Aksi */
    .action-buttons { display: flex; flex-direction: column; gap: 12px; width: 160px; }
    .btn-action { width: 100%; padding: 12px 0; border-radius: 8px; font-weight: 700; font-size: 13px; border: none; color: #FFFFFF; cursor: pointer; text-align: center; text-decoration: none; transition: 0.2s; display: inline-block; }
    .btn-dark { background-color: #555555; }
    .btn-dark:hover { background-color: #333333; }
    .btn-brown { background-color: #6B4F2A; }
    .btn-brown:hover { background-color: #5A3D2B; }

    /* Modal Styling */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    .modal-content { background: #fff; padding: 35px; border-radius: 16px; width: 450px; max-width: 90%; display: flex; flex-direction: column; gap: 15px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .modal-close-btn { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 20px; color: #A0AEC0; cursor: pointer; }
    
    .modal-content input, .modal-content select { padding: 12px; border: 1px solid #E2E8F0; border-radius: 8px; font-family: inherit; font-size: 14px; outline: none; width: 100%; box-sizing: border-box; }
    .modal-content label { font-size: 13px; font-weight: 700; color: #4A5568; margin-bottom: -8px; }
    .modal-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 20px; color: #32220D; margin-bottom: 5px; border-bottom: 1px solid #FBEBCE; padding-bottom: 15px;}
    .history-list { font-size: 13px; color: #4A5568; padding-left: 20px; max-height: 150px; overflow-y: auto; list-style-type: none; }
</style>

<div class="dropbox-canvas">
    
    <h1 class="dropbox-title">Drop Box Live Tracking</h1>

    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number">{{ $totalLokasi ?? 0 }}</div>
            <div class="stat-label">TOTAL LOKASI</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $tersedia ?? 0 }}</div>
            <div class="stat-label">TERSEDIA</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $hampirPenuh ?? 0 }}</div>
            <div class="stat-label">HAMPIR PENUH</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $penuh ?? 0 }}</div>
            <div class="stat-label">PENUH</div>
        </div>
    </div>

    <div class="map-container">
        <div id="map"></div>
    </div>

    <div class="filter-row">
        <form action="{{ route('dropbox.index') }}" method="GET" class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" class="search-input" placeholder="Cari Lokasi Drop Box..." value="{{ request('search') }}">
        </form>
        <button onclick="document.getElementById('modalTambah').style.display='flex'" class="btn-add">
            + Tambah Lokasi
        </button>
    </div>

    <div class="dropbox-list">
        @forelse($dropboxes as $item)
            <div class="dropbox-card">
                
                <div class="dropbox-icon">
                    <i class="fa-solid fa-box"></i>
                </div>

                <div class="dropbox-content">
                    <div class="dropbox-header-row">
                        <h3 class="dropbox-name">{{ $item->nama }}</h3>
                        
                        @if($item->status == 'tersedia')
                            <div class="badge-status status-tersedia">Tersedia</div>
                        @elseif($item->status == 'hampir_penuh')
                            <div class="badge-status status-hampir_penuh">Hampir Penuh</div>
                        @else
                            <div class="badge-status status-penuh">Penuh</div>
                        @endif
                    </div>

                    <div class="dropbox-meta">
                        {{ $item->lokasi }} &nbsp;&middot;&nbsp; Mitra: {{ $item->mitra }}
                    </div>
                    
                    @if($item->status == 'tersedia')
                        <div class="dropbox-kapasitas cap-tersedia">{{ $item->kapasitas }} Terisi</div>
                    @elseif($item->status == 'hampir_penuh')
                        <div class="dropbox-kapasitas cap-hampir">{{ $item->kapasitas }} Terisi</div>
                    @else
                        <div class="dropbox-kapasitas cap-penuh">{{ $item->kapasitas }} Terisi</div>
                    @endif

                    <div class="dropbox-update">
                        Status: {{ $item->keterangan_status }}
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="btn-action btn-dark" 
                        data-nama="{{ $item->nama }}"
                        data-lokasi="{{ $item->lokasi }}"
                        data-mitra="{{ $item->mitra }}"
                        data-history="{{ json_encode($item->history ?? []) }}"
                        onclick="bukaDetail(this)">
                        Detail Riwayat
                    </button>
                    <button type="button" class="btn-action btn-brown" 
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama }}"
                        onclick="bukaModalJemput(this)">
                        Jadwalkan Jemput
                    </button>
                </div>

            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px solid #eee;">
                Tidak ada data Drop Box yang ditemukan.
            </div>
        @endforelse
    </div>

</div>


<div id="modalJemput" class="modal-overlay">
    <div class="modal-content">
        <button type="button" class="modal-close-btn" onclick="document.getElementById('modalJemput').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
        <h3 class="modal-title">Form Penjemputan</h3>
        
        <p style="font-size: 13px; color: #718096; margin-bottom: 10px;">
            Lokasi: <strong id="jemputNamaLoks" style="color: #6B4F2A;"></strong>
        </p>

        <form id="formJemput" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label>Nama Relawan / Petugas</label>
                <input type="text" name="petugas" required placeholder="Cth: Relawan Budi">
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label>Mulai Penjemputan Jam</label>
                <input type="time" name="waktu" required>
                <small style="color: #ED8936; font-size: 11px;">*Trek animasi motor akan berjalan otomatis mengikuti jalan (2 Menit)</small>
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: #6B4F2A; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px;">
                Mulai Penjemputan Otomatis
            </button>
        </form>
    </div>
</div>


<div id="modalTambah" class="modal-overlay">
    <form action="{{ route('dropbox.store') }}" method="POST" class="modal-content">
        @csrf
        <button type="button" class="modal-close-btn" onclick="document.getElementById('modalTambah').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
        <h3 class="modal-title">Tambah Lokasi Baru</h3>
        
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label>Nama Drop Box</label>
            <input type="text" name="nama" required placeholder="Cth: Drop Box Cempaka Putih">
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label>Lokasi Detail</label>
            <input type="text" name="lokasi" required placeholder="Cth: Pintu Masuk Selatan">
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label>Nama Mitra Terkait</label>
            <input type="text" name="mitra" required placeholder="Cth: PT Makmur">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: #6B4F2A; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px;">
            Simpan Lokasi Baru
        </button>
    </form>
</div>


<div id="modalDetail" class="modal-overlay">
    <div class="modal-content" style="align-items: center; text-align: center;">
        <div style="width: 80px; height: 80px; background: #FEF3D1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #6B4F2A; margin-bottom: 10px;">
            <i class="fa-solid fa-box"></i>
        </div>
        
        <h3 id="detNama" style="font-family: Montserrat; font-weight: 700; color: #1A1A1A; margin: 0; font-size: 20px;">Nama</h3>
        <p id="detLokasi" style="font-size: 13px; color: #718096; margin-bottom: 10px;">Lokasi</p>
        <p style="font-size: 14px; font-weight: 700; color: #4A5568; margin-bottom: 20px;">Mitra: <span id="detMitra"></span></p>
        
        <div style="background: #F7FAFC; width: 100%; border-radius: 12px; padding: 15px; text-align: left; border: 1px solid #E2E8F0;">
            <p style="font-size: 12px; font-weight: 700; color: #A0AEC0; text-transform: uppercase; margin-bottom: 10px;">Riwayat Penjemputan Selesai:</p>
            <ul id="detHistory" class="history-list">
            </ul>
        </div>
        
        <button type="button" onclick="document.getElementById('modalDetail').style.display='none'" style="width: 100%; padding: 12px; background: #E2E8F0; color: #1A202C; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px;">
            Tutup
        </button>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/polyline-encoded@0.0.9/Polyline.encoded.js"></script>

<script>
    // 1. Inisialisasi Peta (Center Jakarta)
    var map = L.map('map').setView([-6.2088, 106.8456], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 2. Definisi Ikon Kustom
    var motorIcon = L.icon({
        iconUrl: 'https://img.icons8.com/color/100/motorcycle-delivery-single-box.png', 
        iconSize: [40, 40], 
        iconAnchor: [20, 40], 
        popupAnchor: [0, -40] 
    });

    var finishIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/1053/1053155.png', 
        iconSize: [32, 32],
        iconAnchor: [6, 32], 
        popupAnchor: [10, -32]
    });

    // 3. Ambil data dropbox dari PHP ke JS
    var dropboxes = @json($dropboxes); 

    // 4. Taruh Marker Drop Box & Logika Tracking Animasi Peta
    dropboxes.forEach(function(db) {
        var color = db.status == 'tersedia' ? '#48BB78' : (db.status == 'hampir_penuh' ? '#ED8936' : '#F56565');
        L.circleMarker([db.lat, db.lng], {
            color: color, fillColor: color, fillOpacity: 0.8, radius: 8
        }).addTo(map)
        .bindPopup("<div style='text-align:center;'><b>" + db.nama + "</b><br><span style='color:gray; font-size:12px;'>" + db.kapasitas + " Terisi</span></div>");

        if (db.active_task) {
            let task = db.active_task;
            let now = Math.floor(Date.now() / 1000); 

            if (now >= task.waktu_mulai && now < task.waktu_selesai) {
                // Marker Gudang Pusat
                L.marker([task.lat_gudang, task.lng_gudang], {icon: finishIcon}).addTo(map)
                    .bindPopup("<b>Gudang Pusat FoodLink</b><br>Titik Awal & Akhir Relawan");

                // Mengambil Rute 3 Titik (Gudang -> Drop Box -> Gudang)
                let osrmUrl = `https://router.project-osrm.org/route/v1/driving/${task.lng_gudang},${task.lat_gudang};${task.lng_dropbox},${task.lat_dropbox};${task.lng_gudang},${task.lat_gudang}?overview=full`;

                fetch(osrmUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.code === 'Ok') {
                        let ruteCoords = L.Polyline.fromEncoded(data.routes[0].geometry).getLatLngs();
                        let routeLine = L.polyline(ruteCoords, {color: '#888', dashArray: '5, 10', weight: 3}).addTo(map);

                        let motorMarker = L.marker([task.lat_gudang, task.lng_gudang], {icon: motorIcon}).addTo(map);

                        setInterval(() => {
                            let currentNow = Date.now() / 1000;
                            
                            if (currentNow >= task.waktu_selesai) {
                                map.removeLayer(motorMarker); 
                                map.removeLayer(routeLine);  
                            } else {
                                let totalTaskDuration = task.waktu_selesai - task.waktu_mulai;
                                let elapsed = currentNow - task.waktu_mulai;
                                let progress = elapsed / totalTaskDuration;

                                let indexTarget = Math.floor(ruteCoords.length * progress);
                                
                                if (indexTarget >= 0 && indexTarget < ruteCoords.length) {
                                    motorMarker.setLatLng(ruteCoords[indexTarget]);
                                }

                                // Update keterangan Popup Motor secara live
                                let statusKurir = currentNow < task.waktu_sampai_dropbox 
                                    ? "Sedang menuju ke Drop Box" 
                                    : "Membawa barang ke Gudang";
                                motorMarker.getPopup() ? motorMarker.getPopup().setContent("<b>Relawan: " + task.petugas + "</b><br>" + statusKurir) : motorMarker.bindPopup("<b>Relawan: " + task.petugas + "</b><br>" + statusKurir);
                            }
                        }, 500); 
                    }
                }).catch(e => console.error("OSRM Routing Error: ", e));
            }
        }
    });

    // UPDATE: Scripts untuk Modals Riwayat & Penjemputan dengan parameter yang aman
    function bukaDetail(btn) {
        let nama = btn.getAttribute('data-nama');
        let lokasi = btn.getAttribute('data-lokasi');
        let mitra = btn.getAttribute('data-mitra');
        let historyJson = btn.getAttribute('data-history');

        document.getElementById('detNama').textContent = nama;
        document.getElementById('detLokasi').textContent = lokasi;
        document.getElementById('detMitra').textContent = mitra;
        
        let history = JSON.parse(historyJson || "[]");
        let historyHtml = "";
        
        if (history.length > 0) {
            historyHtml = history.map(h => 
                `<li style='margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed #CBD5E0; line-height: 1.5; position: relative; padding-left: 15px;'>
                    <span style='position: absolute; left: 0; top: 6px; width: 6px; height: 6px; background-color: #6B4F2A; border-radius: 50%;'></span>
                    ${h}
                </li>`
            ).join('');
        } else {
            historyHtml = "<li style='color: #A0AEC0; font-style: italic; text-align: center; padding: 10px 0; margin: 0;'>Belum ada riwayat penjemputan dari lokasi ini.</li>";
        }
            
        document.getElementById('detHistory').innerHTML = historyHtml;
        document.getElementById('modalDetail').style.display = 'flex';
    }

    function bukaModalJemput(btn) {
        let id = btn.getAttribute('data-id');
        let namaLokasi = btn.getAttribute('data-nama');
        
        document.getElementById('jemputNamaLoks').textContent = namaLokasi;
        document.getElementById('formJemput').action = "/admin/drop-box/" + id + "/jemput";
        document.getElementById('modalJemput').style.display = 'flex';
    }
</script>
@endsection