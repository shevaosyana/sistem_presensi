<?php
class PresensiController {
    private Presensi $model;
    private DetailPresensi $detailModel;
    private Jadwal $jadwalModel;

    public function __construct() {
        SessionHelper::requireLogin();
        $this->model = new Presensi();
        $this->detailModel = new DetailPresensi();
        $this->jadwalModel = new Jadwal();
    }

    public function index(): void {
        $role = SessionHelper::getRole();
        $jadwalId = isset($_GET['jadwal_id']) ? (int)$_GET['jadwal_id'] : null;

        if (!$jadwalId) {
            redirect('?page=jadwal&action=saya');
        }

        $jadwal = $this->jadwalModel->findById($jadwalId);
        if (!$jadwal) redirect('?page=dashboard');

        // Ambil histori presensi untuk jadwal ini
        $history = $this->model->getHistoryByJadwal($jadwalId);
        
        // Cek sesi presensi aktif HARI INI
        $sesiAktif = $this->model->getSesiAktif($jadwalId, date('Y-m-d'));

        // Cek apakah mhs sudah presensi hari ini
        $sudahAbsen = false;
        if ($role === 'mahasiswa' && $sesiAktif) {
            $sudahAbsen = $this->detailModel->cekSudahPresensi($sesiAktif['id'], SessionHelper::getProfilId()) !== null;
        }

        require_once ROOT_PATH.'/views/presensi/index.php';
    }

    public function buka(?int $jadwalId): void {
        SessionHelper::requireRole('dosen');
        if (!$jadwalId) redirect('?page=dashboard');

        $jadwal = $this->jadwalModel->findById($jadwalId);
        
        // Cek apakah hari ini sesuai dengan hari jadwal
        if ($jadwal['hari'] !== FormatHelper::hariIni()) {
            SessionHelper::setFlash('error', 'Anda hanya bisa membuka presensi sesuai hari jadwal perkuliahan.');
            redirect("?page=presensi&jadwal_id=$jadwalId");
        }

        $tgl = date('Y-m-d');
        $jam = date('H:i:s');
        $pertemuanKe = $this->model->getPertemuanKe($jadwalId);

        try {
            $this->model->buka($jadwalId, $tgl, $jam, $pertemuanKe);
            SessionHelper::setFlash('success', 'Presensi berhasil dibuka! Mahasiswa sekarang bisa melakukan absensi.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal membuka presensi.');
        }
        redirect("?page=presensi&jadwal_id=$jadwalId");
    }

