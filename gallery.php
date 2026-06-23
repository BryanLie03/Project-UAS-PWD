<?php
session_start();
include "header.php";
?>

<body>
<section class="hero-galeri">
    <h1>GALERI FOTO</h1>
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

        <div class="card" data-category="kpi">
            <img src="img/KPI.jpeg" alt="">
            <h3>Kebaktian Penyegaran Iman</h3>
        </div>

        <div class="card" data-category="kkr">
            <img src="img/kkr.jpg" alt="">
            <h3>Kebaktian Kebangunan Rohani</h3>
        </div>

        <div class="card" data-category="bi">
            <img src="img/bi.jpg" alt="">
            <h3>Bina Iman</h3>
        </div>

        <div class="card" data-category="panti">
            <img src="img/panti.jpg" alt="">
            <h3>Kunjungan Panti Asuhan</h3>
        </div>

        <div class="card" data-category="donor">
            <img src="img/donor.jpg" alt="">
            <h3>Donor Darah</h3>
        </div>
    </div>
</section>
</body>

      <?php 
  include "footer.php"; 
?>

