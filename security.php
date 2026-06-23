<?php
// 1. Pengaturan Cookie & Sesi (Gunakan pengecekan status agar tidak duplikat)
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

// 2. Mencegah Session Fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 3. Logika Idle Timeout (30 Menit)
$timeout_duration = 1800; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: login.php?pesan=timeout"); // Arahkan ke root
    exit();
}
$_SESSION['last_activity'] = time();

// --- FUNGSI PROTEKSI ---

function is_logged_in() {
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

// Fungsi sederhana: selalu arahkan ke login.php di root
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php?pesan=belum_login");
        exit();
    }
}

// Fungsi sederhana: selalu arahkan ke index.php di root
function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: index.php?pesan=akses_ditolak");
        exit();
    }
}

// Fungsi: Mencegah user yang sudah login akses halaman login/register
function prevent_login_bypass() {
    if (is_logged_in()) {
        header("Location: index.php");
        exit();
    }
}
?>