<?php
session_start();
include "header.php";
?>

<body>
<section class="hero-galeri">
    <div class="slide active" style="background-image: url('assets/img/KPI/SKKphotobersama1.jpg');"></div>
    <div class="slide" style="background-image: url('assets/img/KKR/fotobersamakkr.jpg');"></div>
    <div class="slide" style="background-image: url('assets/img/BI/templatebiman.png');"></div>
    <div class="slide" style="background-image: url('assets/img/panti/fotobersama3.jpg');"></div>
    <div class="slide" style="background-image: url('assets/img/DD/SKK1.jpg');"></div>

    <div class="hero-overlay">
        <h1>GALERI FOTO</h1>
        <p>-Dokumentasi Kegiatan Gereja-</p>
    </div>
</section>

<section class="galeri-container">
    <div class="breadcrumb">
        <a href="index.php">Beranda</a>
        <span> > </span>
        <span class="active">Galeri</span>
    </div>

    <div class="filter">
        <select id="kategori">
            <option value="all">Semua Kategori</option>
            <option value="kpi">KPI (Kebaktian Penyegaran Iman)</option>
            <option value="kkr">KKR (Kebaktian Kebangunan Rohani)</option>
            <option value="bi">BI (Bina Iman)</option>
            <option value="panti">Kunjungan Panti Asuhan</option>
            <option value="donor">Donor Darah</option>
        </select>
    </div>

    <div class="galeri-grid">

        <a href="gallery_detail.php?kategori=kpi" class="card" data-category="kpi">
            <img src="assets/img/KPI/SKKphotobersama1.jpg" alt="">
            <h3>Kebaktian Penyegaran Iman</h3>
        </a>

        <a href="gallery_detail.php?kategori=kkr" class="card" data-category="kkr">
            <img src="assets/img/KKR/fotobersamakkr.jpg" alt="">
            <h3>Kebaktian Kebangunan Rohani</h3>
        </a>

        <a href="gallery_detail.php?kategori=bi" class="card" data-category="bi">
            <img src="assets/img/BI/templatebiman.png" alt="">
            <h3>Bina Iman</h3>
        </a>

        <a href="gallery_detail.php?kategori=panti" class="card" data-category="panti">
            <img src="assets/img/panti/fotobersama3.jpg" alt="">
            <h3>Kunjungan Panti Asuhan</h3>
        </a>

        <a href="gallery_detail.php?kategori=donor" class="card" data-category="donor">
            <img src="assets/img/DD/SKK1.jpg" alt="">
            <h3>Donor Darah</h3>
        </a>
    </div>
</section>

<script>
    const slides = document.querySelectorAll('.hero-galeri .slide');
    let currentSlide = 0;

    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    setInterval(nextSlide, 5000);
</script>

</body>

<?php 
    include "footer.php"; 
?>
