<?php
include "../security.php"; 
require_login();
require_role("admin");
require_once '../koneksi.php'; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'hari';
$tab_status = isset($_GET['tab']) ? $_GET['tab'] : 'Pending'; 
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_galeri_event = isset($_GET['filter_galeri']) ? $_GET['filter_galeri'] : 'all';

$limit = 5; 
$page_doa = isset($_GET['page_doa']) ? (int)$_GET['page_doa'] : 1;
$page_event = isset($_GET['page_event']) ? (int)$_GET['page_event'] : 1;
$page_galeri = isset($_GET['page_galeri']) ? (int)$_GET['page_galeri'] : 1;

$offset_doa = ($page_doa - 1) * $limit;
$offset_event = ($page_event - 1) * $limit;
$offset_galeri = ($page_galeri - 1) * $limit;

// 1. Hitung Statistik Doa
$count_pending = 0; $count_confirmed = 0; $count_total = 0;
if ($conn) {
    try {
        $q_count = mysqli_query($conn, "SELECT status, COUNT(*) as total FROM `prayers` GROUP BY status");
        while ($row = mysqli_fetch_assoc($q_count)) {
            if ($row['status'] == 'Pending') $count_pending = $row['total'];
            elseif ($row['status'] == 'Dikonfirmasi') $count_confirmed = $row['total'];
        }
        $count_total = $count_pending + $count_confirmed;
    } catch (Exception $e) {}
}

// 2. Query Doa
$total_pages_doa = 0; $result_doa = null;
if ($conn) {
    $where_clause = "WHERE p.status = '$tab_status'";
    if (!empty($search_query)) $where_clause .= " AND (u.full_name LIKE '%$search_query%' OR p.pray LIKE '%$search_query%')";
    
    $query_total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM prayers p JOIN users u ON p.id_user = u.id_user $where_clause");
    $total_pages_doa = ceil(mysqli_fetch_assoc($query_total)['jml'] / $limit);
    
    $result_doa = mysqli_query($conn, "SELECT p.id_pray, p.pray, p.date, p.status, u.full_name FROM prayers p JOIN users u ON p.id_user = u.id_user $where_clause ORDER BY p.date DESC LIMIT $limit OFFSET $offset_doa");
}

