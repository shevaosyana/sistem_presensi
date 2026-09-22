<?php
class DosenController {
    private Dosen $model;
    private ProgramStudi $prodiModel;
    private User $userModel;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new Dosen();
        $this->prodiModel = new ProgramStudi();
        $this->userModel = new User();
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $prodi  = $_GET['prodi'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search, $prodi);
        $totalPages = ceil($totalData / $perPage);
        $dosen = $this->model->getAll($search, $prodi, $page, $perPage);
        
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/dosen/index.php';
    }

    public function create(): void {
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/dosen/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=dosen');
        
        $val = new ValidationHelper($_POST);
        $val->required('nidn', 'NIDN')->numeric('nidn', 'NIDN')
            ->required('nama', 'Nama Lengkap')->maxLength('nama', 100, 'Nama Lengkap')
            ->required('prodi_id', 'Program Studi')
            ->required('jenis_kelamin', 'Jenis Kelamin')->inList('jenis_kelamin', ['L', 'P'], 'Jenis Kelamin');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=dosen&action=create');
        }

        if ($this->model->isNidnExist($_POST['nidn'])) {
            SessionHelper::setFlash('error', 'NIDN sudah terdaftar.');
            redirect('?page=dosen&action=create');
        }
        if ($this->userModel->isUsernameExist($_POST['nidn'])) {
            SessionHelper::setFlash('error', 'Username (NIDN) sudah digunakan di tabel User.');
            redirect('?page=dosen&action=create');
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $roleDosen = null;
            foreach ($this->userModel->getRoles() as $r) {
                if ($r['nama_role'] === 'dosen') { $roleDosen = $r['id']; break; }
            }
            if (!$roleDosen) throw new Exception("Role dosen tidak ditemukan.");

            $userId = $this->userModel->create([
                'role_id'   => $roleDosen,
                'username'  => $_POST['nidn'],
                'password'  => hash('sha256', $_POST['nidn']),
                'is_active' => 1
            ]);

            $this->model->create([
                'user_id'       => $userId,
                'prodi_id'      => $_POST['prodi_id'],
                'nidn'          => $_POST['nidn'],
                'nama'          => $_POST['nama'],
                'jenis_kelamin' => $_POST['jenis_kelamin']
            ]);

            $db->commit();
            SessionHelper::setFlash('success', 'Data Dosen berhasil ditambahkan. Password default = NIDN.');
            redirect('?page=dosen');
        } catch (Exception $e) {
            $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal menyimpan data: ' . $e->getMessage());
            redirect('?page=dosen&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=dosen');
        $dsn = $this->model->findById($id);
        if (!$dsn) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=dosen');
        }
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/dosen/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=dosen');
        $dsn = $this->model->findById($id);
        if (!$dsn) redirect('?page=dosen');

        $val = new ValidationHelper($_POST);
        $val->required('nidn', 'NIDN')->numeric('nidn', 'NIDN')
            ->required('nama', 'Nama Lengkap')->maxLength('nama', 100, 'Nama Lengkap')
            ->required('prodi_id', 'Program Studi')
            ->required('jenis_kelamin', 'Jenis Kelamin')->inList('jenis_kelamin', ['L', 'P'], 'Jenis Kelamin');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=dosen&action=edit&id='.$id);
        }

        if ($this->model->isNidnExist($_POST['nidn'], $id)) {
            SessionHelper::setFlash('error', 'NIDN sudah terdaftar.');
            redirect('?page=dosen&action=edit&id='.$id);
        }
        if ($this->userModel->isUsernameExist($_POST['nidn'], $dsn['user_id'])) {
            SessionHelper::setFlash('error', 'Username (NIDN) sudah digunakan.');
            redirect('?page=dosen&action=edit&id='.$id);
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $this->model->update($id, [
                'prodi_id'      => $_POST['prodi_id'],
                'nidn'          => $_POST['nidn'],
                'nama'          => $_POST['nama'],
                'jenis_kelamin' => $_POST['jenis_kelamin']
            ]);

            $this->userModel->update($dsn['user_id'], [
                'role_id'   => 2,
                'username'  => $_POST['nidn'],
                'is_active' => 1
            ]);

            if (!empty($_POST['reset_password'])) {
                $this->userModel->updatePassword($dsn['user_id'], hash('sha256', $_POST['nidn']));
            }

            $db->commit();
            SessionHelper::setFlash('success', 'Data Dosen berhasil diperbarui.');
            redirect('?page=dosen');
        } catch (Exception $e) {
            $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=dosen&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=dosen');
        $dsn = $this->model->findById($id);
        if (!$dsn) redirect('?page=dosen');

        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Data tidak dapat dihapus karena dosen mengampu kelas.');
            redirect('?page=dosen');
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            if ($dsn['foto'] && file_exists(UPLOAD_PATH.$dsn['foto'])) {
                unlink(UPLOAD_PATH.$dsn['foto']);
            }
            $this->model->delete($id);
            $this->userModel->delete($dsn['user_id']);

            $db->commit();
            SessionHelper::setFlash('success', 'Data Dosen berhasil dihapus.');
        } catch (Exception $e) {
            $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=dosen');
    }
}
