<?php
// Cek apakah session sudah dimulai, jika belum maka mulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Penjaga pintu: Jika user SUDAH login, langsung lempar ke index.php
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location:index.php");
    exit();
}
?>