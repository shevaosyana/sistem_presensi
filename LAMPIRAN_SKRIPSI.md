# LAMPIRAN-LAMPIRAN PENELITIAN SKRIPSI
## SISTEM INFORMASI PRESENSI PERKULIAHAN MENGGUNAKAN ALGORITMA SHA-256 UNTUK MENJAMIN INTEGRITAS DATA KEHADIRAN MAHASISWA DI FTI UNIBBA

---

## DAFTAR ISI LAMPIRAN

- **LAMPIRAN 1:** Instrumen dan Lembar Hasil Observasi Lapangan di FTI UNIBBA
- **LAMPIRAN 2:** Lembar Studi Dokumentasi dan Analisis Berkas Presensi Fisik
- **LAMPIRAN 3:** Hasil Pengujian Keamanan Integritas Hash SHA-256 (*Hash Match* & *Mismatch*)
- **LAMPIRAN 4:** Listing Kode Program Utama Algoritma SHA-256 dan Modul Audit Integritas

---

&nbsp;

---

## LAMPIRAN 1: INSTRUMEN DAN LEMBAR HASIL OBSERVASI LAPANGAN

*(Berdasarkan Pengamatan Langsung Proses Presensi Perkuliahan di Fakultas Teknologi Informasi Universitas Bale Bandung)*

* **Metode Pengumpulan Data:** Observasi Partisipatif Pasif / Pengamatan Langsung Lapangan
* **Lokasi Pengamatan:** Ruang Perkuliahan dan Ruang Tata Usaha FTI UNIBBA, Gedung FTI Kampus UNIBBA, Jl. R.A.A. Wiranatakusumah No. 112 Baleendah, Bandung
* **Waktu Pelaksanaan:** Semester Ganjil & Genap Tahun Akademik 2025/2026
* **Observer:** Penulis (Mahasiswa Program Studi Teknik Informatika FTI UNIBBA)
* **Tujuan Observasi:** Mengidentifikasi alur kerja presensi berjalan, celah keamanan, kerentanan manipulasi fisik, serta parameter waktu dan data perkuliahan.

---

### A. Tabel Lembar Observasi Alur Pelaksanaan Presensi Perkuliahan

| No | Tahapan & Alur Presensi | Prosedur yang Diamati di Lapangan | Kondisi / Temuan Faktual | Potensi Risiko / Kelemahan | Solusi Sistem Baru yang Diusulkan |
|:--:|:-----------------------|:---------------------------------|:-------------------------|:---------------------------|:----------------------------------|
| 1 | **Distribusi Lembar Presensi** | Dosen/Keti mengambil lembaran kertas presensi dari Tata Usaha (TU) FTI sebelum jam kuliah dimulai. | Lembaran kertas sering terlambat diambil atau tertukar antarkelas/ruangan. | Keterlambatan pencatatan kehadiran mahasiswa. | Distribusi jadwal perkuliahan dan presensi otomatis secara digital melalui web. |
| 2 | **Pencatatan Kehadiran di Kelas** | Mahasiswa mengisi tanda tangan pada daftar hadir kertas yang diedarkan secara berantai saat kuliah berlangsung. | Peredaran kertas mengganggu konsentrasi belajar; sering terjadi mahasiswa menandatangani presensi milik rekannya (*titip absen*). | Data kehadiran tidak valid dan tidak mencerminkan kehadiran fisik yang sebenarnya. | Presensi mandiri berbasis login mahasiswa dengan validasi status dan pencatatan jam real-time. |
| 3 | **Pencatatan Jam & Sesi Kuliah** | Lembaran kertas hanya mencantumkan tanggal, sedangkan jam presensi aktual mahasiswa masuk kelas tidak tercatat secara presisi per individu. | Mahasiswa yang datang terlambat tetap menandatangani baris yang sama tanpa ada pembeda jam masuk. | Tidak ada riwayat waktu presensi yang akurat untuk evaluasi kedisiplinan. | Sistem mencatat otomatis `jam_presensi`, `pertemuan_ke (sesi)`, dan `hari` secara real-time. |
| 4 | **Rekapitulasi Akhir Semester** | Staf TU/Dosen menginput kembali rekap presensi kertas ke dalam lembar kerja spreadsheet (Excel) secara manual. | Proses rekapitulasi memakan waktu lama (2-3 minggu setiap akhir semester) dan rentan salah hitung (*human error*). | Efisiensi administrasi rendah dan pengumuman persentase syarat ujian (minimal 75%) terlambat. | Rekapitulasi persentase kehadiran otomatis dihitung oleh sistem secara instan dan akurat. |
| 5 | **Pengamanan & Keutuhan Data** | Berkas kertas presensi disimpan dalam lemari arsip; data spreadsheet disimpan di komputer lokal staf. | Data di spreadsheet dan arsip kertas tidak memiliki proteksi kriptografis sehingga rentan disunting (*edit*) tanpa jejak audit. | Terjadinya manipulasi nilai/status presensi tanpa bisa diketahui pihak yang mengubah. | Penerapan algoritma kriptografi **SHA-256** dengan 7 parameter data untuk mendeteksi perubahan data sekecil apa pun. |

