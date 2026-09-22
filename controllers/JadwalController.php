<?php
class JadwalController {
    private Jadwal $model;
    private Kelas $kelasModel;
    private Ruangan $ruanganModel;

    public function __construct() {
        // Dosen & Mhs bisa lihat jadwal, Admin bisa kelola
        SessionHelper::requireLogin();
        $this->model = new Jadwal();
        $this->kelasModel = new Kelas();
        $this->ruanganModel = new Ruangan();
    }

    public function index(): void {
        SessionHelper::requireRole('admin');
        
        $search = $_GET['search'] ?? '';
        $kelasId= $_GET['kelas'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search, $kelasId);
        $totalPages = ceil($totalData / $perPage);
        $jadwal = $this->model->getAll($search, $kelasId, $page, $perPage);
        
        $listKelas = $this->kelasModel->getAllSimple();

        require_once ROOT_PATH.'/views/jadwal/index.php';
    }

    public function jadwalSaya(): void {
        $role = SessionHelper::getRole();
        $profilId = SessionHelper::getProfilId();
        
        if (!$profilId) {
            SessionHelper::setFlash('error', 'Profil tidak ditemukan.');
            redirect('?page=dashboard');
        }

        $hariIni = FormatHelper::hariIni();
        $jadwal = [];

        if ($role === 'dosen') {
            $jadwal = $this->model->getByDosen($profilId);
        } elseif ($role === 'mahasiswa') {
            $jadwal = $this->model->getByMahasiswa($profilId);
        }

        require_once ROOT_PATH.'/views/jadwal/saya.php';
    }

    public function create(): void {
        SessionHelper::requireRole('admin');
        $listKelas = $this->kelasModel->getAllSimple();
        $listRuangan = $this->ruanganModel->getAllSimple();
        require_once ROOT_PATH.'/views/jadwal/create.php';
    }

    public function store(): void {
        SessionHelper::requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=jadwal');
        
        $val = new ValidationHelper($_POST);
        $val->required('kelas_id', 'Kelas')
            ->required('ruangan_id', 'Ruangan')
            ->required('hari', 'Hari')->inList('hari', ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'], 'Hari')
            ->required('jam_mulai', 'Jam Mulai')->time('jam_mulai', 'Jam Mulai')
            ->required('jam_selesai', 'Jam Selesai')->time('jam_selesai', 'Jam Selesai')
            ->numeric('toleransi_menit', 'Toleransi');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=jadwal&action=create');
        }

        if ($_POST['jam_mulai'] >= $_POST['jam_selesai']) {
            SessionHelper::setFlash('error', 'Jam mulai harus lebih awal dari jam selesai.');
            redirect('?page=jadwal&action=create');
        }

        try {
            $this->model->create([
                'kelas_id'      => $_POST['kelas_id'],
                'ruangan_id'    => $_POST['ruangan_id'],
                'hari'          => $_POST['hari'],
                'jam_mulai'     => $_POST['jam_mulai'],
                'jam_selesai'   => $_POST['jam_selesai'],
                'toleransi_menit' => empty($_POST['toleransi_menit']) ? DEFAULT_TOLERANSI : (int)$_POST['toleransi_menit']
            ]);
            SessionHelper::setFlash('success', 'Jadwal kuliah berhasil ditambahkan.');
            redirect('?page=jadwal');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan jadwal.');
            redirect('?page=jadwal&action=create');
        }
    }

    public function edit(?int $id): void {
        SessionHelper::requireRole('admin');
        if (!$id) redirect('?page=jadwal');
        $j = $this->model->findById($id);
        if (!$j) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=jadwal');
        }
        
        $listKelas = $this->kelasModel->getAllSimple();
        $listRuangan = $this->ruanganModel->getAllSimple();
        require_once ROOT_PATH.'/views/jadwal/edit.php';
    }

    public function update(?int $id): void {
        SessionHelper::requireRole('admin');
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=jadwal');
        
        $val = new ValidationHelper($_POST);
        $val->required('kelas_id', 'Kelas')
            ->required('ruangan_id', 'Ruangan')
            ->required('hari', 'Hari')->inList('hari', ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'], 'Hari')
            ->required('jam_mulai', 'Jam Mulai')->time('jam_mulai', 'Jam Mulai')
            ->required('jam_selesai', 'Jam Selesai')->time('jam_selesai', 'Jam Selesai')
            ->numeric('toleransi_menit', 'Toleransi');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=jadwal&action=edit&id='.$id);
        }

        if ($_POST['jam_mulai'] >= $_POST['jam_selesai']) {
            SessionHelper::setFlash('error', 'Jam mulai harus lebih awal dari jam selesai.');
            redirect('?page=jadwal&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'kelas_id'      => $_POST['kelas_id'],
                'ruangan_id'    => $_POST['ruangan_id'],
                'hari'          => $_POST['hari'],
                'jam_mulai'     => $_POST['jam_mulai'],
                'jam_selesai'   => $_POST['jam_selesai'],
                'toleransi_menit' => empty($_POST['toleransi_menit']) ? DEFAULT_TOLERANSI : (int)$_POST['toleransi_menit']
            ]);
            SessionHelper::setFlash('success', 'Jadwal kuliah berhasil diperbarui.');
            redirect('?page=jadwal');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui jadwal.');
            redirect('?page=jadwal&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        SessionHelper::requireRole('admin');
        if (!$id) redirect('?page=jadwal');
        
        try {
            // Bisa saja gagal jika sudah ada data presensi (Foreign Key cascade/restrict)
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Jadwal berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus jadwal. Pastikan jadwal ini belum memiliki histori presensi.');
        }
        redirect('?page=jadwal');
    }
}
