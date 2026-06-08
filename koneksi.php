<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "gereja_yesus_sejati";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("koneksi database gagal:" . mysqli_connect_error());
}

?>