<?php
session_start();
require 'config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID kegiatan dari URL
$id_kegiatan = $_GET['id'];

// Ambil data kegiatan gabung dengan data mahasiswa
$query = "SELECT k.*, u.nama, u.nim, u.prodi 
          FROM kegiatan k 
          JOIN users u ON k.user_id = u.id 
          WHERE k.id = '$id_kegiatan'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Cek keamanan
if (!$data || $data['status'] != 'Disetujui') {
    die("Surat tidak valid atau belum disetujui admin.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Validasi</title>
    <style>
        /* CSS STANDAR */
        body { 
            font-family: 'Times New Roman', Times, serif; 
            padding: 40px; 
            color: #000;
        }
        .header { text-align: center; border-bottom: 3px double black; padding-bottom: 10px; margin-bottom: 20px; }
        h3, h4 { margin: 0; }
        .content { margin-top: 30px; line-height: 1.6; }
        .table-data { width: 100%; margin-top: 20px; }
        .table-data td { padding: 5px; vertical-align: top; }
        .ttd { float: right; margin-top: 50px; text-align: center; }

        /* --- CSS KHUSUS PRINT (SOLUSI BUG ANDA) --- */
        @media print {
            @page {
                margin: 0; /* INI KUNCINYA: Menghilangkan Header/Footer Browser (Tanggal, URL, dll) */
                size: auto;
            }
            body {
                margin: 2.5cm; /* Memberi jarak pinggir kertas secara manual */
            }
            /* Menyembunyikan elemen yang tidak perlu dicetak jika ada */
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()"> 

    <div class="header">
        <h3>UNIVERSITAS LANCANG KUNING</h3>
        <h4>FAKULTAS ILMU KOMPUTER</h4>
        <small>Jl. Yos Sudarso Km 8 Rumbai Pekanbaru - Riau</small>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h3><u>SURAT KETERANGAN VALIDASI POIN AKTIVITAS</u></h3>
    </div>

    <div class="content">
        <p>Berdasarkan data aktivitas mahasiswa yang terekam dalam sistem Student Activity Point (SAP), dengan ini menerangkan bahwa:</p>
        
        <table class="table-data">
            <tr>
                <td width="150">Nama</td>
                <td>: <strong><?= $data['nama'] ?></strong></td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>: <?= $data['nim'] ?></td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: <?= $data['prodi'] ?></td>
            </tr>
        </table>

        <p>Telah melaksanakan kegiatan akademik/non-akademik dengan rincian sebagai berikut:</p>

        <table class="table-data" style="border: 1px solid black; border-collapse: collapse;">
            <tr>
                <td style="border: 1px solid black;">Nama Kegiatan</td>
                <td style="border: 1px solid black;">: <?= $data['nama_kegiatan'] ?></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Tanggal Pelaksanaan</td>
                <td style="border: 1px solid black;">: <?= date('d F Y', strtotime($data['tgl_mulai'])) ?> s/d <?= date('d F Y', strtotime($data['tgl_selesai'])) ?></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Status Validasi</td>
                <td style="border: 1px solid black;">: <strong>DISETUJUI / VALID</strong></td>
            </tr>
        </table>

        <p>Demikian surat keterangan ini diterbitkan secara otomatis oleh sistem untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd">
        <p>Pekanbaru, <?= date('d F Y') ?></p>
        <p>Mengetahui,<br>Admin SAP</p>
        <br><br><br>
        <p><strong>( Administrator )</strong></p>
    </div>

</body>
</html>