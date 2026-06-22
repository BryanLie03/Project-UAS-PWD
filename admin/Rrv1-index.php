<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// BACA DATA DARI MESIN
$data_video = file_exists('data_video.json') ? json_decode(file_get_contents('data_video.json'), true) : null;
$data_event = file_exists('data_event.json') ? json_decode(file_get_contents('data_event.json'), true) : [];
$data_galeri = file_exists('data_galeri.json') ? json_decode(file_get_contents('data_galeri.json'), true) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website GYS Pontianak</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; background: #f8fafc; color: #333; }
        .admin-bar { background: #1e293b; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .admin-bar a { background: #3b82f6; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .section-title { border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-top: 40px; }
        
        /* Layout Grid untuk Event dan Galeri */
        .grid-event { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; }
        .card-event { background: white; padding: 15px; border-left: 5px solid #10b981; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        .grid-galeri { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .card-galeri { background: white; padding: 10px; text-align: center; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-galeri img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; }
        
        .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 10px; margin-top: 15px; }
        .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
        .credit { font-size: 0.8em; color: #64748b; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="admin-bar">
        <span>Pratinjau Mode Admin - <b><?= htmlspecialchars($_SESSION['nama_admin']) ?></b></span>
        <a href="admin_dashboard.php">Kembali ke Dashboard</a>
    </div>

    <div class="container">
        <h2 class="section-title">▶️ Video Terbaru</h2>
        <?php if ($data_video): ?>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($data_video['id_video']) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="credit">Diunggah oleh: <?= $data_video['diupload_oleh'] ?></div>
        <?php else: ?>
            <p style="color: #94a3b8; font-style: italic;">Tidak ada video yang ditampilkan.</p>
        <?php endif; ?>

        <h2 class="section-title">📅 Jadwal Kegiatan</h2>
        <div class="grid-event">
            <?php if (!empty($data_event)): ?>
                <?php foreach ($data_event as $event): ?>
                <div class="card-event">
                    <h3 style="margin: 0 0 5px 0;"><?= htmlspecialchars($event['nama']) ?></h3>
                    <p style="margin: 0; color: #64748b;">Pelaksanaan: <?= date('d M Y', strtotime($event['tanggal'])) ?></p>
                    <div class="credit">Dibuat oleh: <?= $event['dibuat_oleh'] ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #94a3b8; font-style: italic;">Belum ada jadwal kegiatan.</p>
            <?php endif; ?>
        </div>

        <h2 class="section-title">🖼️ Galeri Foto</h2>
        <div class="grid-galeri">
            <?php if (!empty($data_galeri)): ?>
                <?php foreach ($data_galeri as $galeri): ?>
                <div class="card-galeri">
                    <img src="<?= $galeri['file'] ?>" alt="Foto Galeri">
                    <p style="margin: 10px 0 0 0; font-weight: bold;"><?= htmlspecialchars($galeri['judul']) ?></p>
                    <div class="credit">Upload by: <?= $galeri['diupload_oleh'] ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #94a3b8; font-style: italic;">Galeri masih kosong.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>