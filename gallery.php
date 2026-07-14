<?php
include "header.php";

$q_hero = mysqli_query($conn, "SELECT image_gallery FROM galleries ORDER BY RAND() LIMIT 5");
$hero_images = [];
if ($q_hero && mysqli_num_rows($q_hero) > 0) {
    while ($row = mysqli_fetch_assoc($q_hero)) {
        $hero_images[] = "uploads/galeri/" . $row['image_gallery'];
    }
} else {
    $hero_images[] = "Assets/img/gereja.jpeg"; 
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$filter_id = isset($_GET['id_event']) ? $_GET['id_event'] : 'all';

$q_dropdown = mysqli_query($conn, "
    SELECT DISTINCT e.id_event, e.event 
    FROM events e 
    JOIN galleries g ON e.id_event = g.id_event 
    ORDER BY e.date DESC
");

$where_clause = "";
if ($filter_id !== 'all') {
    $id_clean = (int)$filter_id;
    $where_clause = " AND e.id_event = $id_clean ";
}

$q_total = mysqli_query($conn, "
    SELECT COUNT(DISTINCT e.id_event) as jml 
    FROM events e 
    JOIN galleries g ON e.id_event = g.id_event 
    WHERE 1=1 $where_clause
");
$total_data = mysqli_fetch_assoc($q_total)['jml'];
$total_pages = ceil($total_data / $limit);

$q_cover = mysqli_query($conn, "
    SELECT e.id_event, e.event, g1.image_gallery as cover_image
    FROM events e
    JOIN galleries g1 ON e.id_event = g1.id_event
    WHERE g1.id_gallery = (
        SELECT MIN(id_gallery) FROM galleries WHERE id_event = e.id_event
    )
    $where_clause
    ORDER BY e.date DESC
    LIMIT $limit OFFSET $offset
");
?>

<section class="hero-galeri">
    <?php foreach ($hero_images as $index => $img_url) : ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: url('<?= htmlspecialchars($img_url) ?>');"></div>
    <?php endforeach; ?>

    <div class="hero-overlay">
        <h1>GALERI FOTO</h1>
        <p>-Dokumentasi Event Gereja-</p>
    </div>
</section>

<section class="galeri-container">
    <div class="breadcrumb">
        <a href="index.php">Beranda</a>
        <span> > </span>
        <span class="active">Galeri</span>
    </div>

    <div class="filter">
        <select id="kategori" onchange="window.location.href='gallery.php?id_event='+this.value">
            <option value="all" <?= $filter_id === 'all' ? 'selected' : '' ?>>Semua Kategori Event</option>
            <?php while ($row_drop = mysqli_fetch_assoc($q_dropdown)) : ?>
                <option value="<?= $row_drop['id_event'] ?>" <?= $filter_id == $row_drop['id_event'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row_drop['event']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="galeri-grid">
        <?php if (mysqli_num_rows($q_cover) > 0) : ?>
            <?php while ($row_cover = mysqli_fetch_assoc($q_cover)) : ?>
                <a href="gallery_detail.php?id=<?= $row_cover['id_event'] ?>" class="card">
                    <div class="card-img-wrapper">
                        <img src="uploads/galeri/<?= htmlspecialchars($row_cover['cover_image']) ?>" alt="<?= htmlspecialchars($row_cover['event']) ?>">
                    </div>
                    <h3><?= htmlspecialchars($row_cover['event']) ?></h3>
                </a>
            <?php endwhile; ?>
        <?php else : ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #64748b;">
                <h3>Belum ada foto yang diunggah untuk kategori ini.</h3>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($total_pages > 1): ?>
        <div class="pagination-frontend">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="gallery.php?id_event=<?= $filter_id ?>&page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>

<script>
    const slides = document.querySelectorAll('.hero-galeri .slide');
    let currentSlide = 0;

    if (slides.length > 1) {
        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        setInterval(nextSlide, 5000);
    }
</script>

<?php include "footer.php"; ?>