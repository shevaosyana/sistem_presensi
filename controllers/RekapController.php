<?php
class RekapController {
    private DetailPresensi $detailModel;
    private Kelas $kelasModel;
    private Jadwal $jadwalModel;

    public function __construct() {
        SessionHelper::requireLogin();
        $this->detailModel = new DetailPresensi();
        $this->kelasModel = new Kelas();
        $this->jadwalModel = new Jadwal();
    }

    public function index(): void {
        $role = SessionHelper::getRole();
        $profilId = SessionHelper::getProfilId();

        if ($role === 'mahasiswa') {
            // Mhs lihat rekap dirinya per semester
            // Untuk simplicity, ambil kelas yang dia ikuti
            $kelasId = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : null;
            $listKelas = $this->kelasModel->getByMahasiswa($profilId);
            
            $rekap = null;
            $jadwal = null;
            if ($kelasId) {
                $rekap = $this->detailModel->getRekapByMahasiswaKelas($profilId, $kelasId);
                // Dapatkan histori detail
                $db = Database::getInstance();
                $history = $db->prepare("SELECT p.pertemuan_ke, p.tanggal, dp.status, dp.jam_presensi, dp.keterangan FROM detail_presensi dp JOIN presensi p ON dp.presensi_id=p.id JOIN jadwal j ON p.jadwal_id=j.id WHERE dp.mahasiswa_id=? AND j.kelas_id=? ORDER BY p.pertemuan_ke ASC");
                $history->execute([$profilId, $kelasId]);
                $detail = $history->fetchAll();
            }

            require_once ROOT_PATH.'/views/rekap/mhs.php';

        } else {
            // Admin / Dosen lihat rekap kelas
            $kelasId = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : null;
            $listKelas = [];
            
            if ($role === 'dosen') {
                $listKelas = $this->kelasModel->getByDosen($profilId);
            } else {
                $listKelas = $this->kelasModel->getAllSimple();
            }

            $rekap = [];
            $kelas = null;
            if ($kelasId) {
                $rekap = $this->detailModel->getRekapByKelas($kelasId);
                $kelas = $this->kelasModel->findById($kelasId);
            }

            require_once ROOT_PATH.'/views/rekap/index.php';
        }
    }

    public function detail(): void {
        // Dosen/Admin lihat detail presensi 1 mahasiswa di 1 kelas
        SessionHelper::requireRole(['admin', 'dosen']);
        $mid = isset($_GET['mhs_id']) ? (int)$_GET['mhs_id'] : null;
        $kid = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : null;

        if (!$mid || !$kid) redirect('?page=rekap');

        $db = Database::getInstance();
        $mhsStmt = $db->prepare("SELECT nim, nama FROM mahasiswa WHERE id=?");
        $mhsStmt->execute([$mid]);
        $mhs = $mhsStmt->fetch();
        $kls = $this->kelasModel->findById($kid);

        $rekapSum = $this->detailModel->getRekapByMahasiswaKelas($mid, $kid);

        $history = $db->prepare("SELECT p.pertemuan_ke, p.tanggal, dp.status, dp.jam_presensi, dp.keterangan FROM detail_presensi dp JOIN presensi p ON dp.presensi_id=p.id JOIN jadwal j ON p.jadwal_id=j.id WHERE dp.mahasiswa_id=? AND j.kelas_id=? ORDER BY p.pertemuan_ke ASC");
        $history->execute([$mid, $kid]);
        $detail = $history->fetchAll();

        require_once ROOT_PATH.'/views/rekap/detail.php';
    }
}