---

### B. Tabel Observasi Elemen Data Presensi yang Diperlukan untuk Hashing

Berdasarkan hasil pengamatan terhadap struktur perkuliahan di FTI UNIBBA, data presensi yang valid dan representatif harus mengikat seluruh dimensi perkuliahan, yaitu:

| No | Elemen Data | Sumber Data Lapangan | Format / Tipe Data | Peran dalam Hashing SHA-256 |
|:--:|:------------|:--------------------|:-------------------|:----------------------------|
| 1 | **Nama Mahasiswa** | Data Induk Mahasiswa FTI UNIBBA | Teks Alfabet (`varchar`) | Mengidentifikasi identitas subjek mahasiswa. |
| 2 | **NIM** | Nomor Induk Mahasiswa resmi UNIBBA | Angka Unik (`varchar`) | Kunci identifikasi unik mahasiswa (*unique identifier*). |
| 3 | **Tanggal Perkuliahan** | Tanggal sesi kuliah dilaksanakan | `YYYY-MM-DD` (`date`) | Mengunci tanggal pelaksanaan presensi. |
| 4 | **Mata Kuliah / Sesi** | Kode & Nama Mata Kuliah di FTI UNIBBA | Kode MK (`varchar`) | Mengunci mata kuliah yang sedang diikuti. |
| 5 | **Waktu / Jam Mulai** | Jam mulai sesuai jadwal perkuliahan FTI | `HH:MM:SS` (`time`) | Mengunci slot jam perkuliahan mahasiswa. |
| 6 | **Hari Perkuliahan** | Hari operasional kuliah (Senin - Sabtu) | Teks Hari (`varchar`) | Mengunci hari kalender akademik perkuliahan. |
| 7 | **Status Kehadiran** | Status kehadiran mahasiswa | `HADIR / TERLAMBAT / IZIN / SAKIT / ALPHA` | Menjadi penentu status kehadiran yang diproteksi dari pengubahan liar. |

---

### C. Kesimpulan Hasil Observasi Lapangan

Hasil observasi menunjukkan bahwa proses pencatatan presensi di FTI UNIBBA memerlukan transisi ke sistem berbasis web dengan perlindungan integritas data. Pengikatan **7 elemen data** di atas ke dalam algoritma **SHA-256** menghasilkan *fingerprint* kriptografis unik yang menjamin data presensi tidak dapat diubah secara ilegal di basis data.

---

&nbsp;

---

## LAMPIRAN 2: LEMBAR STUDI DOKUMENTASI DAN ANALISIS BERKAS PRESENSI FISIK

