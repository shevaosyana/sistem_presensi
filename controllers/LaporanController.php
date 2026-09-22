<?php
class LaporanController {
    private DetailPresensi $detailModel;
    private Kelas $kelasModel;

    public function __construct() {
        SessionHelper::requireRole(['admin', 'dosen']);
        $this->detailModel = new DetailPresensi();
        $this->kelasModel = new Kelas();
    }

    public function index(): void {
        $role = SessionHelper::getRole();
        $profilId = SessionHelper::getProfilId();

        if ($role === 'dosen') {
            $listKelas = $this->kelasModel->getByDosen($profilId);
        } else {
            $listKelas = $this->kelasModel->getAllSimple();
        }
        
        $kelasId  = $_GET['kelas_id'] ?? '';
        $rekap    = [];
        $kelas    = null;
        
        if ($kelasId) {
            $rekap = $this->detailModel->getRekapByKelas((int)$kelasId);
            $kelas = $this->kelasModel->findById((int)$kelasId);
        }
        
        require_once ROOT_PATH.'/views/laporan/index.php';
    }

    public function cetak(): void {
        $kelasId = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : null;
        if (!$kelasId) redirect('?page=laporan');

        $rekap = $this->detailModel->getRekapByKelas($kelasId);
        $kelas = $this->kelasModel->findById($kelasId);

        if (!$kelas) redirect('?page=laporan');
        
        require_once ROOT_PATH.'/views/laporan/cetak.php';
    }

    /**
     * Alias cetak untuk route ?page=laporan&action=pdf
     * Menggunakan view cetak yang sama, lalu print via browser (Ctrl+P)
     */
    public function pdf(): void {
        $this->cetak();
    }
}
