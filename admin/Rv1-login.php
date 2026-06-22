<?php
session_start();

if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simulasi 2 Akun Admin
    $akun_admin = [
        'admin1' => ['password' => 'pass123', 'nama' => 'Budi (Admin 1)'],
        'admin2' => ['password' => 'pass456', 'nama' => 'Siti (Admin 2)']
    ];

    if (array_key_exists($username, $akun_admin) && $akun_admin[$username]['password'] === $password) {
        $_SESSION['status_login'] = 'admin';
        $_SESSION['nama_admin'] = $akun_admin[$username]['nama']; 
        
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f1f5f9; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #2563eb; }
        .error { color: #ef4444; margin-bottom: 15px; font-size: 0.9em; }
        .info { font-size: 0.8em; color: #64748b; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Admin</h2>
        <?php if(isset($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
        <div class="info">
            Akun Test:<br>
            User: <b>admin1</b> | Pass: <b>pass123</b><br>
            User: <b>admin2</b> | Pass: <b>pass456</b>
        </div>
    </div>
</body>
</html>