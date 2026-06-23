<?php
session_start(); // Tetap butuh session_start untuk kirim pesan error via URL
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil input dari form pendaftaran
    // Pastikan nilai di dalam $_POST['...'] sesuai dengan atribut 'name' di form HTML register.php
    $nama_input  = $_POST['full_name']; 
    $email_input = $_POST['email'];
    $phone_input = $_POST['phone_number']; 
    $password_input = md5($_POST['password']); // Tetap menggunakan MD5
    
    // Set role standar untuk setiap pendaftar baru
    $default_role = 'user'; 

    // 2. CEK EMAIL GANDA (Mencegah 1 email dipakai daftar 2 kali)
    $stmt_cek = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt_cek->bind_param("s", $email_input);
    $stmt_cek->execute();
    $hasil_cek = $stmt_cek->get_result();

    if ($hasil_cek->num_rows > 0) {
        // Jika email sudah terdaftar, kembalikan ke halaman register dengan pesan error
        header("location:register.php?error=email_terdaftar");
        $stmt_cek->close();
        exit();
    }
    $stmt_cek->close();

    // 3. MASUKKAN DATA BARU KE DATABASE (Proses Register Sebenarnya)
    // Tanda ? akan diisi oleh data user secara aman untuk mencegah SQL Injection
    $stmt_insert = $conn->prepare("INSERT INTO users (full_name, email, password, role, phone_number) VALUES (?, ?, ?, ?, ?)");
    
    // "sssss" menandakan ada 5 data berbentuk string/teks yang akan dimasukkan
    $stmt_insert->bind_param("sssss", $nama_input, $email_input, $password_input, $default_role, $phone_input);

    // Jalankan perintah Insert
    if ($stmt_insert->execute()) {
        // Jika pendaftaran sukses, lempar ke halaman login
        header("location:login.php?pesan=berhasil");
    } else {
        // Jika gagal karena error database
        header("location:register.php?error=sistem");
    }
    
    $stmt_insert->close();
    exit();

} else {
    // Jika file ini diakses langsung tanpa lewat form
    header("location:register.php");
    exit();
}
?>