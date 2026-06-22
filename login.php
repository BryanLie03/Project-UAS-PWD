<?php
include "security.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login - Gereja Yesus Sejati</title>
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
                    <p>Silakan masuk ke akun Anda</p>
                </div>

                <?php if (isset($_GET['error']) && $_GET['error'] == 'salah') : ?>
                    <div style="background-color: #ffeeed; color: #d9534f; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #f5c6cb; text-align: center;">
                        Email atau kata sandi salah.
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil'): ?>
                    <div style="background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #c8e6c9; text-align: center;">
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