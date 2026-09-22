# C. SEQUENCE DIAGRAM
## Sistem Informasi Presensi Praktikum Mahasiswa di Fakultas Teknologi Informasi Universitas Bale Bandung

Dokumen ini berisi spesifikasi **8 Sequence Diagram** yang menggambarkan interaksi alur waktu (*chronological order*) antar komponen perangkat lunak (Aktor, View, Controller, Model, dan Database MySQL) untuk sistem **Presensi Praktikum Mahasiswa FTI UNIBBA** (tanpa fitur khusus SHA-256 / AuditController).

---

### **Legenda Notasi Sequence Diagram**

| Notasi | Nama Notasi | Deskripsi & Fungsi |
| :---: | :--- | :--- |
| **Actor** | Aktor / Pengguna | Pihak luar (Admin Lab, Dosen, Aslab, Mahasiswa) yang berinteraksi dengan sistem. |
| **View (Boundary)** | Antarmuka / Halaman | Komponen halaman antarmuka web (PHP/HTML) tempat pengguna melakukan aksi. |
| **Controller (Control)** | Pengontrol Logika | Komponen pengolah logika bisnis dan pengatur lalu lintas data aplikasi. |
| **Model (Entity)** | Model Data | Komponen pengelola query dan pemetaan entitas ke basis data. |
| **Database (Data Store)** | Basis Data MySQL | Penyimpanan data persisten sistem (MySQL). |
| &rarr; | *Synchronous Message* | Pesan pemanggilan metode/fungsi yang menunggu balasan *response*. |
| - - &dash;&rarr; | *Return Message* | Pesan balasan (*return value*) atau render tampilan ke antarmuka. |
| **alt** | *Alternative Fragment* | Struktur percabangan logika (*if-else*) berdasarkan kondisi tertentu. |

---

### **SD-01: Sequence Diagram — Login Presensi Praktikum**

```mermaid
sequenceDiagram
    actor Pengguna as Pengguna (Admin/Dosen/Aslab/Mhs)
    participant View as View (login.php)
    participant Controller as AuthController
    participant Model as UserModel
    participant DB as Database MySQL

    Pengguna->>View: 1. Akses halaman login praktikum
    View->>Controller: 2. GET /login
    Controller-->>View: 3. Render form login
    View-->>Pengguna: 4. Tampilkan form login

    Pengguna->>View: 5. Input username & password, Klik Login
    View->>Controller: 6. POST /login {username, password}
    Controller->>Model: 7. findByUsername(username)
    Model->>DB: 8. SELECT * FROM users WHERE username=?
    DB-->>Model: 9. Data User Record
    Model-->>Controller: 10. Return User Data Object

    alt [Kredensial Valid]
        Controller-->>View: 11a. Set session & Redirect ke Dashboard Role
        View-->>Pengguna: 12a. Tampilkan Halaman Dashboard Role
    else [Kredensial Salah]
        Controller-->>View: 11b. Flash error 'Username/Password Salah'
        View-->>Pengguna: 12b. Tampilkan Error di Halaman Login
    end
```

---

### **SD-02: Sequence Diagram — Kelola Data Master Praktikum**

```mermaid
sequenceDiagram
    actor Admin as Admin Laboratorium
    participant View as View (master_praktikum.php)
    participant Controller as MasterController
    participant Model as MasterModel
    participant DB as Database MySQL

    Admin->>View: 1. Pilih Menu Data Master (Lab/Modul/Dosen/Aslab/Mhs)
    Admin->>View: 2. Input / Edit Form Data & Klik Simpan
    View->>Controller: 3. POST /master/store {data}
    Controller->>Controller: 4. validateMasterData(data)
    Controller->>Model: 5. saveMaster(data)
    Model->>DB: 6. INSERT / UPDATE data_master
    DB-->>Model: 7. DB Result Success
    Model-->>Controller: 8. Return status true
    Controller-->>View: 9. Render Sukses & Refresh Tabel
    View-->>Admin: 10. Tampilkan Pesan Sukses
```

