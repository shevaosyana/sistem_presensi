# NASKAH SKRIPSI BAB 4 - RANGKUMAN DARI SESI CHAT AI
# Disimpan: 30 Juli 2026
# Lanjutkan di rumah dengan copy-paste ke Microsoft Word

---

## 4.1 ANALISIS

### 4.1.1 Analisis Masalah
Pencatatan kehadiran mahasiswa dalam kegiatan perkuliahan merupakan salah satu indikator penting dalam evaluasi akademis dan kelayakan mahasiswa untuk mengikuti Ujian Akhir Semester (UAS). Namun, berdasarkan hasil observasi dan studi literatur yang dilakukan, sistem presensi konvensional (menggunakan tanda tangan kertas) memiliki berbagai celah masalah:

1. **Kecurangan Akademis (*Titip Absen*):** Mahasiswa dapat dengan mudah memalsukan tanda tangan rekannya yang tidak hadir, sehingga data kehadiran tidak merepresentasikan kondisi aktual di kelas.
2. **Kehilangan atau Kerusakan Data:** Lembaran kertas presensi rentan sobek, hilang, atau terkena tumpahan cairan, yang berakibat pada hilangnya bukti fisik kehadiran mahasiswa.
3. **Ketidakefisienan Rekapitulasi:** Di akhir semester, dosen atau staf administrasi akademik harus menghitung secara manual persentase kehadiran tiap mahasiswa dari lembaran kertas ke dalam spreadsheet. Proses ini membutuhkan waktu lama dan rawan kesalahan hitung (*human error*).
4. **Kurangnya Akurasi Waktu:** Tanda tangan manual tidak mencatat waktu kedatangan secara presisi. Mahasiswa yang terlambat 30 menit atau lebih tetap mendapat status kehadiran yang sama dengan yang datang tepat waktu, tanpa ada sanksi keterlambatan yang objektif.

**Sebab-sebab terjadi permasalahan:**
* Tidak adanya pencatatan waktu otomatis saat kehadiran dilakukan.
* Tidak adanya mekanisme validasi terpusat yang membatasi hak akses pengisian kehadiran secara tepat waktu.

**Solusi yang Diterapkan:**
Untuk mengatasi masalah tersebut, dikembangkan **Sistem Informasi Presensi Perkuliahan** berbasis web yang mengintegrasikan:
* **Perhitungan Toleransi Keterlambatan Otomatis:** Sistem mencatat waktu presensi secara *real-time* dan membandingkannya dengan jam mulai kelas ditambahkan toleransi keterlambatan (misal 15 menit). Status otomatis berubah menjadi `TERLAMBAT` atau `ALPHA` (jika melewati batas keterlambatan maksimal).
* **Fitur Auto-Alpha saat Sesi Ditutup:** Ketika dosen menutup sesi perkuliahan, mahasiswa anggota kelas yang belum melakukan absensi secara otomatis diubah statusnya menjadi `ALPHA` oleh sistem melalui transaksi database yang aman.

---

## 4.2 Perancangan
## 4.2.1 Pemodelan UML

### A. Use Case Diagram

**Paragraf Pengantar:**

Use Case Diagram berfungsi untuk mendeskripsikan batasan sistem (*system boundary*) beserta fungsionalitas dan pola interaksi tiga aktor utama, yakni Admin Akademik, Dosen Pengampu, dan Mahasiswa pada **Sistem Informasi Presensi Praktikum Mahasiswa di Fakultas Teknologi Informasi Universitas Bale Bandung**.

Adapun rincian deskripsi aktor sistem beserta spesifikasi skenario dan deskripsi use case untuk masing-masing aktor dijelaskan pada tabel-tabel berikut:

#### Tabel 4. 7 Deskripsi Aktor Sistem
*(Sumber : Penulis, 2026)*

| No | Aktor | Deskripsi |
|:---:|:---|:---|
| 1 | **Admin Akademik** | Mengelola data master akademik (prodi, dosen, mahasiswa, mata kuliah praktikum, laboratorium/ruangan), melakukan konfigurasi kelas & jadwal praktikum, memantau rekapitulasi kehadiran praktikum, serta mencetak laporan presensi. |
| 2 | **Dosen Pengampu** | Melihat jadwal mengajar praktikum harian, membuka dan menutup sesi presensi praktikum, melakukan pengubahan status kehadiran mahasiswa secara manual (Izin/Sakit), memantau rekapitulasi kehadiran kelas praktikum yang diampu, serta mencetak laporan presensi. |
| 3 | **Mahasiswa** | Melihat jadwal praktikum pribadi, melakukan presensi mandiri saat sesi praktikum dibuka oleh dosen, serta memantau persentase dan rekapitulasi riwayat kehadiran praktikum pribadi sebagai syarat kelayakan UAS. |

