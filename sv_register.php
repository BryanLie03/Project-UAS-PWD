<?php
session_start(); 
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_input = htmlspecialchars(trim($_POST['full_name']), ENT_QUOTES, 'UTF-8');
    $email_input = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone_input = htmlspecialchars(trim($_POST['phone_number']), ENT_QUOTES, 'UTF-8');
    $password_input = md5($_POST['password']);
    $default_role = 'user'; 

    $stmt_cek = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt_cek->bind_param("s", $email_input);
    $stmt_cek->execute();
    $hasil_cek = $stmt_cek->get_result();

    if ($hasil_cek->num_rows > 0) {
        header("location:register.php?error=email_terdaftar");
        $stmt_cek->close();
        exit();
    }
    $stmt_cek->close();
    $stmt_insert = $conn->prepare("INSERT INTO users (full_name, email, password, role, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("sssss", $nama_input, $email_input, $password_input, $default_role, $phone_input);

    if ($stmt_insert->execute()) {
        header("location:login.php?pesan=berhasil");
    } else {
        header("location:register.php?error=sistem");
    }   
    $stmt_insert->close();
    exit();
} else {
    header("location:register.php");
    exit();
}
?>