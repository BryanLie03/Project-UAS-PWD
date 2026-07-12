<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  
  // Validasi format email SEBELUM melakukan query ke database
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=email");
    exit();
    }

    $password_input = md5($_POST['password']);
    
    $stmt = $conn->prepare("SELECT id_user, full_name, role FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    $_SESSION['id_user']   = $user['id_user'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role']      = $user['role'];
    $_SESSION['status']    = "login";

    if (isset($_POST['remember'])) {
      // Menyimpan ID user ke cookie selama 30 hari (30 * 24 * 3600 detik)
      // Parameter terakhir 'true' untuk httponly agar lebih aman
      setcookie('user_id', $user['id_user'], time() + (30 * 24 * 3600), '/', '', false, true);
      setcookie('user_key', hash('sha256', $user['email']), time() + (30 * 24 * 3600), '/', '', false, true);
    }
    
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
    } else {
    header("Location: login.php?error=salah");
    exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>