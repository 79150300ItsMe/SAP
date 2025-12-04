<?php
session_start();
require 'config/koneksi.php';

// Cek akses
if ($_SESSION['role'] != 'mahasiswa') { header("Location: login.php"); exit; }

// Proses Input Kegiatan
if (isset($_POST['simpan'])) {
    $user_id = $_SESSION['user_id'];
    $kegiatan = $_POST['nama_kegiatan'];
    $mulai = $_POST['tgl_mulai'];
    $selesai = $_POST['tgl_selesai'];
    
    // Upload File Sederhana (Pastikan folder 'uploads' ada)
    $foto = $_FILES['foto']['name'];
    $sertif = $_FILES['sertifikat']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/".$foto);
    move_uploaded_file($_FILES['sertifikat']['tmp_name'], "uploads/".$sertif);

    $query = "INSERT INTO kegiatan (user_id, nama_kegiatan, tgl_mulai, tgl_selesai, foto, sertifikat) 
              VALUES ('$user_id', '$kegiatan', '$mulai', '$selesai', '$foto', '$sertif')";
    mysqli_query($conn, $query);
    echo "<script>alert('Kegiatan Berhasil Disubmit!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <h2>SAP - Mahasiswa</h2>
        <div>
            <span style="margin-right: 15px;">Halo, <?= $_SESSION['nama']; ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h3>Input Kegiatan Baru</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 40px;">
            <label>Nama Kegiatan:</label>
            <input type="text" name="nama_kegiatan" required>
            
            <div style="display: flex; gap: 10px;">
                <div style="flex:1">
                    <label>Tgl Mulai:</label>
                    <input type="date" name="tgl_mulai" required>
                </div>
                <div style="flex:1">
                    <label>Tgl Selesai:</label>
                    <input type="date" name="tgl_selesai" required>
                </div>
            </div>

            <label>Bukti Foto:</label>
            <input type="file" name="foto" required>
            
            <label>File Sertifikat:</label>
            <input type="file" name="sertifikat" required>
            
            <button type="submit" name="simpan">Simpan Kegiatan</button>
        </form>

        <hr>

        <h3>Riwayat Kegiatan Anda</h3>
        <table>
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $id = $_SESSION['user_id'];
                $data = mysqli_query($conn, "SELECT * FROM kegiatan WHERE user_id = '$id' ORDER BY id DESC");
                while($d = mysqli_fetch_assoc($data)){
                ?>
                <tr>
                    <td><?= $d['nama_kegiatan'] ?></td>
                    <td><?= $d['tgl_mulai'] ?></td>
                    <td>
                        <?php 
                        if($d['status'] == 'Pending') echo "<span class='badge badge-pending'>Menunggu</span>";
                        elseif($d['status'] == 'Disetujui') echo "<span class='badge badge-success'>Disetujui</span>";
                        else echo "<span class='badge badge-danger'>Ditolak</span>";
                        ?>
                    </td>
                    <td>
                        <?php if($d['status'] == 'Disetujui'): ?>
                            <a href="cetak_surat.php?id=<?= $d['id'] ?>" target="_blank">Download Surat</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>