#### Tabel 4. 8 Deskripsi Use Case Admin Akademik
*(Sumber : Penulis, 2026)*

| No | Use Case | Deskripsi |
|:---:|:---|:---|
| 1 | Login | Skenario autentikasi pengguna dengan memasukkan username dan password pada halaman login. Sistem memverifikasi kredensial pengguna, kemudian mengarahkan pengguna ke dashboard sesuai dengan peran (*role*) Admin Akademik. |
| 2 | Kelola Data Program Studi | Skenario pengelolaan data master Program Studi yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa kode program studi, nama program studi, jenjang pendidikan, dan fakultas. |
| 3 | Kelola Data Dosen | Skenario pengelolaan data dosen yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa NIDN, nama lengkap, jenis kelamin, dan program studi dosen pengampu praktikum. |
| 4 | Kelola Data Mahasiswa | Skenario pengelolaan data mahasiswa yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa NIM, nama lengkap, jenis kelamin, angkatan, dan program studi mahasiswa peserta praktikum. |
| 5 | Kelola Mata Kuliah Praktikum | Skenario pengelolaan data mata kuliah praktikum yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa kode mata kuliah, nama praktikum, jumlah SKS, jenis mata kuliah, dan program studi terkait. |
| 6 | Kelola Ruangan / Laboratorium | Skenario pengelolaan data ruangan/laboratorium praktikum yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa kode laboratorium, nama laboratorium, kapasitas kursi/PC, dan gedung. |
| 7 | Kelola Tahun Akademik dan Semester | Skenario pengelolaan data tahun akademik dan semester yang meliputi operasi tambah, ubah, dan hapus data. Admin menetapkan periode akademik serta mengaktifkan semester yang sedang berjalan. |
| 8 | Kelola Kelas Praktikum dan Anggota | Skenario pengelolaan kelas praktikum yang meliputi pembentukan rombongan belajar, penetapan dosen pengampu, kapasitas kelas, serta pendaftaran mahasiswa sebagai anggota kelas praktikum. |
| 9 | Kelola Jadwal Praktikum | Skenario pengelolaan jadwal praktikum dengan menentukan hari, waktu mulai, waktu selesai, ruangan laboratorium, serta batas toleransi keterlambatan dalam satuan menit untuk setiap kelas praktikum. |
| 10 | Lihat Laporan dan Rekap Presensi Praktikum | Skenario menampilkan laporan rekapitulasi kehadiran praktikum mahasiswa berdasarkan kelas dan semester. Sistem menyajikan akumulasi status Hadir, Terlambat, Izin, Sakit, dan Alpha beserta persentase kehadiran sebagai bahan evaluasi dan cetak PDF. |

#### Tabel 4. 9 Deskripsi Use Case Dosen Pengampu
*(Sumber : Penulis, 2026)*

| No | Use Case | Deskripsi |
|:---:|:---|:---|
| 1 | Login | Skenario autentikasi dosen dengan memasukkan username dan password pada halaman login. Sistem memverifikasi kredensial pengguna, kemudian mengarahkan dosen ke dashboard sesuai hak akses yang dimiliki. |
| 2 | Buka Sesi Presensi Praktikum | Skenario pembukaan sesi presensi pada jadwal praktikum yang sedang berlangsung. Dosen mengaktifkan sesi presensi sehingga mahasiswa yang terdaftar pada kelas praktikum tersebut dapat melakukan presensi secara mandiri melalui sistem. |
| 3 | Tutup Sesi Presensi dan Penetapan Alpha Otomatis | Skenario penutupan sesi presensi praktikum oleh dosen. Setelah sesi ditutup, sistem secara otomatis mengidentifikasi mahasiswa yang belum melakukan presensi, kemudian menetapkan status Alpha untuk menjaga akurasi data kehadiran praktikum. |
| 4 | Ubah Status Kehadiran | Skenario perubahan status kehadiran mahasiswa oleh dosen pengampu praktikum, misalnya mengubah status Alpha menjadi Izin atau Sakit disertai keterangan alasan yang sesuai guna memperbarui data presensi di basis data. |
| 5 | Lihat Rekap Kehadiran Kelas Praktikum | Skenario menampilkan rekapitulasi kehadiran mahasiswa pada kelas praktikum yang diampu oleh dosen. Sistem menyajikan informasi jumlah hadir, terlambat, izin, sakit, alpha, serta persentase kehadiran setiap mahasiswa sebagai bahan evaluasi kelayakan UAS. |

#### Tabel 4. 10 Deskripsi Use Case Mahasiswa
*(Sumber : Penulis, 2026)*

