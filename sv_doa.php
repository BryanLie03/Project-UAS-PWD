<?php
session_start();
include "koneksi.php";

// Pastikan hanya user yang sudah login yang bisa memproses file ini
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil ID user dari sesi saat dia login
    $id_user = $_SESSION['id_user'];
    
  // Ambil data dari form (atribut 'name')
    $tanggal = $_POST['tanggal'];
    $doa     = htmlspecialchars(trim($_POST['isi_doa']), ENT_QUOTES, 'UTF-8'); // Bersihkan input dari kode jahat

  // Pastikan form tidak kosong (lapisan keamanan tambahan di sisi server)
    if (empty($tanggal) || empty($doa)) {
        header("Location: index.php?pesan_doa=gagal#doa");
        exit();
    }

  // Masukkan ke tabel `prayers` sesuai struktur database Anda
  // Kolom: pray, id_user, date (id_pray otomatis diisi oleh database karena AUTO_INCREMENT)
    $stmt = $conn->prepare("INSERT INTO prayers (pray, id_user, date) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $doa, $id_user, $tanggal);

    if ($stmt->execute()) {
        // Jika berhasil, kembalikan ke index.php bagian section doa
        header("Location: index.php?pesan_doa=sukses#doa");
    } else {
        // Jika gagal
        header("Location: index.php?pesan_doa=gagal#doa");
    }
    
    $stmt->close();
    exit();
    } else {
    header("Location: index.php");
    exit();
}
?>