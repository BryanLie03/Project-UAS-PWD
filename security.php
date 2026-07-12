<?php
// ==========================================
// 1. PENGATURAN & INISIALISASI SESSION
// ==========================================
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false, // Ubah ke true jika website sudah menggunakan HTTPS (SSL/TLS)
    'httponly' => true,  // Mencegah akses cookie dari JavaScript (XSS Protection)
    'samesite' => 'Strict'
    ]);
    session_start();
}

// Mencegah serangan Session Fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// ==========================================
// 2. TAMBAHAN AUTO-LOGIN DARI COOKIE
// ==========================================
if (!isset($_SESSION['status']) && isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])) {
  include "koneksi.php"; // Memastikan koneksi database tersedia
    
    $cookie_id = $_COOKIE['user_id'];
    
    // Ambil data user berdasarkan ID dari cookie
    $stmt = $conn->prepare("SELECT id_user, full_name, email, role FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $cookie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi kecocokan hash email untuk keamanan tambahan
        if ($_COOKIE['user_key'] === hash('sha256', $user['email'])) {
        $_SESSION['id_user']   = $user['id_user'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['status']    = "login";
        }
    }
    $stmt->close();
}

// Mencegah serangan Session Fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// ==========================================
// 3. KONTROL TIMEOUT KETIDAKAKTIFAN
// ==========================================
$timeout_duration = 1800; // 30 menit (dalam detik)

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: login.php?pesan=timeout"); 
    exit();
}

$_SESSION['last_activity'] = time();

// ==========================================
// 4. FUNGSI-FUNGSI BANTUAN AUTENTIKASI
// ==========================================

/**
 * Mengecek apakah user saat ini sedang login
 */
function is_logged_in() {
    return isset($_SESSION['status']) && $_SESSION['status'] == "login";
}

/**
 * Memaksa user untuk login sebelum mengakses halaman tertentu
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php?pesan=belum_login");
    exit();
    }
}

/**
 * Membatasi akses halaman hanya untuk role tertentu (misal: 'admin')
 */
function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: index.php?pesan=akses_ditolak");
    exit();
    }
}

/**
 * Mencegah user yang SUDAH login membuka halaman login/register kembali
 */
function prevent_login_bypass($redirect_url = "index.php") {
    if (is_logged_in()) {
        header("Location: " . $redirect_url);
    exit();
    }
}
?>