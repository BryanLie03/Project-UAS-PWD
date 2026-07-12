<?php
include "security.php"; // Panggil dari root
prevent_login_bypass();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login - Gereja Yesus Sejati</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>" />
</head>
<body>

    <div class="login-page-wrapper">
        <a href="index.php" class="btn-back-absolute">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

    <div class="main-box">
        <div class="brand-logo-wrapper">
        <img src="Assets/img/Logo-scrolled.png" alt="Logo">
        </div>

    <div class="login-container">
        <div class="login-header">
        <h2>Selamat Datang</h2>
        <p>Silakan masuk ke akun Anda</p>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'salah') : ?>
        <div class="alert alert-danger">
            Email atau kata sandi salah.
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') : ?>
        <div class="alert alert-success">
            Pendaftaran berhasil! Silakan login.
        </div>
        <?php endif; ?>

        <form action="sv_login.php" method="POST">
        <div class="input-group">
            <label class="label-login" for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email" required>
        </div>

        <div class="input-group">
            <label class="label-login" for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>
    
        <div class="utilities">
            <label class="remember-me">
            <input type="checkbox" name="remember"> Ingat Saya
            </label>
        </div>

        <button type="submit" class="login-btn">Masuk</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </div>
        </div>
    </div>
</div>

</body>
</html>