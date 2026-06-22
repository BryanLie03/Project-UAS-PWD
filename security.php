<?php
// 1. Pengaturan Cookie Keamanan (Jalankan SEBELUM session_start)
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false, // Set ke true jika website sudah menggunakan HTTPS
    'httponly' => true, // Mencegah akses cookie oleh JavaScript (XSS Protection)
    'samesite' => 'Strict'
]);

// Mulai sesi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Mencegah Session Fixation (Regenerasi ID sesi)
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 3. Logika Idle Timeout (30 Menit)
// Jika user tidak aktif selama 30 menit, sesi hancur otomatis
$timeout_duration = 1800; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: ../login.php?pesan=timeout");
    exit();
}
$_SESSION['last_activity'] = time();

// --- FUNGSI PROTEKSI ---

// Fungsi: Cek apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

// Fungsi: Memaksa user untuk login (Gunakan di halaman Admin/User)
function require_login($redirect_path = "../login.php") {
    if (!is_logged_in()) {
        header("Location: $redirect_path?pesan=belum_login");
        exit();
    }
}

// Fungsi: Memaksa role tertentu (Gunakan di halaman Admin)
function require_role($role, $redirect_path = "../index.php") {
    // Cek apakah rolenya sesuai
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: $redirect_path?pesan=akses_ditolak");
        exit();
    }
}

// Fungsi: Mencegah user yang sudah login untuk akses halaman login/register
function prevent_login_bypass($redirect_path = "index.php") {
    if (is_logged_in()) {
        header("Location: $redirect_path");
        exit();
    }
}
?>