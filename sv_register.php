<?php
session_start(); 
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_input = htmlspecialchars(trim($_POST['full_name']), ENT_QUOTES, 'UTF-8');
    $email_input = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

  // Validasi format email sebelum mengecek ke database
if (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=sistem"); 
    exit();
}

    $phone_input = htmlspecialchars(trim($_POST['phone_number']), ENT_QUOTES, 'UTF-8');
    $password_input = md5($_POST['password']);
    $default_role = 'user'; 

  // Cek apakah email sudah terdaftar
    $stmt_cek = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt_cek->bind_param("s", $email_input);
    $stmt_cek->execute();
    $hasil_cek = $stmt_cek->get_result();

 if ($hasil_cek->num_rows > 0) {
    header("Location: register.php?error=email_terdaftar");
    $stmt_cek->close();
    exit();
}
$stmt_cek->close();

  // Jika email belum ada, proses insert data
$stmt_insert = $conn->prepare("INSERT INTO users (full_name, email, password, role, phone_number) VALUES (?, ?, ?, ?, ?)");
$stmt_insert->bind_param("sssss", $nama_input, $email_input, $password_input, $default_role, $phone_input);

if ($stmt_insert->execute()) {
    header("Location: login.php?pesan=berhasil");
} else {
    header("Location: register.php?error=sistem");
}   

$stmt_insert->close();
    exit();
} else {
    header("Location: register.php");
    exit();
}
?>