| No | Use Case | Deskripsi |
|:---:|:---|:---|
| 1 | Login | Skenario autentikasi mahasiswa dengan memasukkan username dan password pada halaman login. Sistem memverifikasi kredensial pengguna, kemudian mengarahkan mahasiswa ke dashboard sesuai hak akses yang dimiliki. |
| 2 | Presensi Mandiri Praktikum | Skenario pencatatan kehadiran mahasiswa pada sesi presensi praktikum yang telah dibuka oleh dosen. Mahasiswa melakukan presensi secara mandiri melalui sistem, kemudian sistem mencatat waktu presensi dan menghitung selisih waktu terhadap jadwal mulai praktikum untuk menentukan status Hadir atau Terlambat secara otomatis sesuai batas toleransi yang telah ditetapkan. |
| 3 | Lihat Jadwal Praktikum | Skenario menampilkan jadwal praktikum mahasiswa pada semester yang sedang aktif. Sistem menyajikan informasi mengenai hari, waktu praktikum, ruangan laboratorium, mata kuliah praktikum, dan dosen pengampu untuk setiap kelas yang diikuti mahasiswa. |
| 4 | Lihat Rekap Kehadiran Pribadi | Skenario menampilkan rekapitulasi kehadiran mahasiswa untuk setiap mata kuliah praktikum pada semester yang sedang berjalan. Sistem menyajikan jumlah kehadiran, keterlambatan, izin, sakit, alpha, serta persentase kehadiran sebagai informasi perkembangan kehadiran praktikum pribadi. |

---

### B. Activity Diagram

**Paragraf Pengantar:**

Activity Diagram berfungsi untuk memodelkan alur kerja (*workflow*) dari sebuah proses bisnis atau operasional pada sistem. Pemodelan ini menggambarkan urutan aktivitas langkah demi langkah, mulai dari tindakan yang dilakukan oleh aktor pengguna hingga respon dan logika pemrosesan otomatis yang dieksekusi oleh sistem.

Activity Diagram berikut menggambarkan alur kerja operasional presensi praktikum mahasiswa, mulai dari proses autentikasi masuk pengguna, pengelolaan data master praktikum, pembukaan sesi presensi oleh dosen, pengisian presensi mandiri secara *real-time* di laboratorium, eksekusi *auto-alpha* saat penutupan sesi, hingga pengolahan rekapitulasi persentase kehadiran praktikum sebagai syarat kelayakan Ujian Akhir Semester (UAS).

**8 Kalimat Penjelasan Activity Diagram:**

1. Activity Diagram Proses Login menggambarkan proses pengguna (Admin, Dosen, Mahasiswa) dalam melakukan autentikasi masuk ke dalam sistem berdasarkan pencocokan kredensial username dan password.

2. Activity Diagram Kelola Data Master menggambarkan proses Admin Akademik dalam mengelola (menambah, mengubah, dan menghapus) entitas data akademik dan laboratorium praktikum berdasarkan validasi kelengkapan form input oleh sistem.

3. Activity Diagram Konfigurasi Kelas Praktikum mengilustrasikan alur kerja Admin Akademik dalam menginisiasi rombongan belajar praktikum dan menetapkan alokasi mahasiswanya, yang secara otomatis dikontrol oleh sistem melalui fungsi pengecekan batas kapasitas laboratorium dan validasi pencegahan duplikasi data peserta.

4. Activity Diagram Membuka Sesi Presensi Praktikum menggambarkan proses Dosen Pengampu dalam mengaktifkan sesi absensi praktikum pada hari ini agar fitur presensi mandiri di sisi mahasiswa dapat diakses di laboratorium.

5. Activity Diagram Presensi Mandiri Praktikum menggambarkan proses Mahasiswa dalam mencatatkan kehadiran pribadinya secara langsung berbasis server clock dengan kalkulasi selisih batas waktu toleransi keterlambatan oleh sistem.

6. Activity Diagram Tutup Sesi & Auto-Alpha menggambarkan proses Dosen Pengampu dalam mengakhiri penerimaan absensi praktikum sekaligus menyematkan status tidak hadir (Alpha) secara otomatis kepada seluruh mahasiswa yang belum melakukan presensi.

7. Activity Diagram Ubah Status Kehadiran menggambarkan proses Dosen Pengampu dalam merevisi status kehadiran mahasiswa secara manual beserta alasannya guna memperbarui status data presensi di dalam database.

8. Activity Diagram Rekapitulasi Kehadiran Praktikum menggambarkan proses pengguna dalam memantau matriks laporan dan persentase kehadiran praktikum berdasarkan kalkulasi otomatis akumulasi data absensi oleh sistem hingga pencetakan dokumen PDF.