// 3. Query Event (Menghitung jumlah foto per event untuk warning)
$total_pages_event = 0; $result_event = null;
if ($conn) {
    $q_tot_event = mysqli_query($conn, "SELECT COUNT(*) as jml FROM events");
    $total_pages_event = ceil(mysqli_fetch_assoc($q_tot_event)['jml'] / $limit);
    
    $result_event = mysqli_query($conn, "
        SELECT e.*, (SELECT COUNT(*) FROM galleries WHERE id_event = e.id_event) as photo_count 
        FROM events e ORDER BY date DESC LIMIT $limit OFFSET $offset_event
    ");
}

// 4. Query Galeri (Dengan Filter)
$total_pages_galeri = 0; $result_galeri = null;
if ($conn) {
    $where_galeri = "";
    if ($filter_galeri_event !== 'all') {
        $id_filter_clean = (int)$filter_galeri_event;
        $where_galeri = " WHERE g.id_event = $id_filter_clean ";
    }

    $q_tot_galeri = mysqli_query($conn, "SELECT COUNT(*) as jml FROM galleries g $where_galeri");
    $total_pages_galeri = ceil(mysqli_fetch_assoc($q_tot_galeri)['jml'] / $limit);
    
    $result_galeri = mysqli_query($conn, "
        SELECT g.id_gallery, g.image_gallery, e.event, e.id_event, u.full_name 
        FROM galleries g 
        JOIN events e ON g.id_event = e.id_event 
        JOIN users u ON g.id_user = u.id_user 
        $where_galeri
        ORDER BY g.id_gallery DESC 
        LIMIT $limit OFFSET $offset_galeri
    ");
}

// 5. Youtube Database & API
$api_key = 'AIzaSyBpSTSnnydglOfCEMO43doRrzDf-IMB62Y';
$channel_id = 'UCxlTgEU_BloNnEXDdNfv-Dg'; 

$api_url = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId={$channel_id}&maxResults=5&key={$api_key}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$youtube_data = json_decode($response, true);
$video_list_api = []; $pesan_error_api = "";
if (isset($youtube_data['items'])) {
    foreach ($youtube_data['items'] as $item) {
        if (isset($item['id']['videoId'])) {
            $video_list_api[] = ['id_video' => $item['id']['videoId'], 'judul' => $item['snippet']['title'], 'thumbnail' => $item['snippet']['thumbnails']['medium']['url']];
        }
    }
} elseif (isset($youtube_data['error'])) { $pesan_error_api = $youtube_data['error']['message']; }

// Ambil data aktif dari tabel youtube
$q_yt_aktif = mysqli_query($conn, "SELECT * FROM youtube ORDER BY id_youtube DESC LIMIT 1");
$yt_aktif = ($q_yt_aktif && mysqli_num_rows($q_yt_aktif) > 0) ? mysqli_fetch_assoc($q_yt_aktif) : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard GYS Pontianak</title>
  <!-- Ikon Web -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/style_admin.css?v=<?php echo time(); ?>" />
</head>
<body>

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <!-- Logo Full (Tampil saat terbuka) -->
    <img src="../Assets/img/Logo-scrolled.png" alt="Logo Full" class="brand-logo-img logo-full">
    <!-- Logo Minimize (Tampil saat ditutup) -->
    <img src="../Assets/img/logo-minimize.png" alt="Logo Min" class="brand-logo-img logo-min">
    </div>
    <a href="#grafik"><i class="fa-solid fa-chart-pie"></i> <span class="sidebar-text">Statistik Doa</span></a> 
    <a href="#data-doa"><i class="fa-solid fa-hands-praying"></i> <span class="sidebar-text">Daftar Doa</span></a> 
    <a href="#video"><i class="fa-brands fa-youtube"></i> <span class="sidebar-text">Manajemen YouTube</span></a>
    <a href="#event"><i class="fa-solid fa-calendar-days"></i> <span class="sidebar-text">Manajemen Event</span></a>
    <a href="#galeri"><i class="fa-solid fa-images"></i> <span class="sidebar-text">Galeri Foto</span></a>
    <hr>
    <div class="sidebar-btn-container">
        <a href="../index.php" class="btn-view-site"><i class="fa-solid fa-globe"></i> <span class="sidebar-text">Lihat Website</span></a>
        <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="sidebar-text">Keluar</span></a>
    </div>
  </div>

  <div class="main-content" id="mainContent">
    
    <div class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
        <h1 class="header-title">Dashboard Manajemen Konten</h1>
    </div>

    <!-- NOTIFIKASI FLOATING -->
    <?php 
    $msg = $_GET['msg'] ?? '';
    if (!empty($msg)) : 
      echo '<div class="alert-floating ' . (strpos($msg, 'err') !== false ? 'alert-danger-admin' : 'alert-success-admin') . '">';
      echo (strpos($msg, 'err') !== false) ? '<i class="fa-solid fa-circle-exclamation"></i>' : '<i class="fa-solid fa-circle-check"></i>';
      echo '<span>';
      if ($msg == 'err_size') echo 'Gagal: Ukuran file foto maksimal 7 MB!';
      elseif ($msg == 'err_type') echo 'Gagal: Format file harus JPG, JPEG, atau PNG!';
      elseif ($msg == 'sukses_batal_doa') echo 'Status doa dikembalikan ke Pending!';
      elseif ($msg == 'sukses_semua') echo 'Semua doa berhasil dikonfirmasi!';
      elseif ($msg == 'hapus_massal_sukses') echo 'Foto terpilih berhasil dihapus!';
      else echo 'Aksi berhasil diselesaikan!';
      echo '</span></div>';
    endif;
    ?>

    <!-- STATISTIK DOA -->
    <div class="summary-grid" id="grafik">
      <div class="summary-card total">
        <div class="summary-header">
          <div class="summary-icon total"><i class="fa-solid fa-layer-group"></i></div>
          <div class="summary-number total"><?= $count_total ?></div>
        </div>
        <div class="summary-label">Total Doa Masuk</div>
      </div>
      <div class="summary-card pending">
        <div class="summary-header">
          <div class="summary-icon pending"><i class="fa-solid fa-clock-rotate-left"></i></div>
          <div class="summary-number pending"><?= $count_pending ?></div>
        </div>
        <div class="summary-label">Menunggu Tinjauan</div>
      </div>
      <div class="summary-card confirmed">
        <div class="summary-header">
          <div class="summary-icon confirmed"><i class="fa-solid fa-check-double"></i></div>
          <div class="summary-number confirmed"><?= $count_confirmed ?></div>
        </div>
        <div class="summary-label">Telah Dikonfirmasi</div>
      </div>
    </div>

    <!-- TABEL DOA -->
    <div class="card" id="data-doa">
      <div class="card-header">
          <h3><i class="fa-solid fa-hands-praying"></i> Histori & Peninjauan Doa</h3>
      </div>
      
      <div class="tab-container">
        <a href="dashboard.php?filter=<?= $filter ?>&tab=Pending#data-doa" class="tab-btn <?= $tab_status == 'Pending' ? 'active' : '' ?>">⏳ Menunggu (Pending)</a>
        <a href="dashboard.php?filter=<?= $filter ?>&tab=Dikonfirmasi#data-doa" class="tab-btn <?= $tab_status == 'Dikonfirmasi' ? 'active' : '' ?>">✔ Dikonfirmasi</a>
      </div>

      <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
        <form method="GET" action="dashboard.php" style="display:flex; gap:10px;">
          <input type="hidden" name="tab" value="<?= htmlspecialchars($tab_status) ?>">
          <input type="text" name="search" placeholder="Cari Nama / Doa..." value="<?= htmlspecialchars($search_query) ?>" style="width:250px; margin:0;">
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
          <?php if(!empty($search_query)): ?>
            <a href="dashboard.php?tab=<?= $tab_status ?>#data-doa" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></a>
          <?php endif; ?>
        </form>
        <?php if($tab_status == 'Pending' && $result_doa && mysqli_num_rows($result_doa) > 0): ?>
          <button type="button" onclick="bukaModalConfirm('sv_dashboard.php?aksi=konfirmasi_semua', 'Konfirmasi SEMUANYA sekaligus?')" class="btn btn-success"><i class="fa-solid fa-check-double"></i> Konfirmasi Semua</button>
        <?php endif; ?>
      </div>
      
      <div class="table-responsive">
        <table style="margin-top: 0;">
          <tr>
            <th width="15%">Tanggal</th>
            <th width="20%">Pengirim</th>
            <th>Isi Doa</th>
            <th width="12%">Status</th>
            <th width="10%"></th> <!-- Header kosong untuk Aksi -->
          </tr>
          <?php if ($result_doa && mysqli_num_rows($result_doa) > 0): ?>
            <?php while ($row_tabel = mysqli_fetch_assoc($result_doa)): ?>
            <tr>
              <td><?= date('d M Y', strtotime($row_tabel['date'])) ?></td>
              <td><strong><?= htmlspecialchars($row_tabel['full_name']) ?></strong></td>
              <td><?= nl2br(htmlspecialchars($row_tabel['pray'])) ?></td>
              <td>
                <span class="status-badge <?= $row_tabel['status'] == 'Dikonfirmasi' ? 'status-confirmed' : 'status-pending' ?>">
                    <?= $row_tabel['status'] == 'Dikonfirmasi' ? '✔ Dikonfirmasi' : '⏳ Pending' ?>
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <?php if ($row_tabel['status'] !== 'Dikonfirmasi'): ?>
                    <button type="button" title="Konfirmasi" onclick="bukaModalConfirm('sv_dashboard.php?aksi=konfirmasi_doa&id=<?= $row_tabel['id_pray'] ?>', 'Konfirmasi permohonan doa ini?')" class="btn btn-success btn-icon-only"><i class="fa-solid fa-check"></i></button>
                  <?php else: ?>
                    <button type="button" title="Batal Konfirmasi" onclick="bukaModalConfirm('sv_dashboard.php?aksi=batal_konfirmasi_doa&id=<?= $row_tabel['id_pray'] ?>', 'Kembalikan doa ini ke status Pending?')" class="btn btn-danger btn-icon-only"><i class="fa-solid fa-arrow-rotate-left"></i></button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align: center; color: #94a3b8;">Tidak ada data.</td></tr>
          <?php endif; ?>
        </table>
      </div>

      <?php if ($total_pages_doa > 1): ?>
        <div class="pagination">
          <?php for ($i = 1; $i <= $total_pages_doa; $i++): ?>
            <a href="dashboard.php?tab=<?= $tab_status ?>&search=<?= urlencode($search_query) ?>&page_doa=<?= $i ?>#data-doa" class="page-link <?= ($page_doa == $i) ? 'active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- YOUTUBE DB -->
    <div class="card" id="video">
      <div class="card-header">
          <h3><i class="fa-brands fa-youtube"></i> Manajemen You</h3>
      </div>
      <?php if ($yt_aktif) : ?>
        <div class="active-video-set">
          <form action="sv_youtube.php?aksi=edit_judul_video" method="POST" style="display:flex; gap:10px; margin-bottom:10px;">
            <input type="text" name="judul_diedit" value="<?= htmlspecialchars($yt_aktif['Title']) ?>" required style="margin:0; font-weight:bold;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
          </form>
          <!-- Generate Thumbnail dari link id_video -->
          <img src="https://img.youtube.com/vi/<?= htmlspecialchars($yt_aktif['link']) ?>/maxresdefault.jpg" alt="Thumbnail" style="width:100%; border-radius:8px; margin-bottom:10px;">
          <p style="color:#64748b; font-size:0.85em; margin:0;">Disetel pada: <?= date('d M Y H:i', strtotime($yt_aktif['date'])) ?></p>
        </div>
      <?php else : ?>
        <p style="color:#ef4444; font-weight:bold;">Belum ada video di database.</p>
      <?php endif; ?>
      
      <hr style="border:0; border-top:1px solid #e2e8f0; margin:25px 0;">
      <h4 style="margin-top:0; color:var(--secondary-color);">Pilih Video Terbaru dari YouTube</h4>
      <div class="yt-grid">
        <?php if (!empty($video_list_api)) : ?>
          <?php foreach ($video_list_api as $video) : ?>
            <div class="yt-card">
              <img src="<?= $video['thumbnail'] ?>" alt="Thumbnail">
              <h4><?= htmlspecialchars($video['judul']) ?></h4>
              <div class="yt-card-actions">
                <form action="sv_youtube.php?aksi=set_video" method="POST">
                  <input type="hidden" name="id_video" value="<?= $video['id_video'] ?>">
                  <input type="hidden" name="judul_video" value="<?= htmlspecialchars($video['judul']) ?>">
                  <button type="submit" class="btn btn-success" style="width:100%; justify-content:center;"><i class="fa-solid fa-circle-play"></i> Jadikan Utama</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p style="color:red; grid-column: 1/-1;">Gagal memuat API atau kuota habis.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- TABEL EVENT -->
    <div class="card" id="event">
      <div class="card-header">
          <h3><i class="fa-solid fa-calendar-days"></i> Manajemen Event</h3>
          <button class="btn btn-primary" onclick="bukaModalBasic('modalTambahEvent')"><i class="fa-solid fa-plus"></i> Tambah Event</button>
      </div>

      <div style="background: #eff6ff; padding: 12px 15px; border-radius: 8px; border: 1px solid #bfdbfe; margin-bottom: 15px; color: var(--primary-color); font-weight: 500;">
        <i class="fa-solid fa-circle-info"></i> Centang maksimal 5 event untuk ditampilkan di Beranda. (Otomatis Tersimpan)
      </div>

      <div class="table-responsive">
        <table>
          <tr>
            <th width="5%" style="text-align: center;">Tampil</th>
            <th width="15%">Gambar</th>
            <th>Event & Deskripsi</th>
            <th width="15%">Tanggal</th>
            <th width="10%"></th>
          </tr>
          <?php if ($result_event && mysqli_num_rows($result_event) > 0) : ?>
            <?php while ($row_event = mysqli_fetch_assoc($result_event)) : ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" data-id="<?= $row_event['id_event'] ?>" class="check-tampil-event" style="transform: scale(1.5); cursor: pointer;" <?= ($row_event['show'] == 1) ? 'checked' : '' ?>>
              </td>
              <td>
                <img src="../uploads/event/<?= htmlspecialchars($row_event['image']) ?>" class="img-preview">
              </td>
              <td>
                <strong style="color:var(--secondary-color); font-size:1.1em;"><?= htmlspecialchars($row_event['event']) ?></strong><br>
                <span style="font-size: 0.9em; color: #64748b;"><?= nl2br(htmlspecialchars($row_event['description'])) ?></span>
              </td>
              <td><?= date('d M Y', strtotime($row_event['date'])) ?></td>
              <td>
                <div class="action-buttons">
                  <button type="button" title="Edit" onclick="bukaEditEvent(<?= $row_event['id_event'] ?>, '<?= htmlspecialchars(addslashes($row_event['event'])) ?>', '<?= $row_event['date'] ?>', '<?= htmlspecialchars(addslashes(preg_replace("/\r|\n/", "\\n", $row_event['description']))) ?>')" class="btn btn-primary btn-icon-only"><i class="fa-solid fa-pen-to-square"></i></button>
                  <button type="button" title="Hapus" onclick="hapusEvent(<?= $row_event['id_event'] ?>, '<?= htmlspecialchars(addslashes($row_event['event'])) ?>', <?= $row_event['photo_count'] ?>)" class="btn btn-danger btn-icon-only"><i class="fa-solid fa-trash-can"></i></button>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else : ?>
             <tr><td colspan='5' style='text-align: center; color: #94a3b8;'>Belum ada data event.</td></tr>
          <?php endif; ?>
        </table>
      </div>
      
      <?php if ($total_pages_event > 1): ?>
        <div class="pagination">
          <?php for ($i = 1; $i <= $total_pages_event; $i++): ?>
            <a href="dashboard.php?page_event=<?= $i ?>#event" class="page-link <?= ($page_event == $i) ? 'active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- TABEL GALERI -->
    <div class="card" id="galeri">
      <div class="card-header">
          <h3><i class="fa-solid fa-images"></i> Manajemen Galeri Foto</h3>
          <div>
            <button class="btn btn-primary" onclick="bukaModalBasic('modalTambahGaleri')"><i class="fa-solid fa-upload"></i> Upload Foto</button>
            <button class="btn btn-danger" onclick="prosesHapusMassal()"><i class="fa-solid fa-trash-can"></i> Hapus Terpilih</button>
          </div>
      </div>
      
      <!-- Filter Galeri -->
      <form method="GET" action="dashboard.php" style="margin-bottom: 20px; display:flex; gap:10px; align-items:center;">
        <input type="hidden" name="tab" value="<?= htmlspecialchars($tab_status) ?>">
        <label style="font-weight:600; color:var(--secondary-color);">Filter Event:</label>
        <select name="filter_galeri" onchange="this.form.submit()" style="width:300px; margin:0;">
            <option value="all">-- Semua Event --</option>
            <?php
            if ($conn) {
                $q_filter_ev = mysqli_query($conn, "SELECT id_event, event FROM events ORDER BY date DESC");
                while ($row_flt = mysqli_fetch_assoc($q_filter_ev)) {
                    $sel = ($filter_galeri_event == $row_flt['id_event']) ? 'selected' : '';
                    echo '<option value="' . $row_flt['id_event'] . '" '.$sel.'>' . htmlspecialchars($row_flt['event']) . '</option>';
                }
            }
            ?>
        </select>
      </form>

      <div class="table-responsive">
        <form id="formGaleriMassal" action="sv_dashboard.php?aksi=hapus_galeri_massal" method="POST">
            <table>
              <tr>
                <th width="5%" style="text-align:center;"><input type="checkbox" onclick="toggleCheckAll(this)" style="transform:scale(1.3); cursor:pointer;"></th>
                <th width="15%">Preview</th>
                <th>Nama Event</th>
                <th width="20%">Diupload Oleh</th>
                <th width="10%"></th>
              </tr>
              <?php if ($result_galeri && mysqli_num_rows($result_galeri) > 0) : ?>
                <?php while ($row_g = mysqli_fetch_assoc($result_galeri)) : ?>
                <tr>
                  <td style="text-align:center;">
                    <input type="checkbox" name="id_galeri_hapus[]" value="<?= $row_g['id_gallery'] ?>" style="transform:scale(1.3); cursor:pointer;">
                  </td>
                  <td><img src="../uploads/galeri/<?= htmlspecialchars($row_g['image_gallery']) ?>" class="img-preview"></td>
                  <td><strong><?= htmlspecialchars($row_g['event']) ?></strong></td>
                  <td><i class="fa-regular fa-circle-user"></i> <?= htmlspecialchars($row_g['full_name']) ?></td>
                  <td>
                    <div class="action-buttons">
                      <button type="button" title="Edit Event Tujuan" onclick="bukaEditGaleri(<?= $row_g['id_gallery'] ?>, <?= $row_g['id_event'] ?>)" class="btn btn-primary btn-icon-only"><i class="fa-solid fa-pen-to-square"></i></button>
                      <button type="button" title="Hapus" onclick="bukaModalConfirm('sv_dashboard.php?aksi=hapus_galeri&id=<?= $row_g['id_gallery'] ?>', 'Hapus foto ini?')" class="btn btn-danger btn-icon-only"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else : ?>
                <tr><td colspan='5' style='text-align: center; color: #94a3b8;'>Belum ada foto yang sesuai filter.</td></tr>
              <?php endif; ?>
            </table>
        </form>
      </div>

      <?php if ($total_pages_galeri > 1): ?>
        <div class="pagination">
          <?php for ($i = 1; $i <= $total_pages_galeri; $i++): ?>
            <a href="dashboard.php?filter_galeri=<?= $filter_galeri_event ?>&page_galeri=<?= $i ?>#galeri" class="page-link <?= ($page_galeri == $i) ? 'active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

  </div> <!-- End Main Content -->

  <!-- KUMPULAN MODAL (POP-UP) -->

  <!-- 1. Modal Confirm Standard -->
  <div id="customConfirmModal" class="modal-overlay">
    <div class="modal-box">
      <h3><i class="fa-solid fa-circle-question"></i> Konfirmasi Aksi</h3>
      <p id="modalMessage">Pesan teks konfirmasi.</p>
      <div class="modal-buttons">
        <button type="button" onclick="tutupModalBasic('customConfirmModal')" class="btn btn-primary">Batal</button>
        <a href="#" id="modalConfirmBtn" class="btn btn-danger"><i class="fa-solid fa-check"></i> Ya, Lanjutkan</a>
      </div>
    </div>
  </div>

  <!-- 2. Modal Warning Hapus Event (Jika ada foto) -->
  <div id="warningDeleteEventModal" class="modal-overlay">
    <div class="modal-box" style="border-top: 5px solid #ef4444;">
      <h3 style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Peringatan Sistem!</h3>
      <p>Event <strong id="warningEventName"></strong> tidak bisa dihapus biasa karena memiliki <strong id="warningPhotoCount"></strong> foto di dalam Galeri.</p>
      <p style="font-size:0.9em; color:#64748b;">Apakah Anda ingin menghapus Event ini BESERTA SEMUA FOTO yang terikat di dalamnya secara permanen?</p>
      <div class="modal-buttons">
        <button type="button" onclick="tutupModalBasic('warningDeleteEventModal')" class="btn btn-primary">Batal</button>
        <a href="#" id="btnForceDelete" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i> Hapus Semua</a>
      </div>
    </div>
  </div>

  <!-- 3. Modal Info Auto-save -->
  <div id="infoModal" class="modal-overlay">
    <div class="modal-box">
      <h3><i class="fa-solid fa-circle-info"></i> Informasi Sistem</h3>
      <p id="infoModalMessage">Pesan informasi.</p>
      <div class="modal-buttons">
        <button type="button" onclick="tutupModalBasic('infoModal')" class="btn btn-primary">Mengerti</button>
      </div>
    </div>
  </div>

  <!-- 4. Modal Tambah Event -->
  <div id="modalTambahEvent" class="modal-overlay">
    <div class="modal-box" style="width: 550px;">
      <h3><i class="fa-solid fa-calendar-plus"></i> Tambah Event Baru</h3>
      <form action="sv_dashboard.php?aksi=tambah_event" method="POST" enctype="multipart/form-data">
        <label>Nama Event:</label>
        <input type="text" name="nama_event" required>
        <label>Tanggal Pelaksanaan:</label>
        <input type="date" name="tanggal_event" required>
        <label>Deskripsi Event:</label>
        <textarea name="deskripsi_event" rows="3" required></textarea>
        <label>Gambar Event (Wajib JPG/PNG, Max 7MB):</label>
        <input type="file" name="gambar_event" accept=".jpg, .jpeg, .png" required>
        <div class="modal-buttons">
          <button type="button" onclick="tutupModalBasic('modalTambahEvent')" class="btn btn-danger">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Simpan Event</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 5. Modal Tambah Galeri -->
  <div id="modalTambahGaleri" class="modal-overlay">
    <div class="modal-box">
      <h3><i class="fa-solid fa-upload"></i> Upload Foto Galeri</h3>
      <form action="sv_dashboard.php?aksi=tambah_galeri" method="POST" enctype="multipart/form-data">
        <label>Pilih Event Terkait:</label>
        <select name="id_event" required>
            <option value="" disabled selected>-- Pilih Event --</option>
            <?php
            if ($conn) {
                $q_ev_list = mysqli_query($conn, "SELECT id_event, event FROM events ORDER BY date DESC");
                while ($r = mysqli_fetch_assoc($q_ev_list)) echo '<option value="'.$r['id_event'].'">'.htmlspecialchars($r['event']).'</option>';
            }
            ?>
        </select>
        <label>File Foto (Wajib JPG/PNG, Max 7MB):</label>
        <input type="file" name="foto" accept=".jpg, .jpeg, .png" required>
        <div class="modal-buttons">
          <button type="button" onclick="tutupModalBasic('modalTambahGaleri')" class="btn btn-danger">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fa-solid fa-cloud-arrow-up"></i> Upload</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 6. Modal Edit Event -->
  <div id="modalEditEvent" class="modal-overlay">
    <div class="modal-box" style="width: 550px;">
      <h3><i class="fa-solid fa-pen-to-square"></i> Edit Event</h3>
      <form action="sv_dashboard.php?aksi=edit_event" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_event" id="edit_id_event">
        <label>Nama Event:</label>
        <input type="text" name="nama_event" id="edit_nama_event" required>
        <label>Tanggal:</label>
        <input type="date" name="tanggal_event" id="edit_tanggal_event" required>
        <label>Deskripsi:</label>
        <textarea name="deskripsi_event" id="edit_deskripsi_event" rows="3" required></textarea>
        <label style="color:#ef4444; font-size:0.9em;">Ganti Foto? (Opsional, Max 7MB):</label>
        <input type="file" name="gambar_event" accept=".jpg, .jpeg, .png">
        <div class="modal-buttons">
          <button type="button" onclick="tutupModalBasic('modalEditEvent')" class="btn btn-danger">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 7. Modal Edit Galeri -->
  <div id="modalEditGaleri" class="modal-overlay">
    <div class="modal-box">
      <h3><i class="fa-solid fa-pen-to-square"></i> Pindah Event Foto</h3>
      <form action="sv_dashboard.php?aksi=edit_galeri" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_gallery" id="edit_id_gallery">
        <label>Pindah ke Event:</label>
        <select name="id_event" id="edit_event_galeri" required>
            <?php
            if ($conn) {
                $q_ev_list2 = mysqli_query($conn, "SELECT id_event, event FROM events ORDER BY date DESC");
                while ($r2 = mysqli_fetch_assoc($q_ev_list2)) echo '<option value="'.$r2['id_event'].'">'.htmlspecialchars($r2['event']).'</option>';
            }
            ?>
        </select>
        <label style="color:#ef4444; font-size:0.9em;">Ganti Foto? (Opsional, Max 7MB):</label>
        <input type="file" name="foto" accept=".jpg, .jpeg, .png">
        <div class="modal-buttons">
          <button type="button" onclick="tutupModalBasic('modalEditGaleri')" class="btn btn-danger">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 8. Modal Confirm Hapus Massal -->
  <div id="confirmHapusMassalModal" class="modal-overlay">
    <div class="modal-box">
      <h3><i class="fa-solid fa-circle-question"></i> Konfirmasi Hapus Massal</h3>
      <p>Anda yakin ingin menghapus SEMUA foto yang telah dicentang secara permanen?</p>
      <div class="modal-buttons">
        <button type="button" onclick="tutupModalBasic('confirmHapusMassalModal')" class="btn btn-primary">Batal</button>
        <button type="button" onclick="submitHapusMassal()" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i> Ya, Hapus Terpilih</button>
      </div>
    </div>
  </div>

  <script src="../JS/script_admin.js"></script>

</body>
</html>