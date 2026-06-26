<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password_input = md5($_POST['password']);
    $stmt = $conn->prepare("SELECT id_user, full_name, role FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password_input);
    $stmt->execute();
    $result = $stmt->get_result();

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("location:login.php?error=email");
    exit();
}
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['id_user']   = $user['id_user'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['status']    = "login";

    if ($_SESSION['role'] === 'admin') {
            header("location:admin/dashboard.php");
        } else {
            header("location:index.php");
        }
        exit();
    } else {
        header("location:login.php?error=salah");
        exit();
    }
} else {
    header("location:login.php");
    exit();
}
?>