---

### C. Entity Relational Diagram (ERD)

**Paragraf Pengantar:**

ERD menggambarkan relasi antar entitas yang digunakan untuk menyimpan data log dan pengaturan keamanan Sistem Informasi Presensi Praktikum Mahasiswa FTI UNIBBA.

**File ERD Draw.io:** assets/erd_diagram.drawio
*(Buka di app.diagrams.net -> Export As PNG -> Masukkan ke Word dengan lebar 14 cm)*

**Relasi Antar Entitas:**

a. Tabel roles berfungsi sebagai pusat pengelompokan hak akses (*role*) yang menentukan wewenang dan batas fungsionalitas bagi pengguna di dalam sistem. Data pada tabel ini berelasi *one-to-many* dengan tabel users untuk membedakan hak akses antara Admin Akademik, Dosen Pengampu, dan Mahasiswa.

b. Tabel users menyimpan kredensial akun autentikasi login berupa username dan password terenkripsi. Setiap data pada tabel ini berelasi *one-to-one* dengan tabel mahasiswa maupun dosen sebagai pemisah antara akun kredensial dan profil pribadi pengguna.

c. Tabel program_studi menyimpan data induk program studi di lingkungan FTI UNIBBA. Tabel ini berelasi one-to-many dengan tabel mahasiswa, dosen, dan mata_kuliah sebagai pengelompokan entitas civitas akademika dan kurikulum di bawah naungan prodi masing-masing.

d. Tabel mahasiswa menyimpan informasi biodata lengkap mahasiswa. Tabel ini terikat dengan tabel program_studi dan users, serta berelasi many-to-many dengan tabel kelas melalui tabel perantara kelas_anggota dalam pendaftaran anggota kelas perkuliahan.

e. Tabel dosen menyimpan profil pengajar atau dosen pengampu perkuliahan. Tabel ini berelasi one-to-many dengan tabel kelas sebagai penunjuk dosen yang bertanggung jawab mengampu dan mengelola sesi presensi pada kelas tersebut.

f. Tabel tahun_akademik dan semester berfungsi mengatur siklus periode akademik perkuliahan. Tabel tahun_akademik memiliki relasi one-to-many dengan tabel semester, yang kemudian dihubungkan ke tabel kelas untuk memastikan sesi presensi berjalan sesuai periode semester yang sedang aktif.

g. Tabel mata_kuliah dan ruangan masing-masing menyediakan data master mengenai beban SKS mata kuliah dan alokasi fisik ruangan belajar. Kedua tabel ini berelasi dengan tabel kelas dan jadwal untuk membentuk sesi perkuliahan yang valid.

h. Tabel kelas dan kelas_anggota berfungsi sebagai entitas pembentuk rombongan belajar. Tabel kelas menampung informasi mata kuliah, dosen pengampu, dan kapasitas, sementara kelas_anggota mencatat daftar mahasiswa yang secara resmi terdaftar di dalam kelas tersebut.

i. Tabel jadwal menyimpan informasi spesifik mengenai hari, jam mulai, jam selesai, serta batas toleransi keterlambatan. Tabel ini terikat dengan tabel kelas dan ruangan, serta menjadi acuan utama saat dosen mengaktifkan sesi presensi pada tabel presensi.

j. Tabel presensi dan detail_presensi merupakan tabel inti pencatatan transaksi kehadiran. Tabel presensi mencatat header sesi kelas yang dibuka oleh dosen, sedangkan detail_presensi merekam kehadiran setiap mahasiswa secara individual secara real-time.

---

## 4.2.2 Struktur Tabel

Struktur tabel mendeskripsikan secara rinci spesifikasi basis data yang dirancang dalam **Sistem Informasi Presensi Praktikum Mahasiswa di Fakultas Teknologi Informasi Universitas Bale Bandung**. Penjelasan kamus data (*data dictionary*) ini mencakup nama field, tipe data, ukuran (*length*), serta keterangan atribut berupa *Primary Key* (PK) dan *Foreign Key* (FK) yang digunakan pada setiap tabel untuk menjamin integritas dan konsistensi data.

Adapun rincian struktur tabel dari basis data yang dikembangkan adalah sebagai berikut:

#### Tabel 4. 11 Struktur Tabel roles
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| nama_role | VARCHAR | 20 | Nama peran pengguna sistem (admin, dosen, mahasiswa) |
| created_at | TIMESTAMP | – | Waktu pembuatan data |

