<?php
// Pastikan session_start() sudah dipanggil di Index.php sebelum include file ini
include "koneksi.php";
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gereja Yesus Sejati Pontianak</title>
    <link rel="stylesheet" href="CSS/style.css" /> 
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script> 
    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" /> 
  </head>
  
  <body>
    <header>
      <nav class="navbar">
        <a href="index.php"> 
          <div class="logo">
            <img src="Assets/img/Logo.png" alt="Logo" class="logo-awal" />
            <img src="Assets/img/Logo-scrolled.png" alt="Logo" class="logo-scrolled" />
          </div>
        </a>
        <ul class="nav-links">
          <li><a href="#dasar-kepercayaan">Dasar Kepercayaan</a></li>
          <li><a href="#sejarah">Sejarah GYS Pontianak</a></li>
          <li><a href="#kegiatan">Kegiatan</a></li>
          <li><a href="#Galeri">Galeri</a></li>
          
          <li class="nav-right"> 
              <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login") : ?>
                <div class="profile-dropdown">
            <button class="profile-btn">
                <?php 
                $nama = $_SESSION['full_name'];
                $inisial = substr($nama, 0, 1); // Ambil huruf pertama
                // Jika ada spasi, ambil huruf pertama dari kata kedua
                if(strpos($nama, ' ') !== false) {
                    $pecah = explode(' ', $nama);
                    $inisial = strtoupper(substr($pecah[0], 0, 1) . substr($pecah[1], 0, 1));
                } else {
                    $inisial = strtoupper(substr($nama, 0, 1));
                }
                ?>
                <div class="profile-icon"><?php echo $inisial; ?></div>
                <span class="profile-name"><?php echo $_SESSION['full_name']; ?> ▼</span>
            </button>
            
            <div class="dropdown-content">
                <a href="admin/logout.php">Logout</a>
            </div>
        </div>
    <?php else : ?>
        <a href="login.php" class="btn-login">Login</a>
    <?php endif; ?>
</li>
        </ul>
      </nav>
    </header>