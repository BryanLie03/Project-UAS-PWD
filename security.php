<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false, 
    'httponly' => true,  
    'samesite' => 'Strict'
    ]);
    session_start();
}

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (!isset($_SESSION['status']) && isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])) {
  include "connection.php"; 
    
    $cookie_id = $_COOKIE['user_id'];
    
    $stmt = $conn->prepare("SELECT id_user, full_name, email, role FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $cookie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if ($_COOKIE['user_key'] === hash('sha256', $user['email'])) {
        $_SESSION['id_user']   = $user['id_user'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['status']    = "login";
        }
    }
    $stmt->close();
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

function prevent_login_bypass($redirect_url = "index.php") {
    if (is_logged_in()) {
        header("Location: " . $redirect_url);
    exit();
    }
}
?>