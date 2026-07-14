<?php
include 'header.php';

$id_event = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validasi apakah event tersebut ada
$q_event = mysqli_query($conn, "SELECT event FROM events WHERE id_event = $id_event");
if (!$q_event || mysqli_num_rows($q_event) == 0) {
    // Jika user iseng memasukkan ID ngawur di URL, kembalikan ke galeri
    header("Location: gallery.php");
    exit;
}
$nama_event = mysqli_fetch_assoc($q_event)['event'];

// Pengaturan Pagination Halaman Detail
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Menghitung total foto di event ini
$q_total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM galleries WHERE id_event = $id_event");
$total_data = mysqli_fetch_assoc($q_total)['jml'];
$total_pages = ceil($total_data / $limit);

// Mengambil 10 foto untuk halaman yang sedang aktif
$q_foto = mysqli_query($conn, "SELECT image_gallery FROM galleries WHERE id_event = $id_event ORDER BY id_gallery DESC LIMIT $limit OFFSET $offset");

// Cover hero dinamis (Mengambil gambar pertama dari event ini untuk latar atas)
$cover_hero = "Assets/img/gereja.jpeg"; // Fallback awal
$q_cover = mysqli_query($conn, "SELECT image_gallery FROM galleries WHERE id_event = $id_event ORDER BY id_gallery ASC LIMIT 1");
if ($row_cover = mysqli_fetch_assoc($q_cover)) {
    $cover_hero = "uploads/galeri/" . $row_cover['image_gallery'];
}
?>

<section class="hero-galeri">
    <div class="slide active" style="background-image: url('<?= htmlspecialchars($cover_hero) ?>');"></div>

    <div class="hero-overlay">
        <h1><?= htmlspecialchars($nama_event) ?></h1>
        <p>-Dokumentasi Event Gereja-</p>
    </div>
</section>

<section class="galeri-container">
    <div class="breadcrumb">
        <a href="index.php">Beranda</a>
        <span> > </span>
        <a href="gallery.php">Galeri</a>
        <span> > </span>
        <span class="active"><?= htmlspecialchars($nama_event) ?></span>
    </div>

    <!-- Grid Foto Detail -->
    <div class="foto-grid">
        <?php if (mysqli_num_rows($q_foto) > 0): ?>
            <?php while ($foto = mysqli_fetch_assoc($q_foto)): ?>
                <div class="foto-item">
                    <img src="uploads/galeri/<?= htmlspecialchars($foto['image_gallery']) ?>" alt="Dokumentasi <?= htmlspecialchars($nama_event) ?>">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #64748b;">
                <p>Tidak ada foto yang ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination Detail -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination-frontend" style="margin-top: 40px;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="gallery_detail.php?id=<?= $id_event ?>&page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'footer.php'; ?>