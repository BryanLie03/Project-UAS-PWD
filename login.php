<?php
session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location:index.php");
    exit();
}

include "koneksi.php";
include "header.php";

$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = mysqli_real_escape_string($conn, $_POST['username']);
    $password_input = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM user WHERE username='$username_input' OR email='$username_input'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $data_user = mysqli_fetch_assoc($result);

        if (password_verify($password_input, $data_user['password'])) {
            $_SESSION['id_user']  = $data_user['id_user'];
            $_SESSION['username'] = $data_user['username'];
            $_SESSION['nama']     = $data_user['nama']; 
            $_SESSION['status']   = "login";

            header("location:index.php");
            exit();
        } else {
            $pesan_error = "Kata sandi yang Anda masukkan salah.";
        }
    } else {
        $pesan_error = "Username atau Email tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login - Gereja Yesus Sejati</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="style-login.css">
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

                <?php if (!empty($pesan_error)) : ?>
                    <div style="background-color: #ffeeed; color: #d9534f; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #f5c6cb; text-align: center;">
                        <?php echo $pesan_error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="input-group">
                        <label for="username">Username atau Email</label>
                        <input type="text" id="username" name="username" placeholder="Masukkan email atau username" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
                    </div>

                    <div class="utilities">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Ingat saya
                        </label>
                        <a href="#" class="forgot-password">Lupa Password?</a>
                    </div>

                    <button type="submit" class="login-btn">Masuk</button>
                </form>

                <div class="register-link">
                    Belum punya akun? <a href="#">Daftar sekarang</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
