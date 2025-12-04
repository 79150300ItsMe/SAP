<?php require 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrasi SAP</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-box">
        <h2>Registrasi Mahasiswa</h2>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="nim" placeholder="NIM" required>
            <input type="text" name="prodi" placeholder="Program Studi" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Daftar Sekarang</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>

    <?php
    if (isset($_POST['register'])) {
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $prodi = $_POST['prodi'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        // Menggunakan password hash untuk keamanan standar developer
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        $role = 'mahasiswa';

        $query = "INSERT INTO users (nama, nim, prodi, email, username, password, role) VALUES ('$nama', '$nim', '$prodi', '$email', '$username', '$password', '$role')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    ?>
</body>
</html>