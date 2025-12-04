-- Buat Database
CREATE DATABASE IF NOT EXISTS db_sap;
USE db_sap;

-- Tabel Users (Menampung Data Mahasiswa dan Admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    nim VARCHAR(20),
    prodi VARCHAR(50),
    email VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('mahasiswa', 'admin') DEFAULT 'mahasiswa'
);

-- Tabel Kegiatan (Menampung Inputan Mahasiswa)
CREATE TABLE kegiatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    nama_kegiatan VARCHAR(255),
    tgl_mulai DATE,
    tgl_selesai DATE,
    foto VARCHAR(255),
    sertifikat VARCHAR(255),
    status ENUM('Pending', 'Disetujui', 'Ditolak') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert Akun Admin Default (Password: admin123)
INSERT INTO users (nama, username, password, role) 
VALUES ('Administrator', 'admin', '$2y$10$YourHashedPasswordHereOrJustPlainForExam', 'admin');
-- Catatan: Untuk ujian praktek, jika belum paham hash, bisa gunakan plain text dulu. 
-- Di kode PHP bawah saya gunakan password_verify, jadi pastikan registrasi dipakai.