<?php
session_start();
include "koneksi.php";

// Pastikan request berasal dari form POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Keamanan: Mencegah SQL Injection
    $email_input = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = md5($_POST['password']); // Password di-MD5

    // Mencari data di database
    $query = "SELECT * FROM users WHERE email='$email_input' AND password='$password_input'";
    $result = mysqli_query($conn, $query);

    // Jika data cocok
    if (mysqli_num_rows($result) === 1) {
        $data_user = mysqli_fetch_assoc($result);

        // Buat Sesi
        $_SESSION['id_user']   = $data_user['id_user'];
        $_SESSION['full_name'] = $data_user['full_name'];
        $_SESSION['role']      = $data_user['role'];
        $_SESSION['status']    = "login";

        // Arahkan ke halaman utama/dashboard
        header("location:index.php");
        exit();
    } else {
        // Jika salah, kembalikan ke halaman login dan kirim pesan error lewat URL
        header("location:login.php?error=salah");
        exit();
    }
} else {
    // Jika ada yang mencoba buka file ini secara langsung tanpa lewat form
    header("location:login.php");
    exit();
}
?>