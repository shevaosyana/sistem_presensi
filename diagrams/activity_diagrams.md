# Dokumen Spesifikasi Activity Diagram
## Sistem Informasi Presensi Praktikum Mahasiswa di Fakultas Teknologi Informasi Universitas Bale Bandung

Dokumen ini berisi spesifikasi **8 Activity Diagram Utama** (Format 3 Swimlane Bingkai Penuh: `Input (Aktor)`, `Proses (Sistem/Engine)`, `Output (Dashboard/Layar)`) yang telah disesuaikan dengan konteks **Presensi Praktikum Mahasiswa FTI UNIBBA**.

Daftar 8 Activity Diagram:
1. **Activity Diagram 1: Login**
2. **Activity Diagram 2: Kelola Data Master Praktikum**
3. **Activity Diagram 3: Konfigurasi Kelompok & Jadwal Praktikum**
4. **Activity Diagram 4: Buka Sesi Presensi Praktikum**
5. **Activity Diagram 5: Presensi Mandiri Praktikan**
6. **Activity Diagram 6: Tutup Sesi Presensi Praktikum & Auto-Alpha**
7. **Activity Diagram 7: Ubah Status Kehadiran Praktikum**
8. **Activity Diagram 8: Rekap Presensi & Cetak Laporan Praktikum PDF**

---

## 1. Activity Diagram 1: Login

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Akses Halaman Login Sistem Presensi Praktikum;
:Input Username & Password;
:Klik Tombol Login;

|Proses (Sistem/Engine)|
:Validasi Form & Cek Kredensial Pengguna;
if (Kredensial Valid?) then (Ya)
  :Buat Sesi Login (Session);
  |Output (Dashboard/Layar)|
  :Arahkan ke Dashboard Role (Admin/Dosen/Aslab/Mahasiswa);
  stop
else (Tidak)
  |Output (Dashboard/Layar)|
  :Tampilkan Pesan Error "Username/Password Salah";
  stop
endif
@enduml
```

---

## 2. Activity Diagram 2: Kelola Data Master Praktikum

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Pilih Menu Data Master (Laboratorium, Dosen, Aslab, Mhs, Modul);
:Input / Edit Form Data Master Praktikum;
:Klik Tombol Simpan / Hapus;

|Proses (Sistem/Engine)|
:Validasi Kelengkapan & Cek Duplikasi Data Master;
if (Data Valid?) then (Ya)
  :Query Exec (Insert/Update/Delete Data Master);
  |Output (Dashboard/Layar)|
  :Tampilkan Pesan Sukses & Refresh Tabel Data Master;
  stop
else (Tidak)
  |Output (Dashboard/Layar)|
  :Tampilkan Pesan Peringatan Input Invalid;
  stop
endif
@enduml
```

---

## 3. Activity Diagram 3: Konfigurasi Kelompok & Jadwal Praktikum

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Pilih Menu Kelola Kelompok & Jadwal Praktikum;
:Input Kelompok Praktikum, Shift/Sesi, Lab, Dosen/Aslab & Mhs;
:Klik Tombol Simpan Jadwal Praktikum;

|Proses (Sistem/Engine)|
:Validasi Form & Cek Bentrok Jadwal Ruang Lab / Aslab;
if (Jadwal Valid & Tidak Bentrok?) then (Ya)
  :Simpan Record Kelompok, Peserta & Jadwal Praktikum;
  |Output (Dashboard/Layar)|
  :Tampilkan Pesan Sukses & Refresh Tabel Jadwal Praktikum;
  stop
else (Tidak / Bentrok)
  |Output (Dashboard/Layar)|
  :Tampilkan Peringatan Jadwal Lab / Aslab Bentrok;
  stop
endif
@enduml
```

---

## 4. Activity Diagram 4: Buka Sesi Presensi Praktikum

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Pilih Sesi Praktikum Hari Ini pada Menu Presensi;
:Klik Tombol Buka Sesi Presensi Praktikum;

|Proses (Sistem/Engine)|
:Catat Jam Mulai Sesi & Update Status 'AKTIF';
:Aktifkan Tombol Presensi Mandiri Mahasiswa Praktikan;

|Output (Dashboard/Layar)|
:Tampilkan Antarmuka Monitoring Presensi Realtime Sesi Lab;
stop
@enduml
```

