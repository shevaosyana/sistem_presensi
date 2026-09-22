# Dokumen Pemodelan Use Case Diagram & Tabel Deskripsi

Dokumen ini berisi spesifikasi pemodelan Use Case Diagram beserta **Tabel Deskripsi Aktor** dan **Tabel Deskripsi Use Case** pada Sistem Informasi Presensi Perkuliahan.

---

### **Tabel 4. 7 Deskripsi Aktor Sistem**
*(Sumber : Penulis, 2026)*

| No | Aktor | Deskripsi |
|:---:|:---|:---|
| 1 | **Admin Akademik** | Pengguna yang memiliki hak akses tertinggi dalam sistem. Bertugas mengelola seluruh data master (Prodi, Dosen, Mahasiswa, Mata Kuliah, Ruangan, Kelas, Jadwal), mengatur Tahun Akademik & Semester, serta melakukan audit keamanan integritas data presensi menggunakan algoritma SHA-256. |
| 2 | **Dosen Pengampu** | Pengguna yang bertugas sebagai pengajar pada suatu kelas. Memiliki hak akses untuk membuka dan menutup sesi presensi, mengubah status kehadiran mahasiswa secara manual, serta melihat rekapitulasi kehadiran kelas yang diampunya. |
| 3 | **Mahasiswa** | Pengguna yang terdaftar sebagai anggota kelas. Memiliki hak akses untuk melakukan presensi mandiri pada sesi yang telah dibuka oleh dosen, melihat jadwal perkuliahan, serta melihat rekapitulasi kehadiran pribadinya per mata kuliah. |

---

### **Tabel 4. 8 Deskripsi Use Case Admin Akademik**
*(Sumber : Penulis, 2026)*

| No | Use Case | Deskripsi |
|:---:|:---|:---|
| 1 | **Login** | Skenario autentikasi pengguna dengan memasukkan username dan password pada halaman login. Sistem memverifikasi kredensial menggunakan algoritma hash SHA-256, kemudian mengarahkan pengguna ke dashboard sesuai dengan peran (*role*) yang dimiliki. |
| 2 | **Kelola Data Program Studi** | Skenario pengelolaan data master Program Studi yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa kode program studi, nama program studi, jenjang pendidikan, dan fakultas. |
| 3 | **Kelola Data Dosen** | Skenario pengelolaan data dosen yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa NIDN, nama lengkap, jenis kelamin, dan program studi dosen. |
| 4 | **Kelola Data Mahasiswa** | Skenario pengelolaan data mahasiswa yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa NIM, nama lengkap, jenis kelamin, angkatan, dan program studi mahasiswa. |
| 5 | **Kelola Mata Kuliah** | Skenario pengelolaan data mata kuliah yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa kode mata kuliah, nama mata kuliah, jumlah SKS, jenis mata kuliah, dan program studi terkait. |
| 6 | **Kelola Ruangan** | Skenario pengelolaan data ruangan perkuliahan yang meliputi operasi tambah, ubah, dan hapus data. Admin mengelola informasi berupa kode ruangan, nama ruangan, kapasitas, dan gedung. |
| 7 | **Kelola Tahun Akademik dan Semester** | Skenario pengelolaan data tahun akademik dan semester yang meliputi operasi tambah, ubah, dan hapus data. Admin menetapkan periode akademik serta mengaktifkan semester yang sedang berjalan. |
| 8 | **Kelola Kelas dan Anggota** | Skenario pengelolaan kelas perkuliahan yang meliputi pembentukan kelas, penetapan dosen pengampu, kapasitas kelas, serta pendaftaran mahasiswa sebagai anggota kelas. |
| 9 | **Kelola Jadwal** | Skenario pengelolaan jadwal perkuliahan dengan menentukan hari, waktu mulai, waktu selesai, ruangan, serta batas toleransi keterlambatan dalam satuan menit untuk setiap kelas. |
| 10 | **Verifikasi Integritas Hash** | Skenario verifikasi integritas data presensi dengan merekonstruksi nilai hash SHA-256 untuk setiap data presensi dan membandingkannya dengan nilai hash yang tersimpan di basis data guna mendeteksi adanya perubahan atau manipulasi data di luar sistem. |
| 11 | **Lihat Laporan dan Rekap Presensi** | Skenario menampilkan laporan rekapitulasi kehadiran mahasiswa berdasarkan kelas dan semester. Sistem menyajikan jumlah kehadiran, keterlambatan, izin, sakit, dan alfa beserta persentase kehadiran sebagai bahan evaluasi. |

