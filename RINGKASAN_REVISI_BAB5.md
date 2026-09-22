# Ringkasan Perkembangan Dokumen Laporan Skripsi

Dokumen ini berisi rangkuman seluruh modifikasi dan penyelarasan yang telah dilakukan pada berkas [LAPORAN_ANALISIS_PERANCANGAN_HASIL.md](file:///d:/laragon/www/sistem_presensi/LAPORAN_ANALISIS_PERANCANGAN_HASIL.md) agar sesuai dengan draf akhir dokumen Word Anda.

---

## 1. Sub-bab 5.1.1 Listing Program (Selesai)
* **Penjelasan & Formulasi Algoritma:**
  * Formula perhitungan selisih menit keterlambatan ($\Delta t = \frac{T_{\text{submit}} - T_{\text{mulai}}}{60}$).
  * Formula Kriptografi Integritas data menggunakan hash SHA-256.
* **Tabel Simulasi & Audit Keamanan:**
  * **Tabel 5.1:** Berisi skenario simulasi Tepat Waktu, Terlambat, Auto-Alpha, dan deteksi manipulasi langsung di database (status `Valid` vs `Manipulasi Terdeteksi`).
  * Dilengkapi keterangan miring murni akademik: `*(Sumber: Penulis, 2026)*` di bagian bawah tabel.
* **8 Modul Utama PHP (a s.d. h):**
  1. `AuthController.php` (Autentikasi & Sesi)
  2. `PresensiController.php` (Pengisian Hadir)
  3. `PresensiController.php` (Tutup Sesi & Auto-Alpha)
  4. `AuditController.php` (Deteksi Manipulasi Hash)
  5. `FormatHelper.php` (Kalkulator SHA-256)
  6. `config/database.php` (Koneksi PDO Singleton)
  7. `index.php` (Front Controller / Routing)
  8. `LaporanController.php` (Rekapitulasi & Cetak PDF)

---

## 2. UML Sequence Diagram (Selesai)
Telah dirapikan menjadi 4 Diagram Urutan di **Bab IV** dengan format khusus **Teks Atas (Pengantar)** $\rightarrow$ **Gambar (assets/images/...)** $\rightarrow$ **Caption (Gambar 4.xx)** $\rightarrow$ **Teks Bawah (Alur Detail)** tanpa kode Mermaid:
* **F.1. Sequence Diagram: Proses Login Multi-Role** (Teks bawah saja, tanpa teks atas).
* **F.2. Sequence Diagram: Proses Presensi Mandiri Mahasiswa** (Memiliki teks atas dan teks bawah).
* **F.3. Sequence Diagram: Membuka Sesi Presensi (Dosen)** (Memiliki teks atas dan teks bawah).
* **F.4. Sequence Diagram: Proses Tutup Sesi & Auto-Alpha (Dosen)** (Memiliki teks atas dan teks bawah).

---

## 3. Sub-bab 4.2.5 Desain Antarmuka (Bab IV - Selesai)
Tampilan antarmuka (*User Interface*) dan Wireframe telah dipindahkan secara utuh ke **BAB IV Sub-bab 4.2.5 Desain Antarmuka**:
* **a. Rancangan Antarmuka / Wireframe:** Berisi gambaran umum wireframe tata letak halaman aplikasi.
* **b. User Interface / Antarmuka:** Berisi rincian tampilan 16 halaman utama sistem presensi (Login, Dashboard Admin, Presensi Mandiri Mahasiswa, Kelola Presensi & *Auto-Alpha*, Audit Integrity SHA-256, Dashboard Mahasiswa, Rekap Presensi Dosen, Rekap Kehadiran Mahasiswa, hingga Data Master, Anggota Kelas, Jadwal, Semester, Hak Akses, Cetak PDF, dan Profil).

---

## 4. Sub-bab 5.1.2 Implementasi Sistem (Bab V - Selesai)
Telah diperluas secara komprehensif mencakup 5 aspek teknis utama implementasi sistem:
1. **Lingkungan Server & Arsitektur Aplikasi:** Platform Windows 10/11, Web Server Laragon/XAMPP, Apache HTTP Server Gateway, PHP 8.1+ MVC, dan Front Controller `index.php`.
2. **Pengelolaan Koneksi Basis Data (PDO Singleton Pattern):** Abstraksi PDO Singleton & Prepared Statements untuk pencegahan SQL Injection.
3. **Logika Perhitungan Toleransi & Kriptografi SHA-256:** Kalkulasi keterlambatan real-time berbasis server clock & pembuatan integrity hash SHA-256 (64 karakter).
4. **Otomasi Status Auto-Alpha & Transaksi Database:** Algoritma *set difference* dengan transaksi atomik PDO (`beginTransaction`, `commit`, `rollBack`).
5. **Manajemen Otentikasi & Keamanan Sesi Multi-Role:** Pembatasan wewenang 3 peran (Admin, Dosen, Mhs) & proteksi Session Fixation via `session_regenerate_id(true)`.

---

## 5. Sub-bab 5.1.3 Spesifikasi Sistem (Bab V - Selesai)
Telah dilengkapi dengan narasi akademik serta tabel komparasi teknis:
* **Tabel 5.2 Spesifikasi Perangkat Keras Minimal (Hardware):** Penjelasan kebutuhanProsessor, RAM 8GB ECC Server vs 4GB Client, SSD 80GB, NIC 1Gbps, dan Display.
* **Tabel 5.3 Spesifikasi Perangkat Lunak Minimal (Software):** Penjelasan OS Ubuntu/Windows, Web Server Apache v2.4, Interpreter PHP 8.1+, MySQL 8.0, dan Enkripsi TLS 1.3 / OpenSSL.
* Dilengkapi analisis teknis penanganan beban puncak (*peak hours*) dan standar *Responsive Web Design*.

---

## 6. Sub-bab 5.1.4 Instalasi Sistem (Bab V - Selesai)
Telah disesuaikan mengikuti pola 4 struktur instalasi (A, B, C, D):
1. **A. Instalasi Konfigurasi Basis Data:** Aktivasi Apache & MySQL Laragon, pembuatan database `sistem_presensi`, impor `sistem_presensi.sql` / `install.php`, dan konfigurasi PDO Singleton (`config/database.php`).
2. **B. Instalasi Aplikasi Web Presensi Perkuliahan (PHP Native MVC):** Penempatan repositori di *web root*, penyesuaian `BASE_URL` (`config/config.php`), aktivasi ekstensi `pdo_mysql`, `openssl`, `mbstring`, dan izin direktori `uploads/`.
3. **C. Instalasi Dashboard Monitoring & Antarmuka Multi-Role:** Struktur `views/` & `index.php`, pengarahan routing Apache, akses URL auth `?page=auth`, serta komunikasi dashboard Admin, Dosen, & Mahasiswa.
---

## 7. Sub-bab 5.1.5 Menjalankan Sistem (Bab V - Selesai)
Telah diperluas mencakup 4 langkah operasional terstruktur:
1. **Mengaktifkan Layanan Web Server dan DBMS:** Panduan pengaktifan Apache (port 80/443) & MySQL (port 3306) via Laragon/XAMPP.
2. **Mengakses Aplikasi via Browser:** Pengarahan URL lokal `http://localhost/sistem_presensi` & mekanisme auto-redirection halaman login.
3. **Pengoperasian Akun Hak Akses Multi-Role:** Detail kredensial uji coba & hak wewenang untuk 3 role (`admin`, `dosen`, `mahasiswa`).
4. **Alur Operasional Sesi Presensi Real-Time:** Penjelasan siklus Buka Sesi Dosen $\rightarrow$ Presensi Mandiri Mhs (SHA-256) $\rightarrow$ Penutupan Sesi Auto-Alpha $\rightarrow$ Audit Keamanan Data.

---
## 5. Sub-bab 5.2 Pengujian Sistem (Bab V - Selesai)
* **5.2.1 Pengujian Fungsionalitas Sistem (Black-Box Testing):** Berisi 10 skenario pengujian fungsionalitas dengan status VALID (100% Sesuai Spesifikasi).
* **5.2.2 Pengujian Keamanan Integritas Data (Audit Hash SHA-256):** Berisi 3 skenario simulasi audit keutuhan data (Normal AMAN, Manipulasi Status TERDETEKSI, Manipulasi Tanggal TERDETEKSI).
* **5.2.3 Pengujian UAT (User Acceptance Testing):** Dilengkapi dengan **Tabel 5.6 (Skenario Pengujian UAT)** untuk 8 skenario simulasi operasional oleh Admin, Dosen, dan Mahasiswa, serta **Tabel 5.7 (Hasil Kuesioner UAT)** dengan tingkat kepuasan rata-rata sebesar **91,13%** (kategori Sangat Layak/Diterima).

---

## 6. BAB VI: Penutup - Kesimpulan & Saran (Selesai)
Telah ditambahkan **Bab VI** pada dokumen laporan utama:
* **6.1 Kesimpulan:** Meliputi 4 poin utama mengenai integritas data kriptografis SHA-256, otomasi *Auto-Alpha* & kalkulasi keterlambatan, efisiensi pengelolaan *multi-role*, hasil pengujian UAT (91,13% Sangat Layak), serta validasi *Black-Box Testing* (100% Valid).
* **6.2 Saran:** Meliputi 4 rekomendasi pengembangan masa depan (Mobile Native/PWA, Geofencing GPS & Dynamic QR Code, WhatsApp Gateway Alert, dan Scheduled Cloud Backup).

---

*(Catatan: Percakapan ini juga terekam secara otomatis oleh sistem IDE Antigravity pada log direktori workspace lokal Anda.)*