---

### **SD-03: Sequence Diagram — Konfigurasi Kelompok & Jadwal Praktikum**

```mermaid
sequenceDiagram
    actor Admin as Admin Laboratorium
    participant View as View (jadwal_praktikum.php)
    participant Controller as JadwalController
    participant Model as JadwalModel
    participant DB as Database MySQL

    Admin->>View: 1. Input Kelompok, Shift, Lab, Dosen, Aslab & Jam
    Admin->>View: 2. Klik Simpan Jadwal Praktikum
    View->>Controller: 3. POST /jadwal/store {data}
    Controller->>Model: 4. checkScheduleConflict(lab_id, jam, aslab_id)
    Model->>DB: 5. SELECT * FROM jadwal_praktikum WHERE lab_id=? AND jam=?
    DB-->>Model: 6. Result Check
    
    alt [Jadwal Tidak Bentrok]
        Model->>DB: 7a. INSERT INTO jadwal_praktikum & kelompok_mhs
        DB-->>Model: 8a. DB Result Success
        Model-->>Controller: 9a. Return status true
        Controller-->>View: 10a. Render Sukses & Refresh Tabel
        View-->>Admin: 11a. Tampilkan Pesan Sukses Jadwal Tersimpan
    else [Jadwal Bentrok]
        Model-->>Controller: 7b. Return Error 'Jadwal Lab/Aslab Bentrok'
        Controller-->>View: 8b. Render Pesan Peringatan
        View-->>Admin: 9b. Tampilkan Peringatan Jadwal Bentrok
    end
```

---

### **SD-04: Sequence Diagram — Buka Sesi Presensi Praktikum**

```mermaid
sequenceDiagram
    actor Pengajar as Dosen / Asisten Praktikum
    participant View as View (sesi_praktikum.php)
    participant Controller as PresensiController
    participant Model as SesiModel
    participant DB as Database MySQL

    Pengajar->>View: 1. Pilih Sesi Lab Hari Ini & Klik 'Buka Sesi Presensi'
    View->>Controller: 2. POST /sesi/buka {jadwal_id}
    Controller->>Model: 3. openSession(jadwal_id, jam_mulai)
    Model->>DB: 4. UPDATE sesi_praktikum SET status='AKTIF', jam_mulai=NOW()
    DB-->>Model: 5. DB Result Success
    Model-->>Controller: 6. Return Session Status 'AKTIF'
    Controller-->>View: 7. Render Monitoring Realtime
    View-->>Pengajar: 8. Tampilkan Antarmuka Monitoring Realtime Sesi Lab
```

---

### **SD-05: Sequence Diagram — Presensi Mandiri Praktikan**

```mermaid
sequenceDiagram
    actor Mhs as Mahasiswa Praktikan
    participant View as View (presensi_mandiri.php)
    participant Controller as PresensiController
    participant Model as PresensiModel
    participant DB as Database MySQL

    Mhs->>View: 1. Buka Menu Presensi Hari Ini & Klik 'Presensi Sekarang'
    View->>Controller: 2. POST /presensi/store {mhs_id, sesi_id}
    Controller->>Model: 3. checkTolerance(sesi_id, waktu_presensi)
    Model->>DB: 4. SELECT status, jam_mulai, toleransi FROM sesi_praktikum
    DB-->>Model: 5. DB Result Toleransi
    
    alt [Dalam Waktu Toleransi]
        Model->>DB: 6a. INSERT INTO presensi (status='HADIR')
        DB-->>Model: 7a. DB Result Success
        Model-->>Controller: 8a. Return status HADIR
        Controller-->>View: 9a. Render Konfirmasi Hadir
        View-->>Mhs: 10a. Tampilkan Status Kehadiran 'HADIR'
    else [Melewati Waktu Toleransi]
        Model->>DB: 6b. INSERT INTO presensi (status='TERLAMBAT')
        DB-->>Model: 7b. DB Result Success
        Model-->>Controller: 8b. Return status TERLAMBAT
        Controller-->>View: 9b. Render Konfirmasi Terlambat
        View-->>Mhs: 10b. Tampilkan Status Kehadiran 'TERLAMBAT'
    end
```

