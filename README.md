# Sistem Informasi Presensi Perkuliahan

Aplikasi web berbasis PHP & MySQL untuk manajemen dan pencatatan presensi perkuliahan secara digital dan mandiri, dirancang untuk mendukung tiga peran pengguna: **Admin**, **Dosen**, dan **Mahasiswa**.

---

## 📌 Fitur Utama

### 1. Multi-Role Authentication & Dashboard
* **Administrator**: Manajemen data master, kontrol akses pengguna, pemantauan sistem secara menyeluruh.
* **Dosen**: Buka/tutup sesi presensi mandiri, monitoring kehadiran mahasiswa secara real-time, validasi status kehadiran, dan ekspor rekapitulasi.
* **Mahasiswa**: Melakukan presensi mandiri saat sesi dibuka dosen, cek jadwal kuliah, dan melihat riwayat kehadiran.

### 2. Manajemen Data Master (Admin)
* Manajemen Program Studi & Fakultas
* Manajemen Tahun Akademik & Semester (Ganjil/Genap)
* Manajemen Ruangan & Kapasitas
* Manajemen Mata Kuliah & SKS
* Manajemen Kelas & Penugasan Anggota Kelas
* Manajemen Data Dosen & Mahasiswa
* Manajemen Akun Pengguna & Hak Akses

### 3. Presensi & Jadwal Perkuliahan
* Penjadwalan mata kuliah berdasarkan hari, jam, ruang, kelas, dan dosen pengampu
* Sesi presensi interaktif dengan batas waktu toleransi keterlambatan
* Status presensi: **Hadir**, **Izin**, **Sakit**, dan **Alpa**

### 4. Rekapitulasi & Laporan
* Rekap kehadiran mahasiswa per mata kuliah dan semester
* Laporan kehadiran dosen dalam perkuliahan
* Cetak laporan presensi siap cetak / PDF

---

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman**: PHP (Native dengan Arsitektur MVC)
* **Basis Data**: MySQL / MariaDB
* **Web Server**: Apache (XAMPP / Laragon)
* **Frontend**: HTML5, CSS3, JavaScript, Bootstrap / Modern UI
* **Version Control**: Git & GitHub

---

## 🚀 Panduan Instalasi & Penggunaan

### Prasyarat
* Web server lokal (direkomendasikan **Laragon** atau **XAMPP**) dengan versi PHP minimal **7.4** atau **8.x**.
* MySQL / MariaDB.

### Langkah Instalasi

1. **Clone repositori**:
   ```bash
   git clone https://github.com/shevaosyana/sistem_presensi.git
   ```
   *Letakkan folder ini di direktori web server Anda (misal `C:\laragon\www\sistem_presensi` atau `htdocs`).*

2. **Impor Database**:
   * Buka **phpMyAdmin** atau HeidiSQL.
   * Buat database baru dengan nama `sistem_presensi`.
   * Impor file SQL yang ada di folder:
     ```text
     database/sistem_presensi.sql
     ```

3. **Konfigurasi Database**:
   Sesuaikan pengaturan koneksi database pada file `config/database.php` jika username/password database berbeda:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'sistem_presensi');
   ```

4. **Jalankan Aplikasi**:
   * Buka browser dan akses:
     ```text
     http://localhost/sistem_presensi
     ```
     *(atau `http://sistem_presensi.test` jika menggunakan Laragon)*.

---

## 👤 Akun Default (Demo)

| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `admin` | `admin123` *(atau sesuaikan dengan seed.php)* |

---

## 📂 Struktur Direktori

```text
sistem_presensi/
├── assets/          # CSS, JS, gambar, diagram alur/activity
├── config/          # Konfigurasi aplikasi & database
├── controllers/     # Controller logika bisnis (MVC)
├── database/        # Skrip database SQL
├── helpers/         # Fungsi pembantu (format, session, validasi)
├── models/          # Model query database (MVC)
├── views/           # Tampilan antarmuka pengguna (MVC)
├── index.php        # Entry point utama aplikasi
└── README.md        # Dokumentasi proyek
```

---

## 📄 Lisensi
Proyek ini dibuat untuk keperluan akademik dan pengembangan sistem presensi perkuliahan.