#### Tabel 4. 12 Struktur Tabel users
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| role_id | INT | 11 | Foreign Key ke roles(id) |
| username | VARCHAR | 50 | Username unik akun pengguna |
| password | VARCHAR | 255 | Password terenkripsi bcrypt via password_hash() |
| is_active | TINYINT | 1 | Status keaktifan akun (1=Aktif, 0=Nonaktif) |
| created_at | TIMESTAMP | – | Waktu pembuatan data |
| updated_at | TIMESTAMP | – | Waktu pembaruan data terakhir |

#### Tabel 4. 13 Struktur Tabel program_studi
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| kode | VARCHAR | 10 | Kode unik program studi (contoh: IF, SI) |
| nama | VARCHAR | 100 | Nama lengkap program studi |
| jenjang | ENUM | 'D3','S1','S2','S3' | Jenjang pendidikan |
| fakultas | VARCHAR | 100 | Nama fakultas pengampu |

#### Tabel 4. 14 Struktur Tabel mahasiswa
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| user_id | INT | 11 | Foreign Key ke users(id) |
| prodi_id | INT | 11 | Foreign Key ke program_studi(id) |
| nim | VARCHAR | 20 | Nomor Induk Mahasiswa (kolom UNIQUE) |
| nama | VARCHAR | 100 | Nama lengkap mahasiswa |
| jenis_kelamin | ENUM | 'L','P' | Jenis kelamin (L = Laki-laki, P = Perempuan) |
| angkatan | YEAR | 4 | Tahun angkatan masuk |
| foto | VARCHAR | 255 | Path lokasi file foto profil |

#### Tabel 4. 15 Struktur Tabel dosen
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| user_id | INT | 11 | Foreign Key ke users(id) |
| prodi_id | INT | 11 | Foreign Key ke program_studi(id) |
| nidn | VARCHAR | 20 | Nomor Induk Dosen Nasional (kolom UNIQUE) |
| nama | VARCHAR | 100 | Nama lengkap dan gelar dosen |
| jenis_kelamin | ENUM | 'L','P' | Jenis kelamin (L = Laki-laki, P = Perempuan) |
| foto | VARCHAR | 255 | Path lokasi file foto profil |

#### Tabel 4. 16 Struktur Tabel tahun_akademik
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| nama | VARCHAR | 20 | Format tahun akademik (contoh: 2024/2025) |
| tahun_mulai | YEAR | 4 | Tahun awal periode akademik |
| tahun_selesai | YEAR | 4 | Tahun akhir periode akademik |
| status | ENUM | 'aktif','nonaktif' | Status keaktifan tahun akademik |

#### Tabel 4. 17 Struktur Tabel semester
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| tahun_akademik_id | INT | 11 | Foreign Key ke tahun_akademik(id) |
| nama_semester | ENUM | 'Ganjil','Genap' | Jenis semester dalam tahun akademik |
| is_aktif | TINYINT | 1 | Status semester berjalan (1=Aktif, 0=Nonaktif) |

#### Tabel 4. 18 Struktur Tabel mata_kuliah
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| prodi_id | INT | 11 | Foreign Key ke program_studi(id) |
| kode | VARCHAR | 10 | Kode mata kuliah (contoh: IF301) |
| nama | VARCHAR | 100 | Nama lengkap mata kuliah |
| sks | TINYINT | 1 | Jumlah bobot Satuan Kredit Semester (1–6) |
| jenis | ENUM | 'Wajib','Pilihan' | Klasifikasi jenis mata kuliah |

#### Tabel 4. 19 Struktur Tabel ruangan
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| kode | VARCHAR | 10 | Kode ruangan/laboratorium (contoh: R101, LAB-A) |
| nama | VARCHAR | 50 | Nama ruangan/laboratorium praktikum |
| kapasitas | INT | 4 | Kapasitas maksimal kursi/PC ruangan |
| gedung | VARCHAR | 50 | Nama gedung lokasi ruangan |

#### Tabel 4. 20 Struktur Tabel kelas
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| mata_kuliah_id | INT | 11 | Foreign Key ke mata_kuliah(id) |
| dosen_id | INT | 11 | Foreign Key ke dosen(id) |
| semester_id | INT | 11 | Foreign Key ke semester(id) |
| nama | VARCHAR | 10 | Nama kelas praktikum (contoh: A, B, C) |
| kapasitas | INT | 4 | Kuota maksimal mahasiswa dalam kelas praktikum |

#### Tabel 4. 21 Struktur Tabel kelas_anggota
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| kelas_id | INT | 11 | Foreign Key ke kelas(id) |
| mahasiswa_id | INT | 11 | Foreign Key ke mahasiswa(id) |

