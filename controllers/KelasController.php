<?php
class KelasController {
    private Kelas $model;
    private KelasAnggota $anggotaModel;
    private MataKuliah $mkModel;
    private Dosen $dosenModel;
    private Semester $smtModel;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new Kelas();
        $this->anggotaModel = new KelasAnggota();
        $this->mkModel = new MataKuliah();
        $this->dosenModel = new Dosen();
        $this->smtModel = new Semester();
    }

    public function index(): void {
        $smtId  = $_GET['smt'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll('', $smtId);
        $totalPages = ceil($totalData / $perPage);
        $kelas = $this->model->getAll('', $smtId, $page, $perPage);
        
        $listSemester = $this->smtModel->getAllSimple();
        require_once ROOT_PATH.'/views/kelas/index.php';
    }

    public function create(): void {
        $listMK = $this->mkModel->getAllSimple();
        $listDosen = $this->dosenModel->getAllSimple();
        $listSemester = $this->smtModel->getAllSimple();
        require_once ROOT_PATH.'/views/kelas/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=kelas');
        
        $val = new ValidationHelper($_POST);
        $val->required('mata_kuliah_id', 'Mata Kuliah')
            ->required('dosen_id', 'Dosen Pengampu')
            ->required('semester_id', 'Semester')
            ->required('nama', 'Nama Kelas')->maxLength('nama', 50, 'Nama Kelas')
            ->required('kapasitas', 'Kapasitas')->numeric('kapasitas', 'Kapasitas');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=kelas&action=create');
        }

        try {
            $this->model->create([
                'mata_kuliah_id' => $_POST['mata_kuliah_id'],
                'dosen_id'       => $_POST['dosen_id'],
                'semester_id'    => $_POST['semester_id'],
                'nama'           => $_POST['nama'],
                'kapasitas'      => $_POST['kapasitas']
            ]);
            SessionHelper::setFlash('success', 'Kelas berhasil ditambahkan.');
            redirect('?page=kelas');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=kelas&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=kelas');
        $kls = $this->model->findById($id);
        if (!$kls) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=kelas');
        }
        $listMK = $this->mkModel->getAllSimple();
        $listDosen = $this->dosenModel->getAllSimple();
        $listSemester = $this->smtModel->getAllSimple();
        require_once ROOT_PATH.'/views/kelas/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=kelas');
        
        $val = new ValidationHelper($_POST);
        $val->required('mata_kuliah_id', 'Mata Kuliah')
            ->required('dosen_id', 'Dosen Pengampu')
            ->required('semester_id', 'Semester')
            ->required('nama', 'Nama Kelas')->maxLength('nama', 50, 'Nama Kelas')
            ->required('kapasitas', 'Kapasitas')->numeric('kapasitas', 'Kapasitas');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=kelas&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'mata_kuliah_id' => $_POST['mata_kuliah_id'],
                'dosen_id'       => $_POST['dosen_id'],
                'semester_id'    => $_POST['semester_id'],
                'nama'           => $_POST['nama'],
                'kapasitas'      => $_POST['kapasitas']
            ]);
            SessionHelper::setFlash('success', 'Kelas berhasil diperbarui.');
            redirect('?page=kelas');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=kelas&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=kelas');
        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Kelas berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data. Pastikan tidak ada data yang terkait.');
        }
        redirect('?page=kelas');
    }

    public function anggota(?int $id): void {
        if (!$id) redirect('?page=kelas');
        $kelasInfo = $this->model->findById($id);
        if (!$kelasInfo) redirect('?page=kelas');

        $anggota = $this->anggotaModel->getByKelas($id);
        $mhsModel = new Mahasiswa();
        $listMahasiswa = $mhsModel->getAllSimple(); // untuk opsi tambah mahasiswa

        require_once ROOT_PATH.'/views/kelas/anggota.php';
    }

    public function tambah_anggota(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=kelas');
        
        $mhsId = $_POST['mahasiswa_id'] ?? null;
        if (!$mhsId) {
            SessionHelper::setFlash('error', 'Pilih mahasiswa terlebih dahulu.');
            redirect('?page=kelas&action=anggota&id='.$id);
        }

        if ($this->anggotaModel->isMhsExistInKelas($id, $mhsId)) {
            SessionHelper::setFlash('error', 'Mahasiswa tersebut sudah terdaftar di kelas ini.');
            redirect('?page=kelas&action=anggota&id='.$id);
        }

        try {
            $this->anggotaModel->create([
                'kelas_id'     => $id,
                'mahasiswa_id' => $mhsId
            ]);
            SessionHelper::setFlash('success', 'Mahasiswa berhasil ditambahkan ke kelas.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menambahkan mahasiswa.');
        }
        redirect('?page=kelas&action=anggota&id='.$id);
    }

    public function hapus_anggota(?int $id): void {
        $anggotaId = $_GET['anggota_id'] ?? null;
        if (!$id || !$anggotaId) redirect('?page=kelas');

        try {
            $this->anggotaModel->delete($anggotaId);
            SessionHelper::setFlash('success', 'Mahasiswa berhasil dihapus dari kelas.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus mahasiswa dari kelas.');
        }
        redirect('?page=kelas&action=anggota&id='.$id);
    }
}
