<?php
include "security.php";
include "connection.php";

require_login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['id_user'];
    
    $tanggal = $_POST['tanggal_doa']; 
    
    $doa = trim($_POST['isi_doa']); 

    if (empty($tanggal) || empty($doa)) {
        header("Location: index.php?pesan_doa=gagal#doa");
        exit();
    }

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