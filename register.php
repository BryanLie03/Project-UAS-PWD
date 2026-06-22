<?php
include "security.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Register - Gereja Yesus Sejati</title>
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>" />
</head>
<body>

<div class="login-page-wrapper">
    <div class="main-box">
            <div class="brand-logo-wrapper">
            <img src="Assets/img/Logo-scrolled.png" alt="Logo">
            </div>
        <div class="login-container">
                <div class="login-header">
                    <h2>Selamat Datang</h2>
                    <p>Silakan daftar untuk membuat akun Anda</p>
                </div>

                <?php 
                if (isset($_GET['error'])) {
                    $pesan_error = "";
                    if ($_GET['error'] == 'email_terdaftar') {
                        $pesan_error = "Alamat email ini sudah terdaftar.";
                    } else if ($_GET['error'] == 'sistem') {
                        $pesan_error = "Terjadi kesalahan pada sistem server.";
                    }

                    echo '<div style="background-color: #ffeeed; color: #d9534f; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #f5c6cb; text-align: center;">';
                    echo $pesan_error;
                    echo '</div>';
                }
                ?>

                <form action="sv_register.php" method="POST">
                    <div class="input-group">
                        <label class="label-login" for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="label-login" for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="input-group">
                        <label class="label-login" for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" placeholder="Masukkan nomor telepon" required>
                    </div>

                    <div class="input-group"> 
                        <label class="label-login" for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
                    </div>

                    <button type="submit" class="login-btn">Daftar</button>
                </form>

                <div class="register-link">
                    Sudah punya akun? <a href="login.php">Masuk sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>