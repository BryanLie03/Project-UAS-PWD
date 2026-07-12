<?php
session_start();

// Kosongkan semua variabel sesi
$_SESSION = [];
session_unset();

// Hancurkan sesi sepenuhnya dari server
session_destroy();
if (isset($_COOKIE['user_id'])) {
  setcookie('user_id', '', time() - 3600, '/');
  setcookie('user_key', '', time() - 3600, '/');
}

// Arahkan kembali ke halaman login (naik 1 folder ke root)
header("Location: ../login.php");
exit();
?>