    public function tutup(?int $presensiId): void {
        SessionHelper::requireRole('dosen');
        if (!$presensiId) redirect('?page=dashboard');

        $p = $this->model->findById($presensiId);
        if (!$p) redirect('?page=dashboard');

        $db = null;
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            // 1. Tutup presensi
            $this->model->tutup($presensiId);

            // 2. Auto-Alpha untuk mhs yang belum absen (Set Difference)
            // Ambil semua anggota kelas
            $kelasId = (new Jadwal())->findById($p['jadwal_id'])['kelas_id'];
            $semuaMhs = (new Kelas())->getAnggota($kelasId);
            
            // Ambil id mahasiswa yang sudah absen
            $sudahAbsenIds = $this->detailModel->getMahasiswaSudahPresensi($presensiId);

            // Insert Alpha untuk yang belum
            foreach ($semuaMhs as $mhs) {
                if (!in_array($mhs['mahasiswa_id'], $sudahAbsenIds)) {
                    $this->detailModel->insertAlpha($presensiId, $mhs['mahasiswa_id']);
                }
            }

            $db->commit();
            SessionHelper::setFlash('success', 'Sesi presensi ditutup. Mahasiswa yang tidak absen otomatis di-set ALPHA.');
        } catch (Exception $e) {
            if ($db !== null) $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal menutup presensi.');
        }
        redirect("?page=presensi&jadwal_id={$p['jadwal_id']}");
    }

    public function submit(): void {
        SessionHelper::requireRole('mahasiswa');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=dashboard');

        $presensiId = (int)$_POST['presensi_id'];
        $p = $this->model->findById($presensiId);
        
        if (!$p || $p['status'] !== 'AKTIF') {
            SessionHelper::setFlash('error', 'Sesi presensi tidak ditemukan atau sudah ditutup.');
            redirect('?page=dashboard');
        }

        $jadwal = $this->jadwalModel->findById($p['jadwal_id']);
        $mhsId = SessionHelper::getProfilId();

        // Cek dobel
        if ($this->detailModel->cekSudahPresensi($presensiId, $mhsId)) {
            SessionHelper::setFlash('error', 'Anda sudah melakukan presensi hari ini.');
            redirect("?page=presensi&jadwal_id={$p['jadwal_id']}");
        }

        // Algoritma Penentuan Status (Hadir/Terlambat/Alpha)
        $jamSekarang = date('H:i:s');
        $jamMulai = $jadwal['jam_mulai'];
        $toleransi = $jadwal['toleransi_menit'];
        
        $waktuMulai = strtotime($jamMulai);
        $waktuSekarang = strtotime($jamSekarang);
        $menitTelat = ($waktuSekarang - $waktuMulai) / 60;

        $status = 'HADIR';
        $ket = 'Tepat Waktu';

        if ($menitTelat > ($toleransi * 2)) {
            $status = 'ALPHA'; // Terlalu telat
            $ket = 'Sangat Terlambat (Batal Hadir)';
        } elseif ($menitTelat > $toleransi) {
            $status = 'TERLAMBAT';
            $ket = 'Terlambat ' . floor($menitTelat) . ' menit';
        }

        try {
            $this->detailModel->insert($presensiId, $mhsId, $status, $jamSekarang, $ket);
            if ($status === 'ALPHA') {
                SessionHelper::setFlash('error', 'Anda terlalu telat. Presensi ditolak (Alpha).');
            } elseif ($status === 'TERLAMBAT') {
                SessionHelper::setFlash('warning', 'Presensi berhasil (Terlambat).');
            } else {
                SessionHelper::setFlash('success', 'Presensi berhasil!');
            }
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Terjadi kesalahan sistem.');
        }
        redirect("?page=presensi&jadwal_id={$p['jadwal_id']}");
    }

    public function kelola(?int $presensiId): void {
        SessionHelper::requireRole(['dosen', 'admin']);
        if (!$presensiId) redirect('?page=dashboard');

        $p = $this->model->findById($presensiId);
        if (!$p) redirect('?page=dashboard');

        $jadwal = $this->jadwalModel->findById($p['jadwal_id']);
        $detail = $this->detailModel->getByPresensi($presensiId);

        // Jika ada mahasiswa yang belum absen tapi sesi masih aktif, kita bisa pancing tampilin
        // Namun biasanya baru terlihat setelah ditutup.
        require_once ROOT_PATH.'/views/presensi/kelola.php';
    }

    public function ubahStatus(): void {
        SessionHelper::requireRole(['dosen', 'admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=dashboard');

        $detailId = (int)$_POST['detail_id'];
        $presensiId = (int)$_POST['presensi_id'];
        $status = $_POST['status'];
        $ket = $_POST['keterangan'] ?? null;

        if ($status === 'TERLAMBAT' && ($ket === 'Tepat Waktu' || empty(trim($ket ?? '')))) {
            $ket = 'Terlambat';
        } elseif ($status === 'HADIR' && empty(trim($ket ?? ''))) {
            $ket = 'Tepat Waktu';
        }

        try {
            $this->detailModel->updateStatus($detailId, $status, $ket);
            SessionHelper::setFlash('success', 'Status presensi berhasil diubah.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal mengubah status.');
        }
        redirect("?page=presensi&action=kelola&id=$presensiId");
    }

    // Cron job atau dipanggil manual jika ingin mengecek sesi yg expired
    public function autoClose(): void {
        // Ambil semua sesi aktif yang sudah melewati jam_selesai jadwal
        $expired = $this->model->getAktifKedaluwarsa();
        foreach ($expired as $p) {
            $this->tutup($p['id']);
        }
        // Bisa output JSON untuk API
    }
}
