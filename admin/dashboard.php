<?php
include "../security.php"; // Panggil dari root (karena file ini di dalam folder admin)
require_login();
require_role("admin");
require_once 'koneksi.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'hari';
$tab_status = isset($_GET['tab']) ? $_GET['tab'] : 'Pending'; 
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

// ==========================================================
// MENGHITUNG STATISTIK KARTU BAGIAN ATAS (SUMMARY CARDS)
// ==========================================================
$count_pending = 0;
$count_confirmed = 0;
$count_total = 0;

if ($conn) {
    $q_count = mysqli_query($conn, "SELECT status, COUNT(*) as total FROM `prayers` GROUP BY status");
    while ($row = mysqli_fetch_assoc($q_count)) {
        if ($row['status'] == 'Pending') {
            $count_pending = $row['total'];
        } elseif ($row['status'] == 'Dikonfirmasi') {
            $count_confirmed = $row['total'];
        }
    }
    $count_total = $count_pending + $count_confirmed;
}
// ==========================================================

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
        <a href="#grafik">Statistik Doa</a> 
        <a href="#data-doa">Daftar & Histori Doa</a> 
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
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'sukses_semua') : ?>
        <div class="alert-success-admin">
            ✅ Semua permohonan doa yang pending berhasil dikonfirmasi sekaligus!
        </div>
        <?php endif; ?>
<!-- ======================================================================================= -->
        <div class="summary-grid">
            <div class="summary-card total">
                <div class="summary-header">
                    <div class="summary-icon total">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    </div>
                    <div class="summary-number total"><?= $count_total ?></div>
                </div>
                <div class="summary-label">Total Semua Doa Masuk</div>
            </div>

            <div class="summary-card pending">
                <div class="summary-header">
                    <div class="summary-icon pending">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="summary-number pending"><?= $count_pending ?></div>
                </div>
                <div class="summary-label">Menunggu Tinjauan (Pending)</div>
            </div>

            <div class="summary-card confirmed">
                <div class="summary-header">
                    <div class="summary-icon confirmed">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="summary-number confirmed"><?= $count_confirmed ?></div>
                </div>
                <div class="summary-label">Disetujui / Dikonfirmasi</div>
            </div>
        </div>

        
        <div class="card" id="data-doa">
            <h3>📋 Histori & Peninjauan Pokok Doa</h3>
            
            <div class="tab-container">
                <a href="admin_dashboard.php?filter=<?= $filter ?>&tab=Pending&search=<?= urlencode($search_query) ?>#data-doa" class="tab-btn <?= $tab_status == 'Pending' ? 'active' : '' ?>">⏳ Menunggu Konfirmasi (Pending)</a>
                <a href="admin_dashboard.php?filter=<?= $filter ?>&tab=Dikonfirmasi&search=<?= urlencode($search_query) ?>#data-doa" class="tab-btn <?= $tab_status == 'Dikonfirmasi' ? 'active' : '' ?>">✔ Sudah Dikonfirmasi (Histori)</a>
            </div>

            <div class="table-controls">
                <form method="GET" action="admin_dashboard.php" class="search-form">
                    <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                    <input type="hidden" name="tab" value="<?= htmlspecialchars($tab_status) ?>">
                    <input type="text" name="search" placeholder="Cari Nama Pengirim / Pokok Doa..." value="<?= htmlspecialchars($search_query) ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <?php if(!empty($search_query)): ?>
                        <a href="admin_dashboard.php?filter=<?= $filter ?>&tab=<?= $tab_status ?>#data-doa" class="btn btn-danger">Reset</a>
                    <?php endif; ?>
                </form>

                <?php if($tab_status == 'Pending' && $total_data > 0): ?>
                    <button type="button" onclick="bukaModal('admin_dashboard.php?filter=<?= $filter ?>&aksi=konfirmasi_semua', 'Apakah Anda yakin ingin menyetujui dan mengonfirmasi SEMUANYA sekaligus? Tindakan ini akan memproses semua doa pending.')" class="btn btn-success">✔ Konfirmasi Semua Doa</button>
                <?php endif; ?>
            </div>
            
            <div class="table-responsive">
                <table style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="18%">Tanggal Masuk</th>
                            <th width="22%">Nama Pengirim</th>
                            <th>Isi Permohonan Doa</th>
                            <th width="15%">Status</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($conn && $total_data > 0): ?>
                            <?php while ($row_tabel = mysqli_fetch_assoc($result_tabel)): ?>
                            <tr>
                                <td><?= date('d M Y, H:i', strtotime($row_tabel['tanggal_dibuat'])) ?></td>
                                <td><strong><?= htmlspecialchars($row_tabel['nama_pengirim']) ?></strong></td>
                                <td><?= nl2br(htmlspecialchars($row_tabel['isi_doa'])) ?></td>
                                <td>
                                    <?php if ($row_tabel['status'] == 'Dikonfirmasi'): ?>
                                        <span class="status-badge status-confirmed">✔ Dikonfirmasi</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">⏳ Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row_tabel['status'] !== 'Dikonfirmasi'): ?>
                                        <button type="button" onclick="bukaModal('admin_dashboard.php?filter=<?= $filter ?>&aksi=konfirmasi_doa&id=<?= $row_tabel['id'] ?>', 'Konfirmasi bahwa permohonan doa dari <?= htmlspecialchars(addslashes($row_tabel['nama_pengirim'])) ?> telah diterima dan diteruskan ke tim pendoa?')" class="btn btn-success" style="font-size: 0.8em; padding: 5px 10px;">Konfirmasi</button>
                                    <?php else: ?>
                                        <span style="color: #94a3b8; font-size: 0.85em; font-style: italic;">Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #94a3b8; font-style: italic; padding: 20px;">Tidak ada data permohonan doa yang sesuai dengan kriteria penelusuran Anda.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="admin_dashboard.php?filter=<?= $filter ?>&tab=<?= $tab_status ?>&search=<?= urlencode($search_query) ?>&page=<?= $i ?>#data-doa" class="page-link <?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
<!-- ======================================================================================= -->

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
        <div id="customConfirmModal" class="modal-overlay">
        <div class="modal-box">
            <h3 style="margin-top:0; color:#1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom:10px;">Konfirmasi Aksi</h3>
            <p id="modalMessage">Pesan teks konfirmasi akan dicetak di area ini secara dinamis.</p>
            <div class="modal-buttons">
                <button type="button" onclick="tutupModal()" class="btn btn-danger">Batalkan</button>
                <a href="#" id="modalConfirmBtn" class="btn btn-primary">Ya, Konfirmasi</a>
            </div>
        </div>
    </div>
</body>
</html>