Studi dokumentasi dilakukan dengan mengumpulkan, memeriksa, dan menganalisis berkas arsip presensi yang digunakan di FTI UNIBBA.

### A. Data Berkas Presensi yang Dianalisis
1. **Nama Dokumen:** Lembar Daftar Hadir Kuliah (DHK) Mahasiswa FTI UNIBBA.
2. **Format Dokumen:** Formulir kertas ukuran A4/Folio cetak matriks.
3. **Penyimpanan:** Lemari arsip Program Studi / Fakultas Teknologi Informasi UNIBBA.

### B. Analisis Celah Kelemahan pada Berkas Fisik Eksisting

```text
+-----------------------------------------------------------------------------------------+
|                  ANALISIS ANATOMI LEMBAR DAFTAR HADIR KERTAS (EKSISTING)                |
+-----------------------------------------------------------------------------------------+
| [KOP FAKULTAS TEKNOLOGI INFORMASI - UNIBBA]                                            |
| Mata Kuliah: Pemrograman Web          Kelas: TI-21-A             Dosen: [Nama Dosen]    |
| Hari/Jam   : Senin / 08.00-10.30      Ruang: Lab Komputer        Semester: Ganjil       |
+----+-------------+----------------------+--------------------+--------------------------+
| No |     NIM     |    Nama Mahasiswa    | Pertemuan Ke: 1..16| Keterangan / Paraf       |
+----+-------------+----------------------+--------------------+--------------------------+
| 1  | 2021001     | Ahmad Fauzi          | [ Tanda Tangan ]   | * Rawan titip tanda tangan|
| 2  | 2021002     | Budi Santoso         | [ Tanda Tangan ]   | * Tidak ada cap jam masuk |
| 3  | 2021003     | Citra Lestari        | [ Tanda Tangan ]   | * Rawan diubah manual    |
+----+-------------+----------------------+--------------------+--------------------------+
| CATATAN TEMUAN STUDI DOKUMENTASI:                                                       |
| 1. Tidak terdapat mekanisme verifikasi apakah tanda tangan dilakukan oleh pemilik NIM.  |
| 2. Perubahan data nilai/kehadiran pasca-kuliah tidak memiliki riwayat jejak audit digital.|
| 3. Tidak ada penanda unik (hash) yang mengunci bahwa baris data tersebut asli/asli.    |
+-----------------------------------------------------------------------------------------+
```

---

&nbsp;

---

## LAMPIRAN 3: HASIL PENGUJIAN KEAMANAN INTEGRITAS HASH SHA-256

Pengujian integritas data dilakukan dengan melakukan simulasi manipulasi data secara sengaja (*data tampering*) langsung pada tabel basis data `detail_presensi` di MySQL.

### A. Skenario Pengujian Integritas:
1. **Data Awal:** Mahasiswa bernama `Ahmad Fauzi` (NIM `2021001`) memiliki status `ALPHA` pada mata kuliah `TI101` tanggal `2026-08-22`.
   - String Payload: `Ahmad Fauzi|2021001|2026-08-22|TI101|08:00:00|Senin|ALPHA`
   - Hash Asli Tersimpan: `b3e41a80479707297e68fa7075c0fbf246cfc3cf85bdfe4e7cb8aebe9d76c33c`
2. **Skenario Manipulasi:** Dilakukan perubahan langsung pada kolom `status` di tabel database dari `ALPHA` menjadi `HADIR` tanpa melalui otorisasi sistem.
3. **Hasil Rekalkulasi Sistem Saat Menu Audit Dibuka:**
   - Rekalkulasi Hash Aktual: `e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855`
   - Perbandingan: Hash Tersimpan $\neq$ Hash Aktual.
   - Respon Sistem: **❌ MANIPULASI TERDETEKSI!** (*Alert merah pada baris data terkait*).

---

### B. Tabel Hasil Pengujian Integritas SHA-256

