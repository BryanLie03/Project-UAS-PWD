<?php
include "../security.php"; // Panggil dari root (karena file ini di dalam folder admin)
require_login();
require_role("admin");

// KONEKSI API YOUTUBE - GANTI DENGAN DATA ANDA
$api_key = 'AIzaSyBpSTSnnydglOfCEMO43doRrzDf-IMB62Y';
$channel_id = 'UCxlTgEU_BloNnEXDdNfv-Dg'; // GYS Pontianak UC ID

// Mengambil 5 video terbaru
$api_url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$channel_id}&maxResults=5&key={$api_key}";

// Proses cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$youtube_data = json_decode($response, true);
$video_list_api = [];

if (isset($youtube_data['items'])) {
    foreach ($youtube_data['items'] as $item) {
        if (isset($item['id']['videoId'])) {
        $video_list_api[] = [
            'id_video'  => $item['id']['videoId'],
            'judul'     => $item['snippet']['title'],
            'thumbnail' => $item['snippet']['thumbnails']['medium']['url']
        ];
        }
    }
}

// BACA DATA CMS (JSON)
$data_video_cms = file_exists('data_video.json') ? json_decode(file_get_contents('data_video.json'), true) : null;
$data_event = file_exists('data_event.json') ? json_decode(file_get_contents('data_event.json'), true) : [];
$data_galeri = file_exists('data_galeri.json') ? json_decode(file_get_contents('data_galeri.json'), true) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard GYS Pontianak</title>
    <link rel="stylesheet" href="../CSS/style_admin.css?v=<?php echo time(); ?>" />
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Panel Administrator</div>
        <a href="#video">Manajemen YouTube</a>
        <a href="#event">Manajemen Event</a>
        <a href="#galeri">Manajemen Galeri</a>
        <hr>
        <a href="../index.php" class="btn-view-site">Lihat Website</a>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>

    <div class="main-content">
        <h1 class="header-title">Dashboard Manajemen Konten</h1>

        <?php if (isset($_GET['msg'])) : ?>
        <div class="alert-success-admin">
            ✅ Aksi berhasil diselesaikan. Data telah diperbarui!
        </div>
        <?php endif; ?>

        <div class="card" id="video">
        <h3>🎥 Manajemen Video Utama di Website</h3>
        <p>Atur video yang ditampilkan di halaman depan. Anda bisa mengedit judul yang akan ditampilkan di website.</p>
        
        <?php if ($data_video_cms) : ?>
            <div class="active-video-set">
            <form action="proses_dashboard.php?aksi=edit_judul_video" method="POST" class="editable-title-form">
                <input type="text" name="judul_diedit" value="<?= htmlspecialchars($data_video_cms['judul']) ?>" required>
                <button type="submit" class="btn btn-primary btn-save-title">Simpan Judul Baru</button>
            </form>
            
            <div class="video-thumbnail-wrapper">
                <img src="<?= $data_video_cms['thumbnail'] ?>" alt="Thumbnail Aktif">
            </div>
            
            <p class="metadata-text">
                Diupload oleh: <?= $data_video_cms['diupload_oleh'] ?> (Terakhir diubah: <?= date('d M Y, H:i', strtotime($data_video_cms['waktu'])) ?>)
            </p>
            
            <a href="proses_dashboard.php?aksi=hapus_video" class="btn btn-danger" onclick="return confirm('Yakin ingin menarik video ini dari halaman utama?')">🛑 Tarik Video dari Index</a>
            </div>
        <?php else : ?>
            <p class="alert-danger-admin">Belum ada video yang diatur di halaman utama.</p>
        <?php endif; ?>

        <hr class="hr-divider">
        
        <h4>Pilih Video Terbaru dari Channel GYS</h4>
        <div class="yt-grid">
            <?php if (!empty($video_list_api)) : ?>
            <?php foreach ($video_list_api as $video) : ?>
                <div class="yt-card">
                <img src="<?= $video['thumbnail'] ?>" alt="Thumbnail">
                <h4><?= htmlspecialchars($video['judul']) ?></h4>
                <div class="yt-card-actions">
                    <form action="proses_dashboard.php?aksi=set_video" method="POST">
                    <input type="hidden" name="id_video" value="<?= $video['id_video'] ?>">
                    <input type="hidden" name="judul_video" value="<?= htmlspecialchars($video['judul']) ?>">
                    <button type="submit" class="btn btn-success">Jadikan Utama</button>
                    </form>
                </div>
                </div>
            <?php endforeach; ?>
            <?php else : ?>
            <p class="alert-error-yt">Gagal mengambil data dari YouTube. Pastikan API Key dan Channel ID benar.</p>
            <?php endif; ?>
        </div>
        </div>

        <div class="card" id="event">
        <h3>📅 Manajemen Jadwal Kegiatan</h3>
        <form action="proses_dashboard.php?aksi=tambah_event" method="POST" class="form-admin-box">
            <label>Nama Kegiatan:</label>
            <input type="text" name="nama_event" required>
            <label>Tanggal Pelaksanaan:</label>
            <input type="date" name="tanggal_event" required>
            <button type="submit" class="btn btn-primary">+ Tambah Event Baru</button>
        </form>

        <table>
            <tr>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Ditambahkan Oleh</th>
            <th>Aksi</th>
            </tr>
            <?php foreach ($data_event as $event) : ?>
            <tr>
                <td><?= htmlspecialchars($event['nama']) ?></td>
                <td><?= date('d M Y', strtotime($event['tanggal'])) ?></td>
                <td><span class="info-badge"><?= $event['dibuat_oleh'] ?></span></td>
                <td><a href="proses_dashboard.php?aksi=hapus_event&id=<?= $event['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus event ini?')">Hapus</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>

        <div class="card" id="galeri">
        <h3>🖼️ Manajemen Galeri Foto</h3>
        <form action="proses_dashboard.php?aksi=tambah_galeri" method="POST" enctype="multipart/form-data" class="form-admin-box">
            <label>Judul/Keterangan Foto:</label>
            <input type="text" name="judul_foto" required>
            <label>Pilih File Foto (JPG/PNG):</label>
            <input type="file" name="foto" accept="image/*" required>
            <button type="submit" class="btn btn-primary">+ Upload ke Galeri</button>
        </form>

        <table>
            <tr>
            <th class="th-preview">Preview</th>
            <th>Keterangan Foto</th>
            <th>Diupload Oleh</th>
            <th>Aksi</th>
            </tr>
            <?php foreach ($data_galeri as $galeri) : ?>
            <tr>
                <td><img src="<?= $galeri['file'] ?>" class="img-preview"></td>
                <td><?= htmlspecialchars($galeri['judul']) ?></td>
                <td><span class="info-badge"><?= $galeri['diupload_oleh'] ?></span></td>
                <td><a href="proses_dashboard.php?aksi=hapus_galeri&id=<?= $galeri['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus foto ini dari galeri?')">Hapus</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>

    </div>
</body>
</html>