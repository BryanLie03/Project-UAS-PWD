<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_input = mysqli_real_escape_string($conn, $_POST['phone']); 
    $nama_input = mysqli_real_escape_string($conn, $_POST['nama']);   
    
    $hashed_password = md5($_POST['password']); // MD5
    $default_role = 'user';

    // Cek apakah email sudah terdaftar
    $cek_query = "SELECT * FROM users WHERE email='$email_input'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        // Email sudah ada, kembalikan ke halaman register dengan pesan error
        header("location:register.php?error=email_terdaftar");
        exit();
    } else {
        // Simpan data
        $insert_query ="INSERT INTO users (full_name, email, password, role, phone_number) 
                        VALUES ('$nama_input', '$email_input', '$hashed_password', '$default_role', '$phone_input')";
        
        if(mysqli_query($conn, $insert_query)){
            // Sukses mendaftar, lempar ke login
            header("location:login.php?pesan=berhasil");
            exit();
        } else {
            // Error sistem database
            header("location:register.php?error=sistem");
            exit();
        }
    }
} else {
    header("location:register.php");
    exit();
}
?>