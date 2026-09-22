<?php
class TahunAkademikController {
    private TahunAkademik $model;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new TahunAkademik();
    }

    public function index(): void {
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll();
        $totalPages = ceil($totalData / $perPage);
        $ta = $this->model->getAll($page, $perPage);
        
        require_once ROOT_PATH.'/views/tahunakademik/index.php';
    }

    public function create(): void {
        require_once ROOT_PATH.'/views/tahunakademik/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=tahunakademik');
        
        $val = new ValidationHelper($_POST);
        $val->required('nama', 'Nama Tahun Akademik')->maxLength('nama', 20, 'Nama Tahun Akademik')
            ->required('tahun_mulai', 'Tahun Mulai')->year('tahun_mulai', 'Tahun Mulai')
            ->required('tahun_selesai', 'Tahun Selesai')->year('tahun_selesai', 'Tahun Selesai');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=tahunakademik&action=create');
        }

        if ($this->model->isNamaExist($_POST['nama'])) {
            SessionHelper::setFlash('error', 'Nama Tahun Akademik sudah terdaftar.');
            redirect('?page=tahunakademik&action=create');
        }

        try {
            $this->model->create([
                'nama'          => $_POST['nama'],
                'tahun_mulai'   => $_POST['tahun_mulai'],
                'tahun_selesai' => $_POST['tahun_selesai']
            ]);
            SessionHelper::setFlash('success', 'Tahun Akademik berhasil ditambahkan.');
            redirect('?page=tahunakademik');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=tahunakademik&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=tahunakademik');
        $ta = $this->model->findById($id);
        if (!$ta) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=tahunakademik');
        }
        require_once ROOT_PATH.'/views/tahunakademik/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=tahunakademik');
        $ta = $this->model->findById($id);
        if (!$ta) redirect('?page=tahunakademik');

        $val = new ValidationHelper($_POST);
        $val->required('nama', 'Nama Tahun Akademik')->maxLength('nama', 20, 'Nama Tahun Akademik')
            ->required('tahun_mulai', 'Tahun Mulai')->year('tahun_mulai', 'Tahun Mulai')
            ->required('tahun_selesai', 'Tahun Selesai')->year('tahun_selesai', 'Tahun Selesai');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=tahunakademik&action=edit&id='.$id);
        }

        if ($this->model->isNamaExist($_POST['nama'], $id)) {
            SessionHelper::setFlash('error', 'Nama Tahun Akademik sudah terdaftar.');
            redirect('?page=tahunakademik&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'nama'          => $_POST['nama'],
                'tahun_mulai'   => $_POST['tahun_mulai'],
                'tahun_selesai' => $_POST['tahun_selesai']
            ]);
            SessionHelper::setFlash('success', 'Tahun Akademik berhasil diperbarui.');
            redirect('?page=tahunakademik');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=tahunakademik&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=tahunakademik');
        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Data tidak dapat dihapus karena sudah memiliki semester yang terikat.');
            redirect('?page=tahunakademik');
        }

        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Tahun Akademik berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=tahunakademik');
    }

    public function setAktif(?int $id): void {
        if (!$id) redirect('?page=tahunakademik');
        try {
            $this->model->setAktif($id);
            SessionHelper::setFlash('success', 'Status Tahun Akademik berhasil diubah menjadi Aktif.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal mengubah status.');
        }
        redirect('?page=tahunakademik');
    }
}
