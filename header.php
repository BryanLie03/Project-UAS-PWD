<?php
include "security.php";
include "connection.php";
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gereja Yesus Sejati Pontianak</title>
  
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>" />
  
  <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" /> 
</head>
  
<body>
  <header>
    <nav class="navbar">
      <a href="index.php"> 
        <div class="logo">
          <img src="Assets/img/Logo.png" alt="Logo GYS Awal" class="logo-awal" />
          <img src="Assets/img/Logo-scrolled.png" alt="Logo GYS Scrolled" class="logo-scrolled" />
        </div>
      </a>
      
      <ul class="nav-links">
        <li><a href="index.php#dasar-kepercayaan">Dasar Kepercayaan</a></li>
        <li><a href="index.php#sejarah">Sejarah GYS Pontianak</a></li>
        <li><a href="index.php#kegiatan">Kegiatan</a></li>
        <li><a href="gallery.php">Galeri</a></li>
        
        <li class="nav-right"> 
          <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login") : ?>
            <div class="profile-dropdown">
              <button class="profile-btn">
                <?php 
                $nama = $_SESSION['full_name'];
                $inisial = substr($nama, 0, 1); 
                
                if (strpos($nama, ' ') !== false) {
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
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                  <a href="admin/dashboard.php" class="admin-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                  </a>
                <?php endif; ?>
                
                <a href="admin/logout.php" class="logout-link">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </div>
            </div>
          <?php else : ?>
            <a href="login.php" class="btn-login">Login</a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
  </header>