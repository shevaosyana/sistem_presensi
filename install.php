<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sistem_presensi';

try {
    // 1. Koneksi tanpa memilih database untuk membuat database-nya dulu
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // 2. Buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' berhasil dibuat atau sudah ada.<br>";
    
    // 3. Gunakan database tersebut
    $pdo->exec("USE `$dbname`");
    
    // 4. Query pembuatan tabel
    $sql = "
    CREATE TABLE IF NOT EXISTS `roles` (
      `id` int NOT NULL AUTO_INCREMENT,
      `nama_role` varchar(50) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users` (
      `id` int NOT NULL AUTO_INCREMENT,
      `role_id` int NOT NULL,
      `username` varchar(100) NOT NULL,
      `password` varchar(255) NOT NULL,
      `is_active` tinyint(1) DEFAULT 1,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`),
      FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `program_studi` (
      `id` int NOT NULL AUTO_INCREMENT,
      `kode` varchar(10) NOT NULL,
      `nama` varchar(100) NOT NULL,
      `jenjang` varchar(10) NOT NULL,
      `fakultas` varchar(100) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `kode` (`kode`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `mahasiswa` (
      `id` int NOT NULL AUTO_INCREMENT,
      `user_id` int NOT NULL,
      `prodi_id` int NOT NULL,
      `nim` varchar(20) NOT NULL,
      `nama` varchar(100) NOT NULL,
      `jenis_kelamin` enum('L','P') NOT NULL,
      `angkatan` year NOT NULL,
      `foto` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `nim` (`nim`),
      UNIQUE KEY `user_id` (`user_id`),
      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `dosen` (
      `id` int NOT NULL AUTO_INCREMENT,
      `user_id` int NOT NULL,
      `prodi_id` int NOT NULL,
      `nidn` varchar(20) NOT NULL,
      `nama` varchar(100) NOT NULL,
      `jenis_kelamin` enum('L','P') NOT NULL,
      `foto` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `nidn` (`nidn`),
      UNIQUE KEY `user_id` (`user_id`),
      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `mata_kuliah` (
      `id` int NOT NULL AUTO_INCREMENT,
      `prodi_id` int NOT NULL,
      `kode` varchar(15) NOT NULL,
      `nama` varchar(100) NOT NULL,
      `sks` int NOT NULL,
      `jenis` enum('Teori','Praktikum','Teori & Praktikum') NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `kode` (`kode`),
      FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `tahun_akademik` (
      `id` int NOT NULL AUTO_INCREMENT,
      `nama` varchar(20) NOT NULL,
      `tahun_mulai` year NOT NULL,
      `tahun_selesai` year NOT NULL,
      `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
      PRIMARY KEY (`id`),
      UNIQUE KEY `nama` (`nama`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `semester` (
      `id` int NOT NULL AUTO_INCREMENT,
      `tahun_akademik_id` int NOT NULL,
      `nama_semester` enum('Ganjil','Genap','Pendek') NOT NULL,
      `is_aktif` tinyint(1) DEFAULT 0,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `ruangan` (
      `id` int NOT NULL AUTO_INCREMENT,
      `kode` varchar(20) NOT NULL,
      `nama` varchar(100) NOT NULL,
      `kapasitas` int NOT NULL,
      `gedung` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `kode` (`kode`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `kelas` (
      `id` int NOT NULL AUTO_INCREMENT,
      `mata_kuliah_id` int NOT NULL,
      `dosen_id` int NOT NULL,
      `semester_id` int NOT NULL,
      `nama` varchar(50) NOT NULL,
      `kapasitas` int NOT NULL,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE RESTRICT,
      FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE RESTRICT,
      FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `kelas_anggota` (
      `id` int NOT NULL AUTO_INCREMENT,
      `kelas_id` int NOT NULL,
      `mahasiswa_id` int NOT NULL,
      `waktu_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `kelas_mhs` (`kelas_id`,`mahasiswa_id`),
      FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
      FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `jadwal` (
      `id` int NOT NULL AUTO_INCREMENT,
      `kelas_id` int NOT NULL,
      `ruangan_id` int NOT NULL,
      `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
      `jam_mulai` time NOT NULL,
      `jam_selesai` time NOT NULL,
      `toleransi_menit` int NOT NULL DEFAULT 15,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
      FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `presensi` (
      `id` int NOT NULL AUTO_INCREMENT,
      `jadwal_id` int NOT NULL,
      `tanggal` date NOT NULL,
      `jam_buka` time NOT NULL,
      `jam_tutup` time DEFAULT NULL,
      `status` enum('AKTIF','SELESAI') NOT NULL DEFAULT 'AKTIF',
      `pertemuan_ke` int NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `jadwal_tgl` (`jadwal_id`,`tanggal`),
      FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `detail_presensi` (
      `id` int NOT NULL AUTO_INCREMENT,
      `presensi_id` int NOT NULL,
      `mahasiswa_id` int NOT NULL,
      `status` enum('HADIR','TERLAMBAT','IZIN','SAKIT','ALPHA') NOT NULL,
      `jam_presensi` time DEFAULT NULL,
      `keterangan` text,
      `hash_integrity` varchar(64) DEFAULT NULL COMMENT 'SHA-256 integrity hash',
      PRIMARY KEY (`id`),
      UNIQUE KEY `presensi_mhs` (`presensi_id`,`mahasiswa_id`),
      KEY `idx_hash_integrity` (`hash_integrity`),
      FOREIGN KEY (`presensi_id`) REFERENCES `presensi` (`id`) ON DELETE CASCADE,
      FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    
    $pdo->exec($sql);
    echo "Tabel-tabel berhasil dibuat.<br>";
    
    // 5. Insert data awal (Roles & Admin User)
    // Insert Roles
    $stmt = $pdo->query("SELECT COUNT(*) FROM roles");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO roles (nama_role) VALUES ('admin'), ('dosen'), ('mahasiswa')");
        echo "Data default 'roles' berhasil di-insert.<br>";
    }
    
    // Insert Admin User
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username='admin'");
    if ($stmt->fetchColumn() == 0) {
        $hash = hash('sha256', 'admin123');
        $pdo->exec("INSERT INTO users (role_id, username, password, is_active) VALUES (1, 'admin', '$hash', 1)");
        echo "User admin berhasil dibuat. Username: <b>admin</b>, Password: <b>admin123</b>.<br>";
    }
    
    echo "<br><b style='color:green'>Instalasi Database Selesai! Silakan hapus file ini dan reload halaman Anda.</b>";
    echo "<br><a href='index.php'>Kembali ke Halaman Login</a>";

} catch (PDOException $e) {
    die("<b style='color:red'>Error:</b> " . $e->getMessage());
}
