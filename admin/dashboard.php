<?php
include "../security.php"; // Panggil dari folder admin
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
                'id_video' => $item['id']['videoId'],
                'judul' => $item['snippet']['title'],
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
    <style>
        /* CSS RESET & GENERAL */
        :root { --sidebar-bg: #1e293b; --accent: #3b82f6; --bg-color: #f1f5f9; --card-bg: #ffffff; --text-main: #333333; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; background-color: var(--bg-color); color: var(--text-main); }
        
        /* SIDEBAR */
        .sidebar { width: 250px; background-color: var(--sidebar-bg); color: white; min-height: 100vh; position: fixed; }
        .sidebar-header { padding: 20px; font-size: 1.2em; font-weight: bold; text-align: center; border-bottom: 1px solid #334155; background-color: #0f172a; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 15px 20px; display: block; border-left: 4px solid transparent; transition: 0.3s; }
        .sidebar a:hover { background-color: #334155; border-left: 4px solid var(--accent); color: white; }
        
        /* CONTENT MAIN */
        .main-content { flex: 1; margin-left: 250px; padding: 30px; }
        .header-title { margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* CARDS / CONTAINERS */
        .card { background-color: var(--card-bg); border-radius: 8px; padding: 20px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
        .card h3 { margin-top: 0; color: #0f172a; }
        
        /* BUTTONS */
        .btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; color: white; font-weight: bold; font-size: 0.9em; transition: 0.2s; }
        .btn-primary { background-color: var(--accent); } .btn-primary:hover { background-color: #2563eb; }
        .btn-success { background-color: #10b981; } .btn-success:hover { background-color: #059669; }
        .btn-danger { background-color: #ef4444; } .btn-danger:hover { background-color: #dc2626; }
        
        /* FORMS */
        input[type="text"], input[type="date"], input[type="file"], textarea { width: 100%; padding: 10px; margin: 10px 0 15px 0; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e2e8f0; padding: 12px; text-align: left; }
        th { background-color: #f8fafc; font-weight: 600; }
        
        /* NEW YOUTUBE LAYOUT (Editable Video Set) */
        .active-video-set {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            background: #f8fafc;
            max-width: 500px;
            margin-bottom: 30px;
        }
        
        .editable-title-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .editable-title-form input[type="text"] {
            margin: 0; /* Hapus margin default input */
            font-size: 1.1em;
            font-weight: bold;
        }
        
        .video-thumbnail-wrapper img {
            width: 100%;
            max-width: 460px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .metadata-text {
            font-size: small;
            font-weight: lighter;
            color: #64748b;
            margin: 0 0 15px 0;
        }
        
        /* YT API Grid (Existing) */
        .yt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-top: 15px; }
        .yt-card { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; text-align: center; }
        .yt-card img { width: 100%; height: auto; border-bottom: 1px solid #e2e8f0; }
        .yt-card h4 { padding: 5px 10px; font-size: 0.85em; margin: 0; }
        .yt-card-actions { padding: 10px; border-top: 1px solid #e2e8f0; }
        
        /* BADGE */
        .badge { display: inline-block; padding: 3px 8px; background-color: #ef4444; color: white; border-radius: 3px; font-size: 0.75em; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Panel Administrator</div>
        <a href="#video">Manajemen YouTube</a>
        <a href="#event">Manajemen Event</a>
        <a href="#galeri">Manajemen Galeri</a>
        <hr style="border: 0; border-top: 1px solid #334155; margin: 20px 15px;">
        <a href="index.php" target="_blank" style="background-color: #0284c7; color: white; border-radius: 5px; margin: 5px 15px; text-align: center;">Lihat Website</a>
        <a href="logout.php" style="background-color: #ef4444; color: white; border-radius: 5px; margin: 5px 15px; text-align: center;">Keluar</a>
    </div>

    <div class="main-content">
        <h1 class="header-title">Dashboard Manajemen Konten</h1>

        <?php if(isset($_GET['msg'])): ?>
            <div style="background-color: #10b981; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold;">
                ✅ Aksi berhasil diselesaikan. Data telah diperbarui!
            </div>
        <?php endif; ?>

        <div class="card" id="video">
            <h3>🎥 Manajemen Video Utama di Website</h3>
            <p>Atur video yang ditampilkan di halaman depan. Anda bisa mengedit judul yang akan ditampilkan di website.</p>
            
            <?php if ($data_video_cms): ?>
                <div class="active-video-set">
                    <form action="proses_dashboard.php?aksi=edit_judul_video" method="POST" class="editable-title-form">
                        <input type="text" name="judul_diedit" value="<?= htmlspecialchars($data_video_cms['judul']) ?>" required>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 15px;">Simpan Judul Baru</button>
                    </form>
                    
                    <div class="video-thumbnail-wrapper">
                        <img src="<?= $data_video_cms['thumbnail'] ?>" alt="Thumbnail Aktif">
                    </div>
                    
                    <p class="metadata-text">
                        Diupload oleh: <?= $data_video_cms['diupload_oleh'] ?> (Terakhir diubah: <?= date('d M Y, H:i', strtotime($data_video_cms['waktu'])) ?>)
                    </p>
                    
                    <a href="proses_dashboard.php?aksi=hapus_video" class="btn btn-danger" onclick="return confirm('Yakin ingin menarik video ini dari halaman utama?')">🛑 Tarik Video dari Index</a>
                </div>
            <?php else: ?>
                <p style="color: #ef4444; font-weight: bold;">Belum ada video yang diatur di halaman utama.</p>
            <?php endif; ?>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;">
            
            <h4>Pilih Video Terbaru dari Channel GYS</h4>
            <div class="yt-grid">
                <?php if(!empty($video_list_api)): ?>
                    <?php foreach($video_list_api as $video): ?>
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
                <?php else: ?>
                    <p style="color: red; grid-column: 1 / -1;">Gagal mengambil data dari YouTube. Pastikan API Key dan Channel ID benar.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card" id="event">
            <h3>📅 Manajemen Jadwal Kegiatan</h3>
            <form action="proses_dashboard.php?aksi=tambah_event" method="POST" style="background:#f8fafc; padding:15px; border-radius:5px; margin-bottom: 20px;">
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
                <?php foreach ($data_event as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['nama']) ?></td>
                    <td><?= date('d M Y', strtotime($event['tanggal'])) ?></td>
                    <td><span class="info-badge" style="background: #e0f2fe; color: #0284c7; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;"><?= $event['dibuat_oleh'] ?></span></td>
                    <td><a href="proses_dashboard.php?aksi=hapus_event&id=<?= $event['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus event ini?')">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="card" id="galeri">
            <h3>🖼️ Manajemen Galeri Foto</h3>
            <form action="proses_dashboard.php?aksi=tambah_galeri" method="POST" enctype="multipart/form-data" style="background:#f8fafc; padding:15px; border-radius:5px; margin-bottom: 20px;">
                <label>Judul/Keterangan Foto:</label>
                <input type="text" name="judul_foto" required>
                <label>Pilih File Foto (JPG/PNG):</label>
                <input type="file" name="foto" accept="image/*" required>
                <button type="submit" class="btn btn-primary">+ Upload ke Galeri</button>
            </form>

            <table>
                <tr>
                    <th width="15%">Preview</th>
                    <th>Keterangan Foto</th>
                    <th>Diupload Oleh</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($data_galeri as $galeri): ?>
                <tr>
                    <td><img src="<?= $galeri['file'] ?>" style="width: 100px; border-radius: 5px;"></td>
                    <td><?= htmlspecialchars($galeri['judul']) ?></td>
                    <td><span class="info-badge" style="background: #e0f2fe; color: #0284c7; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;"><?= $galeri['diupload_oleh'] ?></span></td>
                    <td><a href="proses_dashboard.php?aksi=hapus_galeri&id=<?= $galeri['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus foto ini dari galeri?')">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </div>
</body>
</html>