---

### **Tabel 4. 9 Deskripsi Use Case Dosen Pengampu**
*(Sumber : Penulis, 2026)*

| No | Use Case | Deskripsi |
|:---:|:---|:---|
| 1 | **Login** | Skenario autentikasi dosen dengan memasukkan username dan password pada halaman login. Sistem memverifikasi kredensial menggunakan algoritma hash SHA-256, kemudian mengarahkan dosen ke dashboard sesuai hak akses yang dimiliki. |
| 2 | **Buka Sesi Presensi** | Skenario pembukaan sesi presensi pada jadwal perkuliahan yang sedang berlangsung. Dosen mengaktifkan sesi presensi sehingga mahasiswa yang terdaftar pada kelas tersebut dapat melakukan presensi secara mandiri melalui sistem. |
| 3 | **Tutup Sesi Presensi dan Penetapan Alpha Otomatis** | Skenario penutupan sesi presensi oleh dosen. Setelah sesi ditutup, sistem secara otomatis mengidentifikasi mahasiswa yang belum melakukan presensi, kemudian menetapkan status Alpha serta menghasilkan nilai hash SHA-256 untuk menjaga integritas data presensi. |
| 4 | **Ubah Status Kehadiran** | Skenario perubahan status kehadiran mahasiswa oleh dosen, misalnya mengubah status Alpha menjadi Izin atau Sakit disertai keterangan yang sesuai. Setiap perubahan data akan diikuti dengan pembentukan nilai hash SHA-256 yang baru untuk menjaga integritas data. |
| 5 | **Lihat Rekap Kehadiran Kelas** | Skenario menampilkan rekapitulasi kehadiran mahasiswa pada kelas yang diampu oleh dosen. Sistem menyajikan informasi jumlah hadir, terlambat, izin, sakit, alpha, serta persentase kehadiran setiap mahasiswa sebagai bahan evaluasi proses pembelajaran. |

---

### **Tabel 4. 10 Deskripsi Use Case Mahasiswa**
*(Sumber : Penulis, 2026)*

| No | Use Case | Deskripsi |
|:---:|:---|:---|
| 1 | **Login** | Skenario autentikasi mahasiswa dengan memasukkan username dan password pada halaman login. Sistem memverifikasi kredensial menggunakan algoritma hash SHA-256, kemudian mengarahkan mahasiswa ke dashboard sesuai hak akses yang dimiliki. |
| 2 | **Presensi Mandiri** | Skenario pencatatan kehadiran mahasiswa pada sesi presensi yang telah dibuka oleh dosen. Mahasiswa melakukan presensi secara mandiri melalui sistem, kemudian sistem mencatat waktu presensi dan menghitung selisih waktu terhadap jadwal mulai perkuliahan untuk menentukan status Hadir atau Terlambat secara otomatis sesuai batas toleransi yang telah ditetapkan. |
| 3 | **Lihat Jadwal Kuliah** | Skenario menampilkan jadwal perkuliahan mahasiswa pada semester yang sedang aktif. Sistem menyajikan informasi mengenai hari, waktu perkuliahan, ruangan, mata kuliah, dan dosen pengampu untuk setiap kelas yang diikuti mahasiswa. |
| 4 | **Lihat Rekap Kehadiran Pribadi** | Skenario menampilkan rekapitulasi kehadiran mahasiswa untuk setiap mata kuliah pada semester yang sedang berjalan. Sistem menyajikan jumlah kehadiran, keterlambatan, izin, sakit, alpha, serta persentase kehadiran sebagai informasi perkembangan kehadiran mahasiswa. |
