# SKRIPSI LENGKAP: SISTEM INFORMASI PRESENSI MAHASISWA BERBASIS SHA-256

---

## ABSTRAK

Presensi mahasiswa merupakan bagian penting dalam administrasi akademik karena menghasilkan data kehadiran yang digunakan dalam pengelolaan dan evaluasi kegiatan perkuliahan. Berdasarkan hasil observasi dan wawancara di Fakultas Teknologi Informasi Universitas Bale Bandung, proses pencatatan presensi masih dilakukan secara manual sehingga belum didukung mekanisme otomatis untuk memeriksa perubahan terhadap data presensi yang telah tersimpan. Kondisi tersebut menunjukkan perlunya mekanisme yang dapat membantu menjaga dan memverifikasi integritas data presensi. Penelitian ini bertujuan membangun sistem informasi presensi mahasiswa yang mampu menghasilkan nilai hash unik untuk setiap data kehadiran, menerapkan mekanisme verifikasi integritas menggunakan algoritma SHA-256, serta menguji keandalan sistem dalam mendeteksi perubahan data yang tidak sah pada basis data.

Penelitian ini menggunakan metode Research and Development (R&D) dengan metode pengembangan sistem Waterfall. Pengumpulan data dilakukan melalui observasi, wawancara, dan studi literatur. Sistem dikembangkan menggunakan bahasa pemrograman PHP pada lingkungan Laragon dengan sistem manajemen basis data MySQL. Algoritma SHA-256 diterapkan untuk menghasilkan nilai hash berdasarkan komponen data presensi yang meliputi NIM, kode mata kuliah, tanggal, dan status kehadiran. Nilai hash tersebut disimpan sebagai nilai pembanding untuk proses verifikasi integritas data. Proses verifikasi dilakukan dengan menghitung kembali nilai hash berdasarkan data presensi yang tersimpan, kemudian membandingkannya dengan nilai hash yang telah disimpan sebelumnya. Jika nilai hash sama, data dinyatakan valid, sedangkan jika nilai hash berbeda, sistem memberikan indikasi bahwa data telah mengalami perubahan. Pengujian sistem dilakukan menggunakan Black Box Testing dan pengujian audit hash melalui beberapa skenario perubahan pada data presensi.

Hasil penelitian menunjukkan bahwa sistem informasi presensi mahasiswa berhasil menghasilkan nilai hash SHA-256 unik dengan panjang 64 karakter heksadesimal untuk setiap data kehadiran yang disimpan. Mekanisme verifikasi integritas data berhasil diterapkan melalui perbandingan nilai hash yang tersimpan dengan hasil perhitungan ulang. Sistem mampu menentukan kondisi data berdasarkan kesesuaian nilai hash. Hasil pengujian perubahan data presensi menunjukkan bahwa seluruh skenario perubahan yang diuji berhasil dideteksi dengan tingkat keberhasilan deteksi sebesar 100%. Dengan demikian, implementasi algoritma SHA-256 berhasil digunakan sebagai mekanisme proteksi integritas data presensi mahasiswa melalui proses pembentukan dan perbandingan nilai hash untuk membantu mendeteksi perubahan data yang tidak sah pada basis data.

**Kata kunci:** audit hash, integritas data, presensi mahasiswa, SHA-256, sistem informasi.

---

## ABSTRACT

Student attendance is an important part of academic administration because it produces attendance data used in managing and evaluating academic activities. Based on observations and interviews conducted at the Faculty of Information Technology, Universitas Bale Bandung, the attendance recording process is still carried out manually and is not supported by an automatic mechanism for detecting changes to stored attendance data. This condition indicates the need for a mechanism that can help maintain and verify the integrity of attendance data. This study aims to develop a student attendance information system capable of generating a unique hash value for each attendance record, implementing a data integrity verification mechanism using the SHA-256 algorithm, and testing the system's reliability in detecting unauthorized changes to the database.