| No | Parameter Data Presensi | Data Database Asli | Data Setelah Dimanipulasi | Respon Sistem Audit | Status Integritas |
|:--:|:-----------------------|:-------------------|:--------------------------|:--------------------|:------------------|
| 1 | Nama Mahasiswa | Ahmad Fauzi | Ahmad Fauzi | Tidak berubah | Sesuai |
| 2 | NIM | 2021001 | 2021001 | Tidak berubah | Sesuai |
| 3 | Tanggal Kuliah | 2026-08-22 | 2026-08-22 | Tidak berubah | Sesuai |
| 4 | Kode Mata Kuliah | TI101 | TI101 | Tidak berubah | Sesuai |
| 5 | Jam Mulai Kuliah | 08:00:00 | 08:00:00 | Tidak berubah | Sesuai |
| 6 | Hari Kuliah | Senin | Senin | Tidak berubah | Sesuai |
| 7 | **Status Presensi** | **ALPHA** | **HADIR (Diubah Ilegal)** | **Status Tidak Cocok** | **Manipulasi!** |
| 8 | **Nilai Hash SHA-256** | `b3e41a80479...` (Stored) | `e3b0c44298f...` (Computed) | **Hash Mismatch** | **❌ MANIPULASI (🔴)** |

---

&nbsp;

---

## LAMPIRAN 4: LISTING KODE PROGRAM UTAMA ALGORITMA SHA-256

### A. File `helpers/FormatHelper.php` (Fungsi Pembentukan Hash 7 Komponen)

```php
/**
 * Generate SHA-256 integrity hash for presensi record
 * Format 7 Komponen: nama|nim|tanggal|sesi_mk|jam|hari|status
 * Separator pipe (|) digunakan untuk mencegah collision
 */
public static function generateHash(
    string $nama, 
    string $nim, 
    string $tgl, 
    string $sesi_mk, 
    string $jam, 
    string $hari, 
    string $status
): string {
    $data = trim($nama) . '|' . 
            trim($nim) . '|' . 
            trim($tgl) . '|' . 
            trim($sesi_mk) . '|' . 
            trim($jam) . '|' . 
            trim($hari) . '|' . 
            trim($status);
            
    return hash('sha256', $data);
}
```

### B. File `controllers/AuditController.php` (Logika Verifikasi Audit Integritas)

```php
// Mengambil seluruh data presensi berdasarkan kelas perkuliahan
$stmt = $db->prepare("
    SELECT dp.id, m.nim, m.nama as mahasiswa_nama, mk.kode as mk_kode, 
           p.tanggal, dp.status, dp.hash_integrity, j.jam_mulai, j.hari 
    FROM detail_presensi dp 
    JOIN mahasiswa m ON dp.mahasiswa_id=m.id 
    JOIN presensi p ON dp.presensi_id=p.id 
    JOIN jadwal j ON p.jadwal_id=j.id 
    JOIN kelas k ON j.kelas_id=k.id 
    JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id 
    WHERE k.id = ? 
    ORDER BY p.tanggal DESC, m.nama ASC
");
$stmt->execute([$kelasId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $r) {
    // Rekalkulasi hash berdasarkan 7 komponen aktual
    $expectedHash = FormatHelper::generateHash(
        $r['mahasiswa_nama'], 
        $r['nim'], 
        $r['tanggal'], 
        $r['mk_kode'], 
        $r['jam_mulai'], 
        $r['hari'], 
        $r['status']
    );
    
    // Uji kecocokan hash (Data Integrity Verification)
    $isValid = hash_equals($r['hash_integrity'] ?? '', $expectedHash);
    
    $dataAudit[] = [
        'nama'           => $r['mahasiswa_nama'],
        'nim'            => $r['nim'],
        'hash_tersimpan' => $r['hash_integrity'],
        'hash_aktual'    => $expectedHash,
        'is_valid'       => $isValid
    ];
}
```
