-- ============================================================
-- SISTEM INFORMASI PRESENSI PERKULIAHAN
-- SQL Database Lengkap
-- Versi: 1.0
-- Engine: MySQL 8.x
-- Charset: utf8mb4
-- ============================================================

-- ─────────────────────────────────────────
-- BUAT DAN PILIH DATABASE
-- ─────────────────────────────────────────
CREATE DATABASE IF NOT EXISTS `sistem_presensi`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `sistem_presensi`;

-- ─────────────────────────────────────────
-- NONAKTIFKAN FK CHECK SEMENTARA
-- ─────────────────────────────────────────
SET FOREIGN_KEY_CHECKS = 0;

-- ─────────────────────────────────────────
-- DROP TABEL JIKA SUDAH ADA (untuk re-install)
-- ─────────────────────────────────────────
DROP TABLE IF EXISTS `detail_presensi`;
DROP TABLE IF EXISTS `presensi`;
DROP TABLE IF EXISTS `jadwal`;
DROP TABLE IF EXISTS `kelas_anggota`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `ruangan`;
DROP TABLE IF EXISTS `mata_kuliah`;
DROP TABLE IF EXISTS `semester`;
DROP TABLE IF EXISTS `tahun_akademik`;
DROP TABLE IF EXISTS `dosen`;
DROP TABLE IF EXISTS `mahasiswa`;
DROP TABLE IF EXISTS `program_studi`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;