#### Tabel 4. 22 Struktur Tabel jadwal
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| kelas_id | INT | 11 | Foreign Key ke kelas(id) |
| ruangan_id | INT | 11 | Foreign Key ke ruangan(id) |
| hari | ENUM | 'Senin',...,'Sabtu' | Hari pelaksanaan praktikum |
| jam_mulai | TIME | – | Waktu awal praktikum dimulai |
| jam_selesai | TIME | – | Waktu akhir praktikum berakhir |
| toleransi_menit | INT | 3 | Batas toleransi keterlambatan dalam satuan menit |

#### Tabel 4. 23 Struktur Tabel presensi
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| jadwal_id | INT | 11 | Foreign Key ke jadwal(id) |
| tanggal | DATE | – | Tanggal pelaksanaan sesi presensi |
| jam_buka | TIME | – | Waktu sesi presensi dibuka oleh dosen |
| jam_tutup | TIME | – | Waktu sesi presensi ditutup oleh dosen |
| status | ENUM | 'BELUM_DIBUKA','AKTIF','SELESAI' | Status keaktifan sesi presensi |
| pertemuan_ke | INT | 2 | Urutan nomor pertemuan praktikum (1–16) |
| keterangan | TEXT | – | Catatan atau materi praktikum |
| created_at | TIMESTAMP | – | Waktu pembuatan data sesi presensi |

#### Tabel 4. 24 Struktur Tabel detail_presensi
*(Sumber : Penulis, 2026)*

| Nama Kolom | Tipe Data | Panjang | Keterangan |
|:---|:---|:---:|:---|
| id | INT | AUTO | Primary Key, Auto Increment |
| presensi_id | INT | 11 | Foreign Key ke presensi(id) |
| mahasiswa_id | INT | 11 | Foreign Key ke mahasiswa(id) |
| status | ENUM | 'HADIR','IZIN','SAKIT','TERLAMBAT','ALPHA' | Status kehadiran mahasiswa per sesi praktikum |
| jam_presensi | TIME | – | Waktu mahasiswa melakukan presensi mandiri |
| keterangan | TEXT | – | Alasan keterangan jika Izin atau Sakit |
| created_at | TIMESTAMP | – | Waktu pencatatan data kehadiran praktikum |

---

## 4.2.3 Desain Antarmuka (Interface Design)

**Paragraf Pengantar:**

Tahap desain dilakukan dengan merancang antarmuka (*interface*) dan alur kerja sistem yang terdiri atas Halaman Login, Dasbor Utama (Admin, Dosen, Mahasiswa), Halaman Kelola Sesi Presensi Praktikum, serta Halaman Presensi Mandiri Praktikum. Rancangan antarmuka tersebut disesuaikan dengan kebutuhan kemudahan pengguna (*User Experience*), prinsip *Clean & Responsive Design*, serta kejelasan informasi kehadiran praktikum mahasiswa.

---

### A. Rancangan Antarmuka / Wireframe

Berikut adalah gambaran umum rancangan tata letak antarmuka (*wireframe*) utama aplikasi:

1. **Halaman Login (Form Login Multi-Role):** Form minimalis di bagian tengah layar dengan latar belakang gradien profesional. Menyediakan bidang isian Username, Password, pilihan peran pengguna (*Admin*, *Dosen*, *Mahasiswa*), serta pesan validasi keamanan.
2. **Dashboard Utama:**
   - **Admin:** Menampilkan ringkasan statistik (Total Mahasiswa, Dosen, Mata Kuliah Praktikum, Kelas Praktikum Aktif), serta grafik aktivitas kehadiran praktikum.
   - **Dosen & Mahasiswa:** Menampilkan kartu profil pengguna di bagian atas dan daftar kartu (*card*) jadwal praktikum hari ini di bagian bawah.
3. **Halaman Kelola Presensi Praktikum (Dosen):** Tabel daftar mahasiswa peserta kelas praktikum beserta status kehadiran real-time, dilengkapi tombol aksi cepat pembukaan sesi, penutupan sesi (*Auto-Alpha*), dan pengubahan status manual.
4. **Halaman Presensi Mandiri Praktikum (Mahasiswa):** Tampilan responsif mobile (*Mobile-First*) dengan tombol aksentuasi utama "**HADIR!**", penunjuk waktu real-time server, serta indikator batas toleransi keterlambatan praktikum.

---

### B. Detail Rancangan Antarmuka & Alur Interaksi Pengguna (15 Halaman)

Adapun rincian rancangan visual antarmuka dan alur interaksi pengguna pada Sistem Informasi Presensi Praktikum Mahasiswa adalah sebagai berikut:

#### 1. Halaman Autentikasi (Form Login Multi-Role)
Halaman autentikasi merupakan pintu masuk utama pengguna ke dalam sistem. Antarmuka ini dirancang bersih dengan form login terpusat, input username, password, dan pilihan peran (Admin, Dosen, Mahasiswa).

