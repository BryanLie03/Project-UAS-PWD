<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

$timeout_duration = 1800; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: login.php?pesan=timeout"); 
    exit();
}
$_SESSION['last_activity'] = time();


function is_logged_in() {
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php?pesan=belum_login");
        exit();
    }
}

function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: index.php?pesan=akses_ditolak");
        exit();
    }
}

function prevent_login_bypass() {
    if (is_logged_in()) {
        header("Location: index.php");
        exit();
    }
}
?>