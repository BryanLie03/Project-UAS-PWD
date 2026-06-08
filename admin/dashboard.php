<?php
include "security.php";

$username = $_SESSION['username'];

echo "Welcome, " . $username;
?>