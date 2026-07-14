<?php
include "security.php";
include "koneksi.php";

// Gunakan fungsi dari security.php untuk memastikan user sudah login
require_login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['id_user'];
    
    // Perbaikan: Gunakan 'tanggal_doa' (Sesuai dengan name di index.php)
    $tanggal = $_POST['tanggal_doa']; 
    
    // Perbaikan: Hilangkan htmlspecialchars di sini (Simpan data mentah)
    $doa = trim($_POST['isi_doa']); 

    // Validasi dasar
    if (empty($tanggal) || empty($doa)) {
        header("Location: index.php?pesan_doa=gagal#doa");
        exit();
    }

    // Masukkan ke database
    $stmt = $conn->prepare("INSERT INTO prayers (pray, id_user, date) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $doa, $id_user, $tanggal);

    if ($stmt->execute()) {
        header("Location: index.php?pesan_doa=sukses#doa");
    } else {
        header("Location: index.php?pesan_doa=gagal#doa");
    }
    
    $stmt->close();
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>