**Gambar 4. 25 Form Login Multi-Role**  
*(Sumber File : assets/images/login_mockup.png)*

- **Alur Interaksi:** Pengguna mengisikan username, password, dan memilih role aktif. Saat tombol **Masuk** ditekan, controller memverifikasi kredensial, meregenerasi ID sesi (`session_regenerate_id(true)`), lalu mengarahkan ke dashboard yang sesuai.

#### 2. Halaman Dashboard Utama (Admin Akademik)
Dashboard Admin menyajikan pusat kendali data statistik akademik dan laboratorium praktikum secara real-time yang mencakup jumlah mahasiswa, dosen, kelas praktikum aktif, dan jadwal praktikum berjalan.

**Gambar 4. 26 Dashboard Utama Admin Akademik**  
*(Sumber File : assets/images/dashboard_mockup.png)*

- **Alur Interaksi:** Admin memantau metrik data master melalui kartu informasi dan ringkasan grafik. Admin dapat langsung mengakses menu navigasi sidebar di sebelah kiri untuk mengelola data master praktikum.

#### 3. Halaman Kelola Sesi Praktikum & Buka Presensi (Dosen)
Antarmuka ini digunakan Dosen Pengampu untuk memilih jadwal praktikum hari ini, menentukan nomor pertemuan (1–16), mengisikan modul praktikum/materi, dan mengaktifkan sesi presensi mandiri.

- **Alur Interaksi:** Dosen menekan tombol **Buka Presensi** pada jadwal praktikum berjalan. Sistem mengubah status sesi menjadi `AKTIF`, mencatat jam buka server, dan mengizinkan mahasiswa anggota kelas praktikum melakukan presensi.

#### 4. Halaman Presensi Mandiri Mahasiswa (Mobile View)
Antarmuka presensi mandiri dirancang responsif untuk diakses melalui smartphone mahasiswa saat berada di laboratorium/ruang praktikum.

- **Alur Interaksi:** Mahasiswa membuka dashboard pada jam praktikum berjalan dan menekan tombol **"HADIR!"**. Sistem mencatat waktu server, menghitung selisih keterlambatan, serta menetapkan status akhir (`HADIR`/`TERLAMBAT`).

#### 5. Halaman Kelola Presensi Kelas Praktikum & Auto-Alpha (Dosen)
Antarmuka ini menampilkan daftar mahasiswa peserta praktikum beserta jam presensi, status kehadiran, dan tombol penyesuaian manual.

**Gambar 4. 27 Kelola Presensi Kelas Praktikum & Auto-Alpha (Dosen)**  
*(Sumber File : assets/images/kelola_presensi.png)*

- **Alur Interaksi:** Daftar presensi ter-update secara real-time. Saat sesi praktikum selesai, Dosen menekan **Tutup Presensi**, memicu transaksi *Auto-Alpha* yang secara otomatis mengisikan status `ALPHA` bagi mahasiswa yang tidak melakukan presensi.

#### 6. Halaman Dashboard Mahasiswa
Antarmuka utama bagi mahasiswa yang menyajikan ringkasan profil, jadwal praktikum hari ini, serta tabel persentase kehadiran per mata kuliah praktikum.

**Gambar 4. 28 Dashboard Utama Mahasiswa**  
*(Sumber File : assets/images/dashboard_mahasiswa.png)*

- **Alur Interaksi:** Mahasiswa melihat jadwal praktikum aktif dan dapat langsung menekan tombol **Lakukan Presensi** atau **Lihat Rekap Kehadiran**.

#### 7. Halaman Rekap Presensi Praktikum Kelas (Dosen)
Antarmuka ini menyajikan matriks akumulasi status kehadiran mahasiswa (Hadir, Terlambat, Izin, Sakit, Alpha) per semester serta persentase kehadiran kelas praktikum.

**Gambar 4. 29 Rekapitulasi Kehadiran Kelas Praktikum (Dosen)**  
*(Sumber File : assets/images/rekap_presensi_dosen.png)*

- **Alur Interaksi:** Dosen memilih kelas praktikum dari dropdown dan menekan tombol **Tampilkan Rekap** untuk meninjau data statistik atau mengekspor laporan via tombol **Cetak Laporan**.

#### 8. Halaman Rekap Kehadiran Saya (Mahasiswa)
Menyajikan grafik persentase kehadiran pribadi mahasiswa pada kegiatan praktikum beserta indikator ambang batas kelayakan mengikuti Ujian Akhir Semester (UAS ≥ 75%).