---

## 5. Activity Diagram 5: Presensi Mandiri Praktikan

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Buka Menu Presensi Praktikum Hari Ini;
:Klik Tombol Presensi Sekarang;

|Proses (Sistem/Engine)|
:Cek Status Sesi Lab & Hitung Waktu Toleransi Keterlambatan;
if (Dalam Toleransi Waktu Praktikum?) then (Ya)
  :Set Status Kehadiran 'HADIR';
  :Simpan Record Presensi ke Database;
  |Output (Dashboard/Layar)|
  :Tampilkan Status Kehadiran 'HADIR' pada Sesi Praktikum;
  stop
else (Terlambat / Sesi Ditutup)
  |Proses (Sistem/Engine)|
  :Set Status Kehadiran 'TERLAMBAT';
  :Simpan Record Presensi ke Database;
  |Output (Dashboard/Layar)|
  :Tampilkan Status Kehadiran 'TERLAMBAT' pada Sesi Praktikum;
  stop
endif
@enduml
```

---

## 6. Activity Diagram 6: Tutup Sesi Presensi Praktikum & Auto-Alpha

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Buka Monitoring Sesi Praktikum Aktif;
:Klik Tombol Tutup Sesi Presensi Praktikum;

|Proses (Sistem/Engine)|
:Update Status Sesi Praktikum menjadi 'SELESAI';
:Jalankan Auto-Alpha (Identifikasi Praktikan Belum Presensi);
:Simpan Bulk Status 'ALPHA' Mahasiswa Absen Praktikum;

|Output (Dashboard/Layar)|
:Tampilkan Ringkasan Rekapitulasi Presensi Sesi Praktikum;
stop
@enduml
```

---

## 7. Activity Diagram 7: Ubah Status Kehadiran Praktikum

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Pilih Mahasiswa Praktikan pada Detail Presensi Lab;
:Ubah Status Kehadiran (Izin/Sakit/Hadir) & Isi Alasan;
:Klik Tombol Simpan Perubahan;

|Proses (Sistem/Engine)|
:Validasi Kelengkapan Alasan Perubahan Status;
if (Alasan Diisi?) then (Ya)
  :Update Status Kehadiran & Keterangan Alasan di Database;
  |Output (Dashboard/Layar)|
  :Tampilkan Notifikasi Perubahan Presensi Berhasil;
  stop
else (Tidak)
  |Output (Dashboard/Layar)|
  :Tampilkan Peringatan Alasan Perubahan Wajib Diisi;
  stop
endif
@enduml
```

---

## 8. Activity Diagram 8: Rekap Presensi & Cetak Laporan Praktikum PDF

```plantuml
@startuml
skinparam SwimlaneBorderColor #000000
skinparam SwimlaneBorderThickness 1
skinparam SwimlaneTitleBorderColor #000000
skinparam SwimlaneTitleBackgroundColor #FFFFFF
skinparam ActivityBorderColor #000000
skinparam ActivityBackgroundColor #FFFFFF

|Input (Aktor)|
start
:Buka Menu Rekap Presensi Praktikum;
:Pilih Filter (Mata Kuliah Praktikum, Kelompok, Lab, Semester);
:Klik Tampilkan Rekap / Cetak PDF Laporan;

|Proses (Sistem/Engine)|
:Query Data Presensi, Hitung % Kehadiran & Render PDF Laporan;
if (Aksi Cetak PDF & Render Sukses?) then (Ya)
  :Generate Stream Berkas PDF Laporan Presensi Praktikum;
  |Output (Dashboard/Layar)|
  :Download File PDF Laporan Praktikum & Tampilkan Tabel Rekap;
  stop
else (Tampilkan Rekap di Layar)
  |Output (Dashboard/Layar)|
  :Tampilkan Tabel Rekapitulasi & Persentase Kehadiran Praktikan;
  stop
endif
@enduml
```
