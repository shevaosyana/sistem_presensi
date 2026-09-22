<?php
class SemesterController {
    private Semester $model;
    private TahunAkademik $taModel;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new Semester();
        $this->taModel = new TahunAkademik();
    }

    public function index(): void {
        $taId   = $_GET['ta'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($taId);
        $totalPages = ceil($totalData / $perPage);
        $semester = $this->model->getAll($taId, $page, $perPage);
        
        $listTA = $this->taModel->getAllSimple();
        require_once ROOT_PATH.'/views/semester/index.php';
    }

    public function create(): void {
        $listTA = $this->taModel->getAllSimple();
        require_once ROOT_PATH.'/views/semester/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=semester');
        
        $val = new ValidationHelper($_POST);
        $val->required('tahun_akademik_id', 'Tahun Akademik')
            ->required('nama_semester', 'Nama Semester')->inList('nama_semester', ['Ganjil', 'Genap', 'Pendek'], 'Nama Semester');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=semester&action=create');
        }

        if ($this->model->isExist($_POST['tahun_akademik_id'], $_POST['nama_semester'])) {
            SessionHelper::setFlash('error', 'Semester tersebut sudah ada di Tahun Akademik ini.');
            redirect('?page=semester&action=create');
        }

        try {
            $this->model->create([
                'tahun_akademik_id' => $_POST['tahun_akademik_id'],
                'nama_semester'     => $_POST['nama_semester']
            ]);
            SessionHelper::setFlash('success', 'Semester berhasil ditambahkan.');
            redirect('?page=semester');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=semester&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=semester');
        $smt = $this->model->findById($id);
        if (!$smt) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=semester');
        }
        $listTA = $this->taModel->getAllSimple();
        require_once ROOT_PATH.'/views/semester/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=semester');
        $smt = $this->model->findById($id);
        if (!$smt) redirect('?page=semester');

        $val = new ValidationHelper($_POST);
        $val->required('tahun_akademik_id', 'Tahun Akademik')
            ->required('nama_semester', 'Nama Semester')->inList('nama_semester', ['Ganjil', 'Genap', 'Pendek'], 'Nama Semester');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=semester&action=edit&id='.$id);
        }

        if ($this->model->isExist($_POST['tahun_akademik_id'], $_POST['nama_semester'], $id)) {
            SessionHelper::setFlash('error', 'Semester tersebut sudah ada di Tahun Akademik ini.');
            redirect('?page=semester&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'tahun_akademik_id' => $_POST['tahun_akademik_id'],
                'nama_semester'     => $_POST['nama_semester']
            ]);
            SessionHelper::setFlash('success', 'Semester berhasil diperbarui.');
            redirect('?page=semester');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=semester&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=semester');
        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Data tidak dapat dihapus karena sudah memiliki kelas yang terikat.');
            redirect('?page=semester');
        }

        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Semester berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=semester');
    }

    public function setAktif(?int $id): void {
        if (!$id) redirect('?page=semester');
        try {
            $this->model->setAktif($id);
            SessionHelper::setFlash('success', 'Semester berhasil diaktifkan. Semua presensi akan mengacu pada semester ini.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal mengaktifkan semester.');
        }
        redirect('?page=semester');
    }
}