**Gambar 4. 30 Rekap Kehadiran Saya (Mahasiswa)**  
*(Sumber File : assets/images/rekap_kehadiran_mahasiswa.png)*

- **Alur Interaksi:** Mahasiswa memfilter riwayat kehadiran berdasarkan mata kuliah praktikum untuk memastikan syarat kelayakan UAS terpenuhi.

#### 9. Halaman Pengelolaan Data Master (CRUD Admin)
Panel administrasi bagi Admin untuk mengelola entitas Program Studi, Dosen, Mahasiswa, Mata Kuliah Praktikum, dan Ruangan Laboratorium.

- **Alur Interaksi:** Admin mencari data pada tabel paginasi, menekan tombol **Tambah Data** untuk membuka modal form, atau menekan tombol **Edit** / **Hapus** untuk mengelola record.

#### 10. Halaman Kelola Kelas Praktikum & Anggota Kelas
Antarmuka untuk pembentukan rombongan belajar praktikum dan pendaftaran mahasiswa ke dalam kelas pada semester aktif.

- **Alur Interaksi:** Admin memilih kelas praktikum dan menambahkan mahasiswa dari dropdown. Sistem memvalidasi batas kapasitas laboratorium dan mencegah duplikasi pendaftaran.

#### 11. Halaman Kelola Jadwal Praktikum
Antarmuka untuk penyusunan alokasi hari, jam mulai, jam selesai, laboratorium praktikum, dan batas toleransi keterlambatan per mata kuliah praktikum.

- **Alur Interaksi:** Admin memilih kelas dan laboratorium, menetapkan jam dan toleransi keterlambatan (misal 15 menit), kemudian menyimpan jadwal setelah lolos validasi bentrokan ruangan.

#### 12. Halaman Kelola Semester & Tahun Akademik
Modul pengaturan siklus akademik aktif (contoh: 2024/2025 Genap) untuk membatasi lingkup pencatatan presensi praktikum.

- **Alur Interaksi:** Admin mengaktifkan periode semester berjalan, yang secara otomatis memfilter seluruh tampilan jadwal praktikum dan laporan rekapitulasi.

#### 13. Halaman Kelola User & Hak Akses
Modul manajemen akun autentikasi untuk pendaftaran akun baru, reset password, dan pengontrolan status keaktifan user (`is_active`).

- **Alur Interaksi:** Admin dapat menonaktifkan akun bermasalah atau melakukan reset kata sandi jika pengguna lupa password.

#### 14. Halaman Cetak Laporan Rekapitulasi Presensi (PDF)
Tampilan ramah cetak (*print-friendly*) tanpa navigasi sidebar/header yang memuat tabel laporan presensi praktikum resmi siap cetak atau simpan PDF.

- **Alur Interaksi:** Pengguna menekan tombol **Cetak Laporan**, sistem merender halaman khusus dan memicu perintah cetak window browser (`window.print()`).

#### 15. Halaman Profil Pengguna (Dosen & Mahasiswa)
Antarmuka untuk melihat informasi data pribadi resmi (NIM/NIDN, Prodi, Email) serta fitur pembaruan kata sandi akun.

- **Alur Interaksi:** Pengguna meninjau profil biodata dan dapat memperbarui password dengan memasukkan password lama dan password baru.

---

## CATATAN PENTING

### File ERD Draw.io:
- Lokasi: d:\laragon\www\sistem_presensi\assets\erd_presensi.drawio
- Cara export: Buka app.diagrams.net -> File -> Open -> pilih file -> File -> Export As -> PNG
- Di Word: atur lebar gambar = 14 cm

### Tips Layout Word:
- Margin standar skripsi: Kiri 4cm, Atas 3cm, Bawah 3cm, Kanan 3cm
- Jika tabel terpotong ke halaman berikutnya: Ctrl+Enter di depan judul sub-bab untuk page break
- Jika Spacing After terlalu besar (30pt): ubah jadi 0pt di Layout -> Spacing After
- ERD sudah font JUMBO (15pt header, 13pt field) agar jelas saat dicetak

### Yang Sudah Selesai:
- [x] 4.1.1 - 4.1.7 Analisis (termasuk Analisis Biaya)
- [x] 4.2.1 Pemodelan UML (Use Case + 9 Activity Diagram + ERD)
- [x] 4.2.2 Struktur Tabel (14 tabel kamus data)
- [x] 4.2.3 Desain Antarmuka (Wireframe + 16 Rincian Antarmuka UI & Alur Interaksi)

### Yang Belum / Perlu Dilanjutkan:
- [ ] BAB 5 Implementasi dan Pengujian (Sudah disiapkan di `riwayat_chat.txt` & `LAMPIRAN_SKRIPSI.md`)