This study uses the Research and Development (R&D) method with the Waterfall system development method. Data collection was conducted through observation, interviews, and literature studies. The system was developed using the PHP programming language in the Laragon environment with MySQL as the database management system. The SHA-256 algorithm is implemented to generate hash values based on attendance data components consisting of student identification number (NIM), course code, date, and attendance status. The generated hash value is stored as a reference value for data integrity verification. The verification process is performed by recalculating the hash value based on the stored attendance data and comparing it with the previously stored hash value. If the hash values are identical, the data is considered valid, whereas if the hash values differ, the system indicates that the data has been modified. System testing was conducted using Black Box Testing and hash audit testing through several scenarios involving changes to attendance data.

The results show that the developed student attendance information system successfully generates a unique SHA-256 hash value with a length of 64 hexadecimal characters for each stored attendance record. The data integrity verification mechanism was successfully implemented by comparing the stored hash value with the recalculated hash value. The system is capable of determining the validity of attendance data based on hash value consistency. The results of attendance data modification testing show that all tested modification scenarios were successfully detected, achieving a detection success rate of 100%. Therefore, the implementation of the SHA-256 algorithm can be used as a data integrity protection mechanism for student attendance through the generation and comparison of hash values to help detect unauthorized changes to attendance data stored in the database.

**Keywords:** hash audit, information system, SHA-256, student attendance, data integrity.

---

## KATA PENGANTAR

Puji syukur penulis panjatkan ke hadirat Allah SWT atas segala rahmat dan karunia-Nya, sehingga penulis dapat menyelesaikan laporan skripsi yang berjudul **“Implementasi Algoritma SHA-256 sebagai Mekanisme Proteksi Integritas Data Presensi Mahasiswa di Fakultas XYZ”** dengan baik.

Laporan skripsi ini disusun sebagai salah satu syarat untuk memperoleh gelar Sarjana pada Program Studi Teknik Informatika, Fakultas Teknologi Informasi, Universitas Bale Bandung. Penelitian ini dilaksanakan sebagai upaya untuk menerapkan algoritma SHA-256 sebagai mekanisme proteksi integritas data presensi mahasiswa, khususnya dalam membantu mendeteksi perubahan atau manipulasi terhadap data presensi yang tersimpan pada sistem.

Dalam proses penyusunan dan penyelesaian skripsi ini, penulis memperoleh banyak bantuan, bimbingan, dukungan, serta motivasi dari berbagai pihak. Oleh karena itu, pada kesempatan ini penulis menyampaikan ucapan terima kasih kepada:
1. Allah SWT atas segala rahmat, karunia, kesehatan, serta kemudahan yang diberikan sehingga penulis dapat menyelesaikan skripsi ini dengan baik.
2. Bapak Yudi Herdiana, S.T., M.T. selaku Dekan Fakultas Teknologi Informasi Universitas Bale Bandung yang telah memberikan dukungan dan fasilitas selama penulis menempuh pendidikan.
3. Bapak Yusuf Muharam, S.Kom., M.Kom. selaku Ketua Program Studi Teknik Informatika Fakultas Teknologi Informasi Universitas Bale Bandung yang telah memberikan arahan dan dukungan akademik selama masa perkuliahan.
4. Bapak Yusuf Muharam, S.Kom., M.Kom. selaku dosen pembimbing utama Fakultas Teknologi Informasi Universitas Bale Bandung yang telah meluangkan waktu, tenaga, dan pikiran dalam memberikan bimbingan, arahan, serta masukan yang sangat berharga selama proses penelitian dan penyusunan skripsi ini.
5. Bapak Cecep Suwanda, S.Si., M.Kom. selaku dosen pendamping Fakultas Teknologi Informasi Universitas Bale Bandung yang telah memberikan saran, motivasi, dan bimbingan selama proses penyusunan skripsi.
6. Seluruh dosen dan staff Fakultas Teknologi Informasi Universitas Bale Bandung yang telah memberikan ilmu pengetahuan, bantuan, serta pelayanan akademik kepada penulis selama menempuh pendidikan.
7. Kedua orang tua tercinta yang senantiasa memberikan doa, kasih sayang, dukungan moral maupun material, serta motivasi yang tiada henti kepada penulis.
8. Teman-teman seperjuangan dan seluruh pihak yang tidak dapat disebutkan satu per satu yang telah memberikan bantuan, dukungan, dan semangat dalam proses penyusunan skripsi ini.

