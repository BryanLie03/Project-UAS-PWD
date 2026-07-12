<?php
session_start();

// ==============================================================================
// KEAMANAN: Cek keamanan, pastikan yang melakukan aksi ini adalah admin
// ==============================================================================
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login' || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php"); // Naik 1 folder ke root untuk mengakses login.php
    exit;
}

// ==============================================================================
// PROSES SIMPAN ID VIDEO YOUTUBE
// ==============================================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_video'])) {
    $id_video = $_POST['id_video'];

  // Simpan ID video ke dalam file teks (sebagai pengganti tabel database sementara)
    file_put_contents('video_aktif.txt', $id_video);

  // Lempar kembali ke dashboard dengan membawa status sukses
  header("Location: dashboard.php?msg=video_sukses"); // Disesuaikan dengan nama file asli 'dashboard.php'
    exit;
} else {
  // Jika diakses langsung tanpa lewat form, kembalikan ke dashboard
    header("Location: dashboard.php");
    exit;
}
?>