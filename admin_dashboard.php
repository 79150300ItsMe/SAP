<?php
session_start();
require 'config/koneksi.php';

// Cek keamanan: Hanya admin yang boleh masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Proses Validasi (Jika tombol diklik)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = ($_GET['aksi'] == 'terima') ? 'Disetujui' : 'Ditolak';
    
    // Update status di database
    mysqli_query($conn, "UPDATE kegiatan SET status='$status' WHERE id='$id'");
    
    // Redirect agar URL bersih kembali
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin SAP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="navbar">
        <h2>Panel Admin - Validasi Kegiatan</h2>
        <div>
            <span style="margin-right: 15px;">Admin: <?= $_SESSION['nama']; ?></span>
            <a href="logout.php" style="background-color: #c0392b;">Logout</a>
        </div>
    </div>

    <div class="container">
        <h3>Daftar Ajuan Kegiatan Mahasiswa</h3>
        <p>Silakan periksa bukti kegiatan sebelum melakukan validasi.</p>
        
        <table>
            <thead>
                <tr>
                    <th width="20%">Mahasiswa</th>
                    <th width="30%">Detail Kegiatan</th>
                    <th width="20%">Bukti Lampiran</th>
                    <th width="15%">Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query Join: Mengambil data kegiatan + nama mahasiswa dari tabel users
                $query = "SELECT k.*, u.nama, u.nim 
                          FROM kegiatan k 
                          JOIN users u ON k.user_id = u.id 
                          ORDER BY k.id DESC"; 
                
                $data = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($data) == 0){
                    echo "<tr><td colspan='5' style='text-align:center; padding: 20px;'>Belum ada ajuan kegiatan.</td></tr>";
                }

                while($d = mysqli_fetch_assoc($data)){
                ?>
                <tr>
                    <td>
                        <strong><?= $d['nama'] ?></strong><br>
                        <small style="color: #666;">NIM: <?= $d['nim'] ?></small>
                    </td>
                    <td>
                        <strong><?= $d['nama_kegiatan'] ?></strong><br>
                        <small style="color: #666;">Pelaksanaan: <?= date('d/m/Y', strtotime($d['tgl_mulai'])) ?></small>
                    </td>
                    <td>
                        <a href="uploads/<?= $d['foto'] ?>" target="_blank" class="link-bukti">
                            📂 Lihat Foto
                        </a><br>
                        <a href="uploads/<?= $d['sertifikat'] ?>" target="_blank" class="link-bukti">
                            📄 Lihat Sertifikat
                        </a>
                    </td>
                    <td>
                        <?php 
                        // Class badge sudah ada di style.css sebelumnya
                        if($d['status'] == 'Pending') 
                            echo "<span class='badge badge-pending'>Menunggu</span>";
                        elseif($d['status'] == 'Disetujui') 
                            echo "<span class='badge badge-success'>Valid / Sah</span>";
                        else 
                            echo "<span class='badge badge-danger'>Ditolak</span>";
                        ?>
                    </td>
                    <td>
                        <?php if($d['status'] == 'Pending'): ?>
                            <div class="action-buttons">
                                <a href="?aksi=terima&id=<?= $d['id'] ?>" 
                                   class="btn-action btn-approve" 
                                   onclick="return confirm('Yakin ingin MENYETUJUI kegiatan ini?')">
                                   ✓ Terima
                                </a>
                                
                                <a href="?aksi=tolak&id=<?= $d['id'] ?>" 
                                   class="btn-action btn-reject" 
                                   onclick="return confirm('Yakin ingin MENOLAK kegiatan ini?')">
                                   ✗ Tolak
                                </a>
                            </div>
                        <?php else: ?>
                            <small style="color: grey; font-style: italic;">Selesai</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>