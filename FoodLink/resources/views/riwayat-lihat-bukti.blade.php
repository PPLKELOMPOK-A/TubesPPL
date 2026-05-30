<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bukti Donasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { display: flex; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .btn-back { text-decoration: none; color: #333; font-size: 1.5rem; margin-right: 20px; }
        .title-section h2 { margin: 0; color: #2c3e50; font-size: 1.5rem; }
        .status-badge { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        
        .content-card { display: grid; grid-template-columns: 1fr; gap: 20px; }
        .detail-info { background: #fffaf5; border-radius: 10px; padding: 20px; border: 1px solid #ffe8d6; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #ddd; padding-bottom: 8px; }
        .info-row span:first-child { color: #7f8c8d; font-size: 0.9rem; }
        .info-row span:last-child { font-weight: 600; color: #2c3e50; text-align: right; }

        .gallery-title { margin-top: 30px; color: #2c3e50; border-left: 5px solid #8d6e63; padding-left: 10px; }
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 15px; }
        .gallery-item { border-radius: 10px; overflow: hidden; height: 200px; border: 1px solid #eee; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
        
        .description-box { margin-top: 20px; line-height: 1.6; color: #555; background: #fdfdfd; padding: 15px; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <a href="javascript:history.back()" class="btn-back"><i class="fas fa-chevron-left"></i></a>
        <div class="title-section">
            <span class="status-badge">Selesai Disalurkan</span>
            <h2>{{ $donasi->judul }}</h2>
        </div>
    </div>

    <div class="content-card">
        <div class="detail-info">
            <div class="info-row">
                <span>ID Transaksi</span>
                <span>#TRX-00{{ $donasi->id }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal Penyaluran</span>
                <span>{{ $donasi->tanggal }}</span>
            </div>
            <div class="info-row">
                <span>Tujuan Penyaluran</span>
                <span>{{ $donasi->tujuan }}</span>
            </div>
            <div class="info-row">
                <span>Jenis Donasi</span>
                <span>{{ $donasi->jenis }}</span>
            </div>
        </div>

        <div class="description-box">
            <strong>Catatan Penyaluran:</strong><br>
            {{ $donasi->deskripsi }} <br><br>
            <em>*{{ $donasi->catatan }}</em>
        </div>

        <h3 class="gallery-title">Dokumentasi Penyaluran</h3>
        <div class="gallery-grid">
            @foreach($donasi->galeri as $foto)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $foto) }}" alt="Bukti Foto" onerror="this.src='https://via.placeholder.com/400x300?text=Foto+Dokumentasi'">
            </div>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>