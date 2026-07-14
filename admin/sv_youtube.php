<?php
include "../security.php"; 
require_login();           
require_role("admin");     
include "../koneksi.php";

$aksi = $_GET['aksi'] ?? '';
$id_user_aktif = $_SESSION['id_user']; // Ambil ID User yang sedang login

// 1. Simpan/Timpa Video Utama Baru
if ($aksi == 'set_video' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_video'])) {
    
    $id_video = $_POST['id_video'];
    $judul_video = $_POST['judul_video'] ?? 'Livestream Khotbah';
    $tanggal_sekarang = date('Y-m-d');
    $desc = "Diambil dari YouTube API";

    // Karena kita hanya butuh 1 video tampil, kita kosongkan dulu tabel, lalu Insert. 
    // Atau bisa juga Update row pertama. Kita pakai TRUNCATE lalu INSERT agar id selalu baru/bersih.
    mysqli_query($conn, "TRUNCATE TABLE youtube");
    
    $stmt = $conn->prepare("INSERT INTO youtube (id_user, Title, description, link, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $id_user_aktif, $judul_video, $desc, $id_video, $tanggal_sekarang);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?msg=video_sukses#video");
    exit;
} 

// 2. Ubah Judul Video Saja
elseif ($aksi == 'edit_judul_video' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_baru = $_POST['judul_diedit'];
    
    // Update judul pada video terbaru (Limit 1)
    $stmt = $conn->prepare("UPDATE youtube SET Title = ? ORDER BY id_youtube DESC LIMIT 1");
    $stmt->bind_param("s", $judul_baru);
    $stmt->execute();
    $stmt->close();
    
    header("Location: dashboard.php?msg=video_edit_sukses#video");
    exit;
} 

else {
    header("Location: dashboard.php");
    exit;
}
?>