<?php
session_start();

// Cek keamanan, pastikan yang melakukan aksi ini adalah admin
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Jika tombol diklik dan ada id_video yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_video'])) {
    $id_video = $_POST['id_video'];
    
    // Simpan ID video ke dalam file teks (sebagai pengganti tabel database sementara)
    file_put_contents('video_aktif.txt', $id_video);
    
    // Lempar kembali ke dashboard dengan membawa status sukses
    header("Location: admin_dashboard.php?status=sukses");
    exit;
} else {
    // Jika diakses langsung tanpa lewat form, kembalikan ke dashboard
    header("Location: admin_dashboard.php");
    exit;
}
?>