<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    // Hash password menggunakan MD5 sesuai permintaan Anda
    $password_input = md5($_POST['password']); 

    // Menggunakan Prepared Statement untuk mencegah SQL Injection
    // Kita mencari email dan password sekaligus di database
    $stmt = $conn->prepare("SELECT id_user, full_name, role FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Buat Sesi
        $_SESSION['id_user']   = $user['id_user'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['status']    = "login";

        header("location:index.php");
        exit();
    } else {
        // Jika email atau password salah
        header("location:login.php?error=salah");
        exit();
    }
} else {
    header("location:login.php");
    exit();
}
?>