-- ============================================================
-- TABEL 1: roles
-- Menyimpan peran pengguna sistem
-- ============================================================
CREATE TABLE `roles` (
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `nama_role`  VARCHAR(20)  NOT NULL,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_roles_nama` (`nama_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Daftar peran pengguna sistem';

-- ============================================================
-- TABEL 2: users
-- Menyimpan akun login semua pengguna
-- ============================================================
CREATE TABLE `users` (
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `role_id`    INT          NOT NULL,
    `username`   VARCHAR(50)  NOT NULL,
    `password`   VARCHAR(255) NOT NULL COMMENT 'Hashed dengan password_hash()',
    `is_active`  TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1=aktif, 0=nonaktif',
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_username` (`username`),
    KEY `idx_users_role` (`role_id`),
    CONSTRAINT `fk_users_role`
        FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Akun login pengguna sistem';

-- ============================================================
-- TABEL 3: program_studi
-- Menyimpan data program studi di fakultas
-- ============================================================
CREATE TABLE `program_studi` (
    `id`       INT          NOT NULL AUTO_INCREMENT,
    `kode`     VARCHAR(10)  NOT NULL COMMENT 'Kode singkat prodi, misal: IF, SI, TK',
    `nama`     VARCHAR(100) NOT NULL COMMENT 'Nama lengkap program studi',
    `jenjang`  ENUM('D3','S1','S2','S3') NOT NULL DEFAULT 'S1',
    `fakultas` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_prodi_kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data program studi dalam satu fakultas';

-- ============================================================
-- TABEL 4: tahun_akademik
-- Menyimpan data tahun akademik
-- ============================================================
CREATE TABLE `tahun_akademik` (
    `id`            INT         NOT NULL AUTO_INCREMENT,
    `nama`          VARCHAR(20) NOT NULL COMMENT 'Contoh: 2024/2025',
    `tahun_mulai`   YEAR        NOT NULL,
    `tahun_selesai` YEAR        NOT NULL,
    `status`        ENUM('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ta_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data tahun akademik';

-- ============================================================
-- TABEL 5: semester
-- Menyimpan data semester per tahun akademik
-- ============================================================
CREATE TABLE `semester` (
    `id`                INT      NOT NULL AUTO_INCREMENT,
    `tahun_akademik_id` INT      NOT NULL,
    `nama_semester`     ENUM('Ganjil','Genap') NOT NULL,
    `is_aktif`          TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=sedang berjalan',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_semester_ta_nama` (`tahun_akademik_id`, `nama_semester`),
    KEY `idx_semester_ta` (`tahun_akademik_id`),
    CONSTRAINT `fk_semester_ta`
        FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data semester dalam tahun akademik';

-- ============================================================
-- TABEL 6: mahasiswa
-- Menyimpan data profil mahasiswa
-- ============================================================
CREATE TABLE `mahasiswa` (
    `id`            INT         NOT NULL AUTO_INCREMENT,
    `user_id`       INT         NOT NULL,
    `prodi_id`      INT         NOT NULL,
    `nim`           VARCHAR(20) NOT NULL COMMENT 'Nomor Induk Mahasiswa',
    `nama`          VARCHAR(100) NOT NULL,
    `jenis_kelamin` ENUM('L','P') NOT NULL,
    `angkatan`      YEAR        NOT NULL,
    `foto`          VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path file foto profil',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_mahasiswa_nim` (`nim`),
    UNIQUE KEY `uq_mahasiswa_user` (`user_id`),
    KEY `idx_mahasiswa_prodi` (`prodi_id`),
    KEY `idx_mahasiswa_angkatan` (`angkatan`),
    CONSTRAINT `fk_mahasiswa_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_mahasiswa_prodi`
        FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data profil mahasiswa';

-- ============================================================
-- TABEL 7: dosen
-- Menyimpan data profil dosen
-- ============================================================
CREATE TABLE `dosen` (
    `id`            INT         NOT NULL AUTO_INCREMENT,
    `user_id`       INT         NOT NULL,
    `prodi_id`      INT         NOT NULL,
    `nidn`          VARCHAR(20) NOT NULL COMMENT 'Nomor Induk Dosen Nasional',
    `nama`          VARCHAR(100) NOT NULL,
    `jenis_kelamin` ENUM('L','P') NOT NULL,
    `foto`          VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path file foto profil',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_dosen_nidn` (`nidn`),
    UNIQUE KEY `uq_dosen_user` (`user_id`),
    KEY `idx_dosen_prodi` (`prodi_id`),
    CONSTRAINT `fk_dosen_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_dosen_prodi`
        FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data profil dosen';

-- ============================================================
-- TABEL 8: mata_kuliah
-- Menyimpan data mata kuliah per program studi
-- ============================================================
CREATE TABLE `mata_kuliah` (
    `id`       INT         NOT NULL AUTO_INCREMENT,
    `prodi_id` INT         NOT NULL,
    `kode`     VARCHAR(10) NOT NULL COMMENT 'Kode mata kuliah, mis: IF301',
    `nama`     VARCHAR(100) NOT NULL,
    `sks`      TINYINT     NOT NULL DEFAULT 3 COMMENT 'Jumlah SKS (1-6)',
    `jenis`    ENUM('Wajib','Pilihan') NOT NULL DEFAULT 'Wajib',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_mk_kode` (`kode`),
    KEY `idx_mk_prodi` (`prodi_id`),
    CONSTRAINT `fk_mk_prodi`
        FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `chk_mk_sks`
        CHECK (`sks` BETWEEN 1 AND 6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data mata kuliah per program studi';

-- ============================================================
-- TABEL 9: ruangan
-- Menyimpan data ruangan perkuliahan
-- ============================================================
CREATE TABLE `ruangan` (
    `id`        INT         NOT NULL AUTO_INCREMENT,
    `kode`      VARCHAR(10) NOT NULL COMMENT 'Kode ruangan, mis: R101, LAB-A',
    `nama`      VARCHAR(50) NOT NULL,
    `kapasitas` INT         NOT NULL DEFAULT 40,
    `gedung`    VARCHAR(50) NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ruangan_kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data ruangan perkuliahan';

-- ============================================================
-- TABEL 10: kelas
-- Satu kelas = satu MK + satu Dosen + satu Semester
-- ============================================================
CREATE TABLE `kelas` (
    `id`             INT         NOT NULL AUTO_INCREMENT,
    `mata_kuliah_id` INT         NOT NULL,
    `dosen_id`       INT         NOT NULL,
    `semester_id`    INT         NOT NULL,
    `nama`           VARCHAR(10) NOT NULL COMMENT 'Nama kelas: A, B, C, dst',
    `kapasitas`      INT         NOT NULL DEFAULT 40,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kelas_mk_smt_nama` (`mata_kuliah_id`, `semester_id`, `nama`),
    KEY `idx_kelas_mk` (`mata_kuliah_id`),
    KEY `idx_kelas_dosen` (`dosen_id`),
    KEY `idx_kelas_semester` (`semester_id`),
    CONSTRAINT `fk_kelas_mk`
        FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_kelas_dosen`
        FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_kelas_semester`
        FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data kelas perkuliahan';

-- ============================================================
-- TABEL 11: kelas_anggota
-- Relasi many-to-many: kelas dan mahasiswa
-- ============================================================
CREATE TABLE `kelas_anggota` (
    `id`           INT NOT NULL AUTO_INCREMENT,
    `kelas_id`     INT NOT NULL,
    `mahasiswa_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kelas_anggota` (`kelas_id`, `mahasiswa_id`),
    KEY `idx_kls_angg_kelas` (`kelas_id`),
    KEY `idx_kls_angg_mhs` (`mahasiswa_id`),
    CONSTRAINT `fk_kls_angg_kelas`
        FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_kls_angg_mhs`
        FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Daftar mahasiswa dalam setiap kelas';

-- ============================================================
-- TABEL 12: jadwal
-- Jadwal kuliah: hari, jam, ruangan per kelas
-- ============================================================
CREATE TABLE `jadwal` (
    `id`              INT  NOT NULL AUTO_INCREMENT,
    `kelas_id`        INT  NOT NULL,
    `ruangan_id`      INT  NOT NULL,
    `hari`            ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
    `jam_mulai`       TIME NOT NULL,
    `jam_selesai`     TIME NOT NULL,
    `toleransi_menit` INT  NOT NULL DEFAULT 15 COMMENT 'Menit toleransi keterlambatan',
    PRIMARY KEY (`id`),
    KEY `idx_jadwal_kelas` (`kelas_id`),
    KEY `idx_jadwal_ruangan` (`ruangan_id`),
    KEY `idx_jadwal_hari` (`hari`),
    CONSTRAINT `fk_jadwal_kelas`
        FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_jadwal_ruangan`
        FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `chk_jadwal_jam`
        CHECK (`jam_selesai` > `jam_mulai`),
    CONSTRAINT `chk_jadwal_toleransi`
        CHECK (`toleransi_menit` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Jadwal kuliah per kelas';

-- ============================================================
-- TABEL 13: presensi
-- Sesi presensi yang dibuka dosen per pertemuan
-- ============================================================
CREATE TABLE `presensi` (
    `id`           INT  NOT NULL AUTO_INCREMENT,
    `jadwal_id`    INT  NOT NULL,
    `tanggal`      DATE NOT NULL,
    `jam_buka`     TIME NULL DEFAULT NULL COMMENT 'Diisi saat dosen buka presensi',
    `jam_tutup`    TIME NULL DEFAULT NULL COMMENT 'Diisi saat presensi ditutup',
    `status`       ENUM('BELUM_DIBUKA','AKTIF','SELESAI') NOT NULL DEFAULT 'BELUM_DIBUKA',
    `pertemuan_ke` INT  NOT NULL DEFAULT 1,
    `keterangan`   TEXT NULL DEFAULT NULL,
    `created_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_presensi_jadwal_tanggal` (`jadwal_id`, `tanggal`),
    KEY `idx_presensi_jadwal` (`jadwal_id`),
    KEY `idx_presensi_tanggal` (`tanggal`),
    KEY `idx_presensi_status` (`status`),
    CONSTRAINT `fk_presensi_jadwal`
        FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sesi presensi per pertemuan kuliah';

-- ============================================================
-- TABEL 14: detail_presensi
-- Status kehadiran setiap mahasiswa per sesi presensi
-- ============================================================
CREATE TABLE `detail_presensi` (
    `id`           INT  NOT NULL AUTO_INCREMENT,
    `presensi_id`  INT  NOT NULL,
    `mahasiswa_id` INT  NOT NULL,
    `status`       ENUM('HADIR','IZIN','SAKIT','TERLAMBAT','ALPHA') NOT NULL,
    `jam_presensi` TIME NULL DEFAULT NULL COMMENT 'Jam mahasiswa melakukan presensi',
    `keterangan`   TEXT NULL DEFAULT NULL COMMENT 'Keterangan izin/sakit',
    `created_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_detail_presensi_mhs` (`presensi_id`, `mahasiswa_id`),
    KEY `idx_dp_presensi` (`presensi_id`),
    KEY `idx_dp_mahasiswa` (`mahasiswa_id`),
    KEY `idx_dp_status` (`status`),
    CONSTRAINT `fk_dp_presensi`
        FOREIGN KEY (`presensi_id`) REFERENCES `presensi` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_dp_mahasiswa`
        FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Detail kehadiran mahasiswa per sesi presensi';

-- ─────────────────────────────────────────
-- AKTIFKAN KEMBALI FK CHECK
-- ─────────────────────────────────────────
SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
-- DATA SEED AWAL
-- ============================================================

-- SEED: roles
INSERT INTO `roles` (`id`, `nama_role`) VALUES
(1, 'admin'),
(2, 'dosen'),
(3, 'mahasiswa');

-- SEED: users (admin default)
-- Password: Admin@123
-- Jalankan di PHP: echo password_hash('Admin@123', PASSWORD_DEFAULT);
-- lalu ganti hash di bawah
INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `is_active`) VALUES
(1, 1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- SEED: program_studi
INSERT INTO `program_studi` (`id`, `kode`, `nama`, `jenjang`, `fakultas`) VALUES
(1, 'IF',  'Teknik Informatika',     'S1', 'Fakultas Teknik'),
(2, 'SI',  'Sistem Informasi',       'S1', 'Fakultas Teknik'),
(3, 'TK',  'Teknik Komputer',        'S1', 'Fakultas Teknik'),
(4, 'MI',  'Manajemen Informatika',  'D3', 'Fakultas Teknik');

-- SEED: tahun_akademik
INSERT INTO `tahun_akademik` (`id`, `nama`, `tahun_mulai`, `tahun_selesai`, `status`) VALUES
(1, '2023/2024', 2023, 2024, 'nonaktif'),
(2, '2024/2025', 2024, 2025, 'aktif'),
(3, '2025/2026', 2025, 2026, 'nonaktif');

-- SEED: semester
INSERT INTO `semester` (`id`, `tahun_akademik_id`, `nama_semester`, `is_aktif`) VALUES
(1, 1, 'Ganjil', 0),
(2, 1, 'Genap',  0),
(3, 2, 'Ganjil', 0),
(4, 2, 'Genap',  1),
(5, 3, 'Ganjil', 0);

-- SEED: ruangan
INSERT INTO `ruangan` (`id`, `kode`, `nama`, `kapasitas`, `gedung`) VALUES
(1, 'R101',  'Ruang 101',      40, 'Gedung A'),
(2, 'R102',  'Ruang 102',      40, 'Gedung A'),
(3, 'R201',  'Ruang 201',      35, 'Gedung B'),
(4, 'R202',  'Ruang 202',      35, 'Gedung B'),
(5, 'LAB-A', 'Laboratorium A', 30, 'Gedung C'),
(6, 'LAB-B', 'Laboratorium B', 30, 'Gedung C'),
(7, 'AULA',  'Aula Utama',    100, 'Gedung D');

-- ============================================================
-- VIEW: v_rekap_presensi
-- Rekap kehadiran per mahasiswa per kelas
-- ============================================================
CREATE OR REPLACE VIEW `v_rekap_presensi` AS
SELECT
    dp.mahasiswa_id,
    m.nim,
    m.nama                                          AS nama_mahasiswa,
    mk.kode                                         AS kode_mk,
    mk.nama                                         AS nama_mk,
    k.nama                                          AS nama_kelas,
    s.nama_semester,
    ta.nama                                         AS tahun_akademik,
    COUNT(dp.id)                                    AS total_pertemuan,
    SUM(dp.status = 'HADIR')                        AS jumlah_hadir,
    SUM(dp.status = 'TERLAMBAT')                    AS jumlah_terlambat,
    SUM(dp.status = 'IZIN')                         AS jumlah_izin,
    SUM(dp.status = 'SAKIT')                        AS jumlah_sakit,
    SUM(dp.status = 'ALPHA')                        AS jumlah_alpha,
    ROUND(
        (SUM(dp.status = 'HADIR') + SUM(dp.status = 'TERLAMBAT'))
        / COUNT(dp.id) * 100, 2
    )                                               AS persentase_kehadiran
FROM `detail_presensi` dp
JOIN `mahasiswa`      m   ON dp.mahasiswa_id      = m.id
JOIN `presensi`       p   ON dp.presensi_id       = p.id
JOIN `jadwal`         j   ON p.jadwal_id           = j.id
JOIN `kelas`          k   ON j.kelas_id            = k.id
JOIN `mata_kuliah`    mk  ON k.mata_kuliah_id      = mk.id
JOIN `semester`       s   ON k.semester_id         = s.id
JOIN `tahun_akademik` ta  ON s.tahun_akademik_id   = ta.id
GROUP BY dp.mahasiswa_id, k.id;

-- ============================================================
-- VIEW: v_jadwal_lengkap
-- Jadwal dengan info kelas, dosen, ruangan, semester
-- ============================================================
CREATE OR REPLACE VIEW `v_jadwal_lengkap` AS
SELECT
    j.id               AS jadwal_id,
    j.hari,
    j.jam_mulai,
    j.jam_selesai,
    j.toleransi_menit,
    k.id               AS kelas_id,
    k.nama             AS nama_kelas,
    mk.kode            AS kode_mk,
    mk.nama            AS nama_mk,
    mk.sks,
    d.nidn,
    d.nama             AS nama_dosen,
    r.kode             AS kode_ruangan,
    r.nama             AS nama_ruangan,
    s.nama_semester,
    ta.nama            AS tahun_akademik
FROM `jadwal` j
JOIN `kelas`          k   ON j.kelas_id          = k.id
JOIN `mata_kuliah`    mk  ON k.mata_kuliah_id     = mk.id
JOIN `dosen`          d   ON k.dosen_id           = d.id
JOIN `ruangan`        r   ON j.ruangan_id         = r.id
JOIN `semester`       s   ON k.semester_id        = s.id
JOIN `tahun_akademik` ta  ON s.tahun_akademik_id  = ta.id;

-- ============================================================
-- SELESAI: Total 14 tabel + 2 view berhasil dibuat
-- ============================================================
