<?php
class ProdiController {
    private ProgramStudi $model;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new ProgramStudi();
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search);
        $totalPages = ceil($totalData / $perPage);
        $prodi = $this->model->getAll($search, $page, $perPage);
        
        require_once ROOT_PATH.'/views/prodi/index.php';
    }

    public function create(): void {
        require_once ROOT_PATH.'/views/prodi/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=prodi');
        
        $val = new ValidationHelper($_POST);
        $val->required('kode', 'Kode Prodi')->maxLength('kode', 10, 'Kode Prodi')
            ->required('nama', 'Nama Program Studi')->maxLength('nama', 100, 'Nama Program Studi')
            ->required('jenjang', 'Jenjang')->inList('jenjang', ['D3','D4','S1','S2','S3'], 'Jenjang');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=prodi&action=create');
        }

        if ($this->model->isKodeExist($_POST['kode'])) {
            SessionHelper::setFlash('error', 'Kode Program Studi sudah terdaftar.');
            redirect('?page=prodi&action=create');
        }

        try {
            $this->model->create([
                'kode'     => strtoupper($_POST['kode']),
                'nama'     => $_POST['nama'],
                'jenjang'  => $_POST['jenjang'],
                'fakultas' => FAKULTAS_NAME
            ]);
            SessionHelper::setFlash('success', 'Data Program Studi berhasil ditambahkan.');
            redirect('?page=prodi');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=prodi&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=prodi');
        $p = $this->model->findById($id);
        if (!$p) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=prodi');
        }
        require_once ROOT_PATH.'/views/prodi/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=prodi');
        $p = $this->model->findById($id);
        if (!$p) redirect('?page=prodi');

        $val = new ValidationHelper($_POST);
        $val->required('kode', 'Kode Prodi')->maxLength('kode', 10, 'Kode Prodi')
            ->required('nama', 'Nama Program Studi')->maxLength('nama', 100, 'Nama Program Studi')
            ->required('jenjang', 'Jenjang')->inList('jenjang', ['D3','D4','S1','S2','S3'], 'Jenjang');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=prodi&action=edit&id='.$id);
        }

        if ($this->model->isKodeExist($_POST['kode'], $id)) {
            SessionHelper::setFlash('error', 'Kode Program Studi sudah terdaftar.');
            redirect('?page=prodi&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'kode'     => strtoupper($_POST['kode']),
                'nama'     => $_POST['nama'],
                'jenjang'  => $_POST['jenjang'],
                'fakultas' => FAKULTAS_NAME
            ]);
            SessionHelper::setFlash('success', 'Data Program Studi berhasil diperbarui.');
            redirect('?page=prodi');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=prodi&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=prodi');
        
        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Data tidak dapat dihapus karena sudah memiliki mahasiswa/dosen/mata kuliah yang terikat.');
            redirect('?page=prodi');
        }

        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Data Program Studi berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=prodi');
    }
}
