<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/database.php';

try {
    $db = Database::getInstance();
    
    echo "<h2>Proses Seeding Database Sistem Presensi...</h2>";

    // 1. Program Studi
    $db->exec("INSERT IGNORE INTO program_studi (id, kode, nama, jenjang, fakultas) VALUES 
        (1, 'TI', 'Teknik Informatika', 'S1', 'Fakultas Ilmu Komputer'),
        (2, 'SI', 'Sistem Informasi', 'S1', 'Fakultas Ilmu Komputer'),
        (3, 'TE', 'Teknik Elektro', 'S1', 'Fakultas Teknik')
    ");
    echo "✅ Program Studi berhasil ditambahkan.<br>";

    // 2. Tahun Akademik & Semester
    $db->exec("INSERT IGNORE INTO tahun_akademik (id, nama, tahun_mulai, tahun_selesai, status) VALUES 
        (1, '2023/2024', 2023, 2024, 'nonaktif'),
        (2, '2024/2025', 2024, 2025, 'aktif')
    ");
    
    $db->exec("INSERT IGNORE INTO semester (id, tahun_akademik_id, nama_semester, is_aktif) VALUES 
        (1, 1, 'Ganjil', 0),
        (2, 1, 'Genap', 0),
        (3, 2, 'Ganjil', 1)
    ");
    echo "✅ Tahun Akademik & Semester berhasil ditambahkan.<br>";

    // 3. Ruangan
    $db->exec("INSERT IGNORE INTO ruangan (id, kode, nama, kapasitas, gedung) VALUES 
        (1, 'R101', 'Ruang Teori 101', 40, 'Gedung A'),
        (2, 'R102', 'Ruang Teori 102', 40, 'Gedung A'),
        (3, 'LAB01', 'Laboratorium Komputer 1', 30, 'Gedung B')
    ");
    echo "✅ Ruangan berhasil ditambahkan.<br>";

    // 4. Users & Dosen
    $hashDosen1 = hash('sha256', 'dosen123');
    $hashDosen2 = hash('sha256', 'dosen123');
    
    // Pastikan roles ada
    $db->exec("INSERT IGNORE INTO roles (id, nama_role) VALUES (1, 'admin'), (2, 'dosen'), (3, 'mahasiswa')");

    $db->exec("INSERT IGNORE INTO users (id, role_id, username, password, is_active) VALUES 
        (101, 2, 'dosen1', '$hashDosen1', 1),
        (102, 2, 'dosen2', '$hashDosen2', 1)
    ");

    $db->exec("INSERT IGNORE INTO dosen (id, user_id, prodi_id, nidn, nama, jenis_kelamin) VALUES 
        (1, 101, 1, '1111111111', 'Budi Santoso, M.Kom', 'L'),
        (2, 102, 2, '2222222222', 'Siti Aminah, M.T', 'P')
    ");
    echo "✅ Dosen berhasil ditambahkan.<br>";

    // 5. Users & Mahasiswa
    $hashMhs = hash('sha256', 'mhs123');
    $db->exec("INSERT IGNORE INTO users (id, role_id, username, password, is_active) VALUES 
        (201, 3, 'mhs1', '$hashMhs', 1),
        (202, 3, 'mhs2', '$hashMhs', 1),
        (203, 3, 'mhs3', '$hashMhs', 1)
    ");

    $db->exec("INSERT IGNORE INTO mahasiswa (id, user_id, prodi_id, nim, nama, jenis_kelamin, angkatan) VALUES 
        (1, 201, 1, '230001', 'Andi Pratama', 'L', 2023),
        (2, 202, 1, '230002', 'Rini Melati', 'P', 2023),
        (3, 203, 2, '230003', 'Dimas Anggara', 'L', 2023)
    ");
    echo "✅ Mahasiswa berhasil ditambahkan.<br>";

    // 6. Mata Kuliah
    $db->exec("INSERT IGNORE INTO mata_kuliah (id, prodi_id, kode, nama, sks, jenis) VALUES 
        (1, 1, 'MK101', 'Algoritma dan Pemrograman', 3, 'Teori & Praktikum'),
        (2, 1, 'MK102', 'Struktur Data', 3, 'Teori'),
        (3, 2, 'MK201', 'Sistem Basis Data', 3, 'Teori')
    ");
    echo "✅ Mata Kuliah berhasil ditambahkan.<br>";

    // 7. Kelas
    $db->exec("INSERT IGNORE INTO kelas (id, mata_kuliah_id, dosen_id, semester_id, nama, kapasitas) VALUES 
        (1, 1, 1, 3, 'TI-A', 40),
        (2, 3, 2, 3, 'SI-A', 40)
    ");
    echo "✅ Kelas berhasil ditambahkan.<br>";

    // 8. Kelas Anggota (Mahasiswa yang mengambil kelas)
    $db->exec("INSERT IGNORE INTO kelas_anggota (id, kelas_id, mahasiswa_id) VALUES 
        (1, 1, 1),
        (2, 1, 2),
        (3, 2, 3)
    ");
    echo "✅ Kelas Anggota (KRS Mahasiswa) berhasil ditambahkan.<br>";

    // 9. Jadwal
    $db->exec("INSERT IGNORE INTO jadwal (id, kelas_id, ruangan_id, hari, jam_mulai, jam_selesai, toleransi_menit) VALUES 
        (1, 1, 3, 'Senin', '08:00:00', '10:30:00', 15),
        (2, 2, 1, 'Selasa', '13:00:00', '15:30:00', 15)
    ");
    echo "✅ Jadwal berhasil ditambahkan.<br>";

    echo "<h3>🎉 Proses Seeding Selesai!</h3>";
    echo "<p>Anda sekarang memiliki data dummy untuk dicoba.</p>";
    echo "<p><a href='index.php'>Kembali ke Aplikasi</a></p>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