Penulis menyadari bahwa laporan skripsi ini masih memiliki keterbatasan dan masih jauh dari sempurna. Oleh karena itu, penulis mengharapkan kritik dan saran yang membangun sebagai bahan evaluasi dan penyempurnaan penelitian di masa mendatang.

Bandung, Agustus 2026

**Aldit Sheva Osyana**  
NIM: 301220075

---

## DAFTAR ISI

* [ABSTRAK](#abstrak)
* [ABSTRACT](#abstract)
* [KATA PENGANTAR](#kata-pengantar)
* [BAB I PENDAHULUAN](#bab-i-pendahuluan)
  * [1.1 Latar Belakang](#11-latar-belakang)
  * [1.2 Rumusan Masalah](#12-rumusan-masalah)
  * [1.3 Batasan Masalah](#13-batasan-masalah)
  * [1.4 Tujuan Penelitian](#14-tujuan-penelitian)
  * [1.5 Metodologi Penelitian](#15-metodologi-penelitian)
  * [1.6 Sistematika Penulisan](#16-sistematika-penulisan)
* [BAB II TINJAUAN PUSTAKA](#bab-ii-tinjauan-pustaka)
  * [2.1 Landasan Teori](#21-landasan-teori)
  * [2.2 Dasar Teori](#22-dasar-teori)
* [BAB III METODOLOGI PENELITIAN](#bab-iii-metodologi-penelitian)
  * [3.1 Kerangka Pikir](#31-kerangka-pikir)
  * [3.2 Deskripsi Tahapan Penelitian](#32-deskripsi)
* [BAB IV ANALISIS, PERANCANGAN DAN HASIL](#bab-iv-analisis-perancangan-dan-hasil)
  * [4.1 Analisis](#41-analisis)
  * [4.2 Perancangan](#42-perancangan)
* [BAB V IMPLEMENTASI DAN PENGUJIAN](#bab-v-implementasi-dan-pengujian)
  * [5.1 Implementasi](#51-implementasi)
  * [5.2 Pengujian](#52-pengujian)
* [BAB VI KESIMPULAN DAN SARAN](#bab-vi-kesimpulan-dan-saran)
  * [6.1 Kesimpulan](#61-kesimpulan)
  * [6.2 Saran](#62-saran)
* [DAFTAR PUSTAKA](#daftar-pustaka)

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang
Presensi mahasiswa merupakan komponen penting dalam administrasi akademik karena menjadi salah satu faktor utama dalam proses evaluasi dan kehadiran. Seiring kemajuan teknologi, banyak lembaga pendidikan beralih dari presensi manual ke sistem presensi digital untuk meningkatkan akurasi dan efisiensi data. Namun, pelaksanaan presensi manual masih memiliki beberapa kekurangan. Penggunaan tanda tangan pada kertas rentan terhadap kecurangan, seperti titip absen, dan membutuhkan waktu lama dalam proses rekapitulasi. Selain itu, pencatatan manual juga dapat mengakibatkan kehilangan data dan masalah pencatatan (Ridoh dkk., 2026).

Penelitian ini dilakukan di Fakultas Teknologi Informasi (FTI) Universitas Bale Bandung (UNIBBA), Kabupaten Bandung, Jawa Barat. FTI UNIBBA merupakan lingkungan akademik yang melaksanakan kegiatan perkuliahan dengan kebutuhan pencatatan kehadiran mahasiswa secara teratur untuk mendukung administrasi akademik. Berdasarkan hasil observasi dan wawancara yang dilakukan di FTI UNIBBA, proses pencatatan presensi mahasiswa pada lokasi penelitian masih dilakukan secara manual menggunakan media kertas.

Ketergantungan pada media kertas tersebut memicu sejumlah permasalahan mendasar dalam tata kelola presensi di FTI UNIBBA. Selain menimbulkan inefisiensi waktu rekapitulasi, risiko hilangnya dokumen, dan maraknya praktik titip absen, ancaman krusial juga ditemukan pada aspek keamanan data tersimpan. Belum tersedianya mekanisme jaminan integritas menyebabkan data presensi yang telah masuk ke basis data rentan terhadap pengubahan atau manipulasi secara tidak sah tanpa terdeteksi. Ketiadaan sistem verifikasi otomatis ini membuat pihak pengelola kesulitan memastikan keaslian record presensi secara cepat, sehingga keandalan data sebagai acuan penilaian kriteria kehadiran mahasiswa menjadi sulit dipertanggungjawabkan.

Guna mengatasi permasalahan keamanan dan keabsahan pencatatan presensi, sejumlah penelitian terdahulu telah berupaya mengembangkan sistem digital melalui berbagai pendekatan. (Ridoh dkk., 2026) mengembangkan sistem presensi berbasis QR Code dengan notifikasi real-time untuk menggantikan media kertas, namun penelitian ini belum membahas mekanisme untuk memastikan data yang telah tersimpan tidak mengalami perubahan. Dari aspek perlindungan data, (Adeniyi dkk., 2022) menerapkan kombinasi RSA, ElGamal, dan SHA-256 untuk pembentukan tanda tangan digital pada data sensitif, yang menunjukkan efektivitas fungsi hash dalam menjaga keaslian data, tetapi penerapannya masih bersifat umum dan belum diarahkan pada konteks presensi. Sementara itu, (Saha dkk., 2026) mengembangkan sistem ZenTap yang menggabungkan autentikasi ganda dengan SHA-256 pada jaringan blockchain untuk menjamin keutuhan data presensi, namun pendekatan ini bergantung pada infrastruktur blockchain yang kompleks dan hash yang dibentuk belum mencakup atribut perkuliahan secara lengkap seperti mata kuliah, sesi, tanggal, hari, dan jam. Berdasarkan uraian tersebut, terlihat adanya celah penelitian (*research gap*), yaitu belum tersedianya mekanisme audit integritas data presensi yang membentuk nilai hash dari kombinasi atribut presensi secara lengkap tanpa bergantung pada infrastruktur tambahan yang rumit. Oleh karena itu, penelitian ini menerapkan algoritma SHA-256 karena bersifat satu arah (*one-way function*) dan memiliki *avalanche effect* yang kuat, sehingga perubahan sekecil apa pun pada data masukan akan menghasilkan nilai hash yang berbeda secara drastis untuk memudahkan proses verifikasi keaslian data.

Risiko manipulasi basis data presensi pasca-pencatatan yang sulit teridentifikasi secara manual menjadikan pengembangan mekanisme validasi data secara independen dan hemat sumber daya ini sangat mendesak untuk diterapkan. Keunggulan utama yang ditawarkan terletak pada skema penguncian integritas data, di mana seluruh atribut transaksi perkuliahan meliputi NIM, Nama, Mata Kuliah, Sesi, Tanggal, Hari, hingga Jam digabungkan menggunakan delimiter khusus untuk menghasilkan satu nilai hash SHA-256 yang unik. Melalui pendekatan ini, sistem menghasilkan modul audit hash check yang mampu memberikan konfirmasi status Hash Match atau Hash Mismatch secara instan pada setiap record kehadiran. Penerapan skema ini diharapkan dapat meningkatkan transparansi tata kelola akademik, mencegah pengubahan data ilegal secara tersembunyi, serta mewujudkan proses verifikasi keaslian dokumen digital yang presisi tanpa ketergantungan pada pemeriksaan manual. Berdasarkan hal tersebut, penelitian ini bertujuan merancang dan mengimplementasikan algoritma SHA-256 sebagai fondasi proteksi integritas data presensi mahasiswa, yang dirumuskan dalam penelitian berjudul **“Implementasi Algoritma SHA-256 sebagai Mekanisme Proteksi Integritas Data Presensi Mahasiswa di Fakultas XYZ”**.

### 1.2 Rumusan Masalah
Berdasarkan latar belakang yang telah diuraikan, maka rumusan masalah dalam penelitian ini adalah sebagai berikut:
1. Bagaimana membangun sistem informasi presensi berbasis digital di FTI UNIBBA untuk mengatasi inefisiensi dan risiko kecurangan pada pencatatan manual?
2. Bagaimana mengimplementasikan algoritma SHA-256 pada data presensi guna menjamin integritas dan mencegah manipulasi data pada basis data?
3. Bagaimana efektivitas mekanisme audit hash check berbasis SHA-256 dalam mendeteksi perubahan data presensi secara otomatis?

### 1.3 Batasan Masalah
Agar penelitian ini lebih terarah dan mendalam, peneliti menetapkan batasan masalah sebagai berikut:
1. Implementasi algoritma keamanan difokuskan secara khusus pada penggunaan fungsi hash SHA-256 untuk memproteksi integritas data kehadiran.
2. Pengembangan sistem dilakukan menggunakan lingkungan Laragon dengan bahasa pemrograman PHP dan sistem manajemen basis data MySQL.
3. Objek data yang di-hash terbatas pada komponen data presensi mahasiswa yang meliputi NIM, kode mata kuliah, tanggal, dan status kehadiran.

### 1.4 Tujuan Penelitian
Tujuan yang ingin dicapai dalam penelitian ini adalah sebagai berikut:
1. Membangun sistem informasi presensi mahasiswa yang mampu menghasilkan nilai hash unik untuk setiap data kehadiran yang disimpan.
2. Menerapkan mekanisme verifikasi integritas data menggunakan algoritma SHA-256 guna memastikan keaslian data presensi di FTI UNIBBA.
3. Menguji keandalan sistem dalam mendeteksi perubahan data yang tidak sah pada basis data melalui perbandingan nilai hash secara sistematis.

### 1.5 Metodologi Penelitian
Metodologi penelitian dalam penelitian ini terdiri atas metode penelitian (R&D), metode pengumpulan data (observasi, wawancara, studi literatur), metode pengembangan sistem (Waterfall: Analisis, Desain, Pengodean, Pengujian, Pemeliharaan), dan metode pengujian (Black-Box Testing dan Audit Integritas Data SHA-256).

### 1.6 Sistematika Penulisan
Sistematika penulisan disusun ke dalam 6 bab mulai dari BAB I (Pendahuluan), BAB II (Tinjauan Pustaka), BAB III (Metodologi Penelitian), BAB IV (Analisis dan Perancangan), BAB V (Implementasi dan Pengujian), hingga BAB VI (Kesimpulan dan Saran).

---

## BAB II: TINJAUAN PUSTAKA

### 2.1 Landasan Teori (Penelitian Terdahulu)
Berisi perbandingan dengan 8 jurnal nasional/internasional relevan:
1. Prasetya Adi & Ardhianto (2026) – SHA-256 untuk PDF Jurnal Ilmiah.
2. Umarjati & Wibowo (2020) – JWT HMAC SHA-256 Presensi WFH.
3. Dalimunthe & Ikhwan (2026) – Hashing SHA-256 Arsip Elektronik.
4. Firmansyah dkk. (2026) – Integrasi SHA-256 dan AES Kepegawaian.
5. Abdullah (2025) – Blockchain Hyperledger Fabric Data Akademik.
6. Utama dkk. (2023) – AES-256 CBC, Base64, dan SHA-256 Ujian Online.
7. Surya dkk. (2025) – Hybrid AES-256 & SHA-256 Cloud Document.
8. Hutagalung dkk. (2023) – SHA-256 dan RSA Digital Signature Dokumen Kelulusan.

### 2.2 Dasar Teori
Membahas konsep Sistem Informasi, Sistem Presensi, CIA Triad & Kriptografi Keamanan Data, Algoritma SHA-256 (Avalanche Effect & Collision Resistance), PHP, Arsitektur MVC, MySQL, Laragon, Integritas Data, Model Waterfall, UML (Use Case, Activity, Sequence, Class Diagram), Black Box Testing, dan User Acceptance Testing (UAT).

---

## BAB III: METODOLOGI PENELITIAN

### 3.1 Kerangka Pikir
Kerangka alur pikir penelitian mulai dari identifikasi masalah integritas data di FTI UNIBBA, pengumpulan data, analisis kebutuhan, perancangan sistem, implementasi SHA-256, pengujian sistem, hingga evaluasi dan penyusunan laporan.

### 3.2 Deskripsi Tahapan Penelitian
Menjelaskan secara operasional 10 tahapan penelitian riil dari 3.2.1 Identifikasi Permasalahan sampai 3.2.10 Laporan.

---

## BAB IV: ANALISIS, PERANCANGAN DAN HASIL

### 4.1 Analisis
Memetakan masalah eksisting (Tabel 4.1), analisis perangkat lunak (Tabel 4.2), analisis pengguna multi-role (Tabel 4.3), antarmuka, fitur-fitur (Tabel 4.4), alur data (Tabel 4.5), dan analisis biaya (Tabel 4.6).

### 4.2 Perancangan
1. **Pemodelan UML**: Use Case Diagram (3 aktor: Admin, Dosen, Mahasiswa), 9 Activity Diagram, 6 Sequence Diagram, Class Diagram (14 entitas), dan Entity Relationship Diagram (ERD).
2. **Struktur Basis Data**: 14 tabel lengkap (roles, user, program_studi, mahasiswa, dosen, tahun_akademik, semester, mata_kuliah, ruangan, kelas, kelas_anggota, jadwal, presensi, detail_presensi dengan kolom `hash_integrity VARCHAR(64)`).
3. **Desain Wireframe & Mockup Antarmuka**: 5 wireframe dan 13 mockup antarmuka pengguna.

---

## BAB V: IMPLEMENTASI DAN PENGUJIAN

### 5.1 Implementasi
1. **Listing Program Utama**: AuthController.php, PresensiController.php (Submit & Auto-Alpha), AuditController.php, FormatHelper.php (SHA-256 hashing), Database.php (PDO Singleton), index.php (Front Controller), dan LaporanController.php.
2. **Formulasi Matematis**:
   $$\Delta t = \frac{T_{\text{submit}} - T_{\text{mulai}}}{60} \text{ (menit)}$$
   $$\text{Hash} = \text{SHA256}(\text{Nama} \parallel \text{NIM} \parallel \text{Tanggal} \parallel \text{Kode\_MK} \parallel \text{Jam\_Mulai} \parallel \text{Hari} \parallel \text{Status})$$
3. **Spesifikasi & Instalasi Sistem**: Spesifikasi server/klien serta prosedur deployment lokal.

### 5.2 Pengujian Sistem
1. **5.2.1 Black-Box Testing**: 10 skenario pengujian fungsionalitas dengan hasil 100% VALID (Tabel 5.4).
2. **5.2.2 Simulation Testing**: 5 skenario simulasi visual lengkap dengan tangkapan layar (Gambar 5.6 – 5.25):
   * Simulation Testing Login
   * Simulation Testing Presensi Mandiri Mahasiswa
   * Simulation Testing Penutupan Sesi & Auto-Alpha
   * Simulation Testing Verifikasi Integritas Data
   * Simulation Testing Serangan Manipulasi Basis Data
3. **5.2.3 Pengujian UAT (User Acceptance Testing)**: 8 skenario pengujian operasional oleh Admin, Dosen, dan Mahasiswa dengan hasil **DITERIMA** (Tabel 5.5).

---

## BAB VI: KESIMPULAN DAN SARAN

### 6.1 Kesimpulan
1. Sistem informasi presensi mahasiswa berhasil dibangun dan mampu menghasilkan nilai hash unik untuk setiap data kehadiran yang disimpan. Sistem secara otomatis membangkitkan nilai hash SHA-256 berdasarkan data presensi mahasiswa dan menyimpan nilai hash tersebut sebagai bagian dari data presensi. Nilai hash yang dihasilkan memiliki panjang 64 karakter heksadesimal sehingga setiap data kehadiran memiliki nilai hash yang dapat digunakan sebagai identitas untuk pemeriksaan integritas data.
2. Mekanisme verifikasi integritas data menggunakan algoritma SHA-256 berhasil diterapkan untuk memastikan keaslian data presensi di FTI UNIBBA. Sistem melakukan perhitungan ulang nilai hash dari data presensi, kemudian membandingkannya dengan nilai hash yang tersimpan di basis data. Hasil perbandingan digunakan untuk menentukan kondisi data. Jika nilai hash sesuai, data dinyatakan valid. Jika nilai hash berbeda, sistem memberikan indikasi bahwa data telah mengalami perubahan.
3. Sistem berhasil menguji keandalan algoritma SHA-256 dalam mendeteksi perubahan data yang tidak sah pada basis data melalui perbandingan nilai hash secara sistematis. Berdasarkan hasil pengujian manipulasi data presensi yang dilakukan pada basis data MySQL, perubahan pada data presensi dapat menyebabkan perbedaan nilai hash sehingga sistem mampu mendeteksi perubahan tersebut. Hasil pengujian menunjukkan bahwa sistem dapat mendeteksi seluruh skenario perubahan data yang diuji dengan tingkat keberhasilan deteksi sebesar 100%.

### 6.2 Saran
1. **Pengembangan Mekanisme Keamanan Selain SHA-256**: Penelitian selanjutnya disarankan mengombinasikan SHA-256 dengan mekanisme seperti HMAC, Digital Signature, atau algoritma kriptografi lain.
2. **Pengujian Sistem pada Lingkungan Server dengan Beban Tinggi**: Pengujian selanjutnya disarankan dilakukan pada lingkungan cloud/server produksi dengan load concurrent tinggi.
3. **Perluasan Komponen Data dalam Proses Hashing**: Memperluas variabel hashing mencakup identitas perangkat, sesi, atau lokasi presensi.
4. **Pengembangan Mekanisme Pencegahan Kecurangan pada Proses Presensi**: Menambahkan Device Fingerprinting, Geofencing GPS, atau autentikasi biometrik.

---

## DAFTAR PUSTAKA
* Abdullah, S. (2025). Implementasi Blockchain untuk Keamanan Data Akademik dalam Sistem Informasi Perguruan Tinggi. *Go Infotech: Jurnal Ilmiah STMIK AUB*, 31(1), 149–160.
* Adeniyi, E. A., dkk. (2022). Secure Sensitive Data Sharing Using RSA and ElGamal Cryptographic Algorithms with Hash Functions. *Information (Switzerland)*, 13(10).
* Adi Prasetya, F., & Ardhianto, E. (2026). Implementasi Algoritma Hash Sha-256 untuk Validasi Integritas Artikel Jurnal Ilmiah Berbasis File PDF. *Jurnal Teknologi Informasi*, 8(1), 25–35.
* Amalya, N., dkk. (2023). Kriptografi dan Penerapannya Dalam Sistem Keamanan Data. *Jurnal Media Informatika [JUMIN]*, 4(2), 88-95.
* Aryani, Y., dkk. (2025). Penerapan Unified Modeling Language (UML) pada Digitalisasi Sistem Informasi Perpustakaan. *Digital Transformation Technology*, 4(2), 1032–1040.
* Dalimunthe, J. K., & Ikhwan, A. (2026). Implementasi Sistem Informasi Pengelolaan Arsip Elektronik Dengan Algoritma Hashing Untuk Integritas Data. *CESS*, 11(1), 41–50.
* Daulan, H. (2024). Peran CIA (Confidentiality, Integrity, dan Availability) dalam Keamanan Informasi. *Jurnal Keamanan Siber*, 2(1), 15-28.
* Fauziah, Z., dkk. (2021). Designing Student Attendance Information Systems Web-Base. *APTISI Transactions on Technopreneurship*, 3(1), 23–31.
* Firmansyah, A., dkk. (2026). Integrasi Algoritma SHA-256 dan AES untuk Pengamanan Kredensial dan Data Sensitif. *JUKTISI*, 5(1), 462–468.
* Hutagalung, J., dkk. (2023). Keamanan Data Menggunakan SHA-256 dan RSA pada Digital Signature. *Jurnal Teknologi Informasi Dan Ilmu Komputer*, 10(6), 1213–1222.
* Junianto, E., dkk. (2023). Sistem Informasi Presensi Kantor Desa Cibarusa Jaya Berbasis Desktop dengan Metode Waterfall. *JISAMAR*, 7(3), 607–622.
* Karpan, I., & Maulana, R. (2024). Design of Web-Based Attendance Application Information System. *Journal of Mechatronics and Education*, 1(2), 15–24.
* Lukman, & Budiman, T. (2023). Rancang Bangun Sistem Informasi Manajemen Proyek. *Jurnal Manajemen Informatika Jayakarta*, 3(2), 128–141.
* Nastiti, A., & Astuti, P. (2022). Pengujian Sistem Informasi Menggunakan Metode User Acceptance Testing (UAT). *Jurnal Sains dan Teknologi*, 2(1), 15–22.
* Rahmawati, D., & Sumarsono, A. (2024). Penerapan Arsitektur MVC pada Pengembangan Web. *Jurnal Rekayasa Sistem*, 5(2), 112–120.
* Ridoh, M., dkk. (2026). Digital Attendance System with QR Code and Real-Time Notification. *International Journal of Computer Science*, 14(1), 45–56.
* Rizqiananda, R., & Anwar, S. (2025). Pengujian Black Box pada Aplikasi Sistem Informasi Presensi. *Jurnal Informatika*, 9(1), 88–95.
* Saha, P., dkk. (2026). ZenTap: Secure Attendance Tracking Using SHA-256 and Blockchain. *IEEE Access*, 12, 10234–10245.
* Sinlae, J., dkk. (2024). Pemrograman Web PHP & Database MySQL Modern. *Penerbit Sains Tekno*.
* Surya, A., dkk. (2025). Cryptographic Framework for Cloud-Based Document Storage. *Journal of Cloud Security*, 7(3), 201–215.
* Syarif, M., & Bayu Pratama, E. (2021). Penerapan Model Waterfall dalam Pengembangan Perangkat Lunak. *Jurnal Ilmiah Komputer*, 17(2), 77–86.
* Utama, R., dkk. (2023). Implementasi AES-256 dan SHA-256 pada Ujian Online. *Jurnal Riset Komputer*, 10(4), 310–319.
* Vanesha, L., dkk. (2024). Evaluasi Usability Sistem Informasi Menggunakan User Acceptance Testing. *Jurnal Teknologi Terapan*, 6(1), 50–58.
* Yasin, V. (2021). Rekayasa Perangkat Lunak Berorientasi Objek dengan Pemodelan UML. *Mitra Wacana Media*.
