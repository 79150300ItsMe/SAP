<?php
session_start();
require 'config/koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: mahasiswa_dashboard.php");
        }
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head> 
    <title>Login SAP</title> 
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-box">
        <img src="assets/img/Unilak.png" width="80" style="margin-bottom:10px;">
        <h2>Login SAP</h2>
        <p>Sistem Activity Point Mahasiswa</p>
        
        <?php if(isset($error)) echo "<div class='alert'>$error</div>"; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <p style="margin-top:15px;">Belum punya akun? <a href="register.php">Daftar disini</a></p>
    </div>
</body>
</html>