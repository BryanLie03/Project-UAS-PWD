<?php
date_default_timezone_set('Asia/Jakarta');

$host = "localhost";
$username = "root";
$password = "";
$database = "gereja_yesus_sejati";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>