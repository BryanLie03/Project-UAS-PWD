<?php
// Pastikan session_start() hanya dipanggil jika sesi belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// KEAMANAN: Periksa apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login' || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  // Jika bukan admin atau belum login, lempar kembali ke halaman login di root
    header("Location: ../login.php");
    exit();
}
?>