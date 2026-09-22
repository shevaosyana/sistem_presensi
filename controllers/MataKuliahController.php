<?php
class MataKuliahController {
    private MataKuliah $model;
    private ProgramStudi $prodiModel;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new MataKuliah();
        $this->prodiModel = new ProgramStudi();
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $prodi  = $_GET['prodi'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search, $prodi);
        $totalPages = ceil($totalData / $perPage);
        $matakuliah = $this->model->getAll($search, $prodi, $page, $perPage);
        
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/matakuliah/index.php';
    }

    public function create(): void {
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/matakuliah/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=matakuliah');
        
        $val = new ValidationHelper($_POST);
        $val->required('kode', 'Kode MK')->maxLength('kode', 15, 'Kode MK')
            ->required('nama', 'Nama Mata Kuliah')->maxLength('nama', 100, 'Nama Mata Kuliah')
            ->required('sks', 'SKS')->numeric('sks', 'SKS')
            ->required('jenis', 'Jenis MK')->inList('jenis', ['Teori', 'Praktikum', 'Teori & Praktikum'], 'Jenis MK')
            ->required('prodi_id', 'Program Studi');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=matakuliah&action=create');
        }

        if ($this->model->isKodeExist($_POST['kode'])) {
            SessionHelper::setFlash('error', 'Kode Mata Kuliah sudah terdaftar.');
            redirect('?page=matakuliah&action=create');
        }

        try {
            $this->model->create([
                'prodi_id' => $_POST['prodi_id'],
                'kode'     => strtoupper($_POST['kode']),
                'nama'     => $_POST['nama'],
                'sks'      => $_POST['sks'],
                'jenis'    => $_POST['jenis']
            ]);
            SessionHelper::setFlash('success', 'Mata Kuliah berhasil ditambahkan.');
            redirect('?page=matakuliah');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=matakuliah&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=matakuliah');
        $mk = $this->model->findById($id);
        if (!$mk) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=matakuliah');
        }
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/matakuliah/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=matakuliah');
        $mk = $this->model->findById($id);
        if (!$mk) redirect('?page=matakuliah');

        $val = new ValidationHelper($_POST);
        $val->required('kode', 'Kode MK')->maxLength('kode', 15, 'Kode MK')
            ->required('nama', 'Nama Mata Kuliah')->maxLength('nama', 100, 'Nama Mata Kuliah')
            ->required('sks', 'SKS')->numeric('sks', 'SKS')
            ->required('jenis', 'Jenis MK')->inList('jenis', ['Teori', 'Praktikum', 'Teori & Praktikum'], 'Jenis MK')
            ->required('prodi_id', 'Program Studi');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=matakuliah&action=edit&id='.$id);
        }

        if ($this->model->isKodeExist($_POST['kode'], $id)) {
            SessionHelper::setFlash('error', 'Kode Mata Kuliah sudah terdaftar.');
            redirect('?page=matakuliah&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'prodi_id' => $_POST['prodi_id'],
                'kode'     => strtoupper($_POST['kode']),
                'nama'     => $_POST['nama'],
                'sks'      => $_POST['sks'],
                'jenis'    => $_POST['jenis']
            ]);
            SessionHelper::setFlash('success', 'Mata Kuliah berhasil diperbarui.');
            redirect('?page=matakuliah');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=matakuliah&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=matakuliah');
        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Data tidak dapat dihapus karena mata kuliah digunakan pada kelas.');
            redirect('?page=matakuliah');
        }

        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'Mata Kuliah berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=matakuliah');
    }
}
