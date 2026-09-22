<?php
class RuanganController {
    private Ruangan $model;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new Ruangan();
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search);
        $totalPages = ceil($totalData / $perPage);
        $ruangan = $this->model->getAll($search, $page, $perPage);
        
        require_once ROOT_PATH.'/views/ruangan/index.php';
    }

    public function create(): void {
        require_once ROOT_PATH.'/views/ruangan/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=ruangan');
        
        $val = new ValidationHelper($_POST);
        $val->required('kode', 'Kode Ruangan')->maxLength('kode', 20, 'Kode Ruangan')
            ->required('nama', 'Nama Ruangan')->maxLength('nama', 100, 'Nama Ruangan')
            ->required('kapasitas', 'Kapasitas')->numeric('kapasitas', 'Kapasitas');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=ruangan&action=create');
        }

        if ($this->model->isKodeExist($_POST['kode'])) {
            SessionHelper::setFlash('error', 'Kode ruangan sudah terdaftar.');
            redirect('?page=ruangan&action=create');
        }

        try {
            $this->model->create([
                'kode'      => $_POST['kode'],
                'nama'      => $_POST['nama'],
                'kapasitas' => (int)$_POST['kapasitas'],
                'gedung'    => $_POST['gedung'] ?? null
            ]);
            SessionHelper::setFlash('success', 'Data Ruangan berhasil ditambahkan.');
            redirect('?page=ruangan');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=ruangan&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=ruangan');
        $rng = $this->model->findById($id);
        if (!$rng) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=ruangan');
        }
        require_once ROOT_PATH.'/views/ruangan/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=ruangan');
        
        $val = new ValidationHelper($_POST);
        $val->required('kode', 'Kode Ruangan')->maxLength('kode', 20, 'Kode Ruangan')
            ->required('nama', 'Nama Ruangan')->maxLength('nama', 100, 'Nama Ruangan')
            ->required('kapasitas', 'Kapasitas')->numeric('kapasitas', 'Kapasitas');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=ruangan&action=edit&id='.$id);
        }

        if ($this->model->isKodeExist($_POST['kode'], $id)) {
            SessionHelper::setFlash('error', 'Kode ruangan sudah digunakan.');
            redirect('?page=ruangan&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'kode'      => $_POST['kode'],
                'nama'      => $_POST['nama'],
                'kapasitas' => (int)$_POST['kapasitas'],
                'gedung'    => $_POST['gedung'] ?? null
            ]);
            SessionHelper::setFlash('success', 'Data Ruangan berhasil diperbarui.');
            redirect('?page=ruangan');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=ruangan&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=ruangan');
        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Tidak dapat dihapus karena ruangan dipakai di jadwal kuliah.');
            redirect('?page=ruangan');
        }
        
        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Data Ruangan berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=ruangan');
    }
}