---

### **SD-06: Sequence Diagram — Tutup Sesi Presensi Praktikum & Auto-Alpha**

```mermaid
sequenceDiagram
    actor Pengajar as Dosen / Asisten Praktikum
    participant View as View (sesi_praktikum.php)
    participant Controller as PresensiController
    participant Model as PresensiModel
    participant DB as Database MySQL

    Pengajar->>View: 1. Klik Tombol 'Tutup Sesi Presensi'
    View->>Controller: 2. POST /sesi/tutup {sesi_id}
    Controller->>Model: 3. closeSession(sesi_id)
    Model->>DB: 4. UPDATE sesi_praktikum SET status='SELESAI'
    Controller->>Model: 5. runAutoAlpha(sesi_id)
    Model->>DB: 6. INSERT INTO presensi (status='ALPHA') SELECT mhs_id WHERE not_presenced
    DB-->>Model: 7. DB Result Success
    Model-->>Controller: 8. Return Summary Rekap Sesi
    Controller-->>View: 9. Render Ringkasan Rekap
    View-->>Pengajar: 10. Tampilkan Ringkasan Rekapitulasi Presensi Sesi Lab
```

---

### **SD-07: Sequence Diagram — Ubah Status Kehadiran Praktikum**

```mermaid
sequenceDiagram
    actor Pengajar as Dosen / Asisten Praktikum
    participant View as View (detail_presensi.php)
    participant Controller as PresensiController
    participant Model as PresensiModel
    participant DB as Database MySQL

    Pengajar->>View: 1. Pilih Mhs, Ubah Status (Izin/Sakit/Hadir) & Isi Alasan
    Pengajar->>View: 2. Klik 'Simpan Perubahan'
    View->>Controller: 3. POST /presensi/update {presensi_id, status_baru, alasan}
    Controller->>Controller: 4. validateReason(alasan)
    Controller->>Model: 5. updateStatus(presensi_id, status_baru, alasan)
    Model->>DB: 6. UPDATE presensi SET status=?, keterangan=? WHERE id=?
    DB-->>Model: 7. DB Result Success
    Model-->>Controller: 8. Return status true
    Controller-->>View: 9. Render Notifikasi Berhasil
    View-->>Pengajar: 10. Tampilkan Notifikasi Perubahan Presensi Berhasil
```

---

### **SD-08: Sequence Diagram — Rekap Presensi & Cetak Laporan Praktikum PDF**

```mermaid
sequenceDiagram
    actor Pengguna as Pengguna (Admin/Dosen/Aslab)
    participant View as View (rekap_praktikum.php)
    participant Controller as RekapController
    participant PDFEngine as PDFEngine (Dompdf)
    participant DB as Database MySQL

    Pengguna->>View: 1. Pilih Filter & Klik 'Tampilkan Rekap' / 'Cetak PDF'
    View->>Controller: 2. GET /rekap/generate {filter}
    Controller->>DB: 3. SELECT mhs, count(hadir), count(alpha), persentase FROM presensi
    DB-->>Controller: 4. DB Result Data Rekap
    
    alt [Aksi Cetak PDF]
        Controller->>PDFEngine: 5a. renderPDF(rekapData)
        PDFEngine-->>Controller: 6a. Stream File PDF Laporan
        Controller-->>View: 7a. Download PDF Stream
        View-->>Pengguna: 8a. Download Berkas PDF Laporan Praktikum
    else [Aksi Tampilkan di Layar]
        Controller-->>View: 5b. Render Data Rekap ke View
        View-->>Pengguna: 6b. Tampilkan Tabel Rekapitulasi & Persentase Kehadiran
    end
```
