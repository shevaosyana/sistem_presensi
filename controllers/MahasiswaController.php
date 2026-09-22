<?php
class MahasiswaController {
    private Mahasiswa $model;
    private ProgramStudi $prodiModel;
    private User $userModel;

    public function __construct() {
        SessionHelper::requireRole('admin');
        $this->model = new Mahasiswa();
        $this->prodiModel = new ProgramStudi();
        $this->userModel = new User();
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $prodi  = $_GET['prodi'] ?? '';
        $angk   = $_GET['angkatan'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search, $prodi, $angk);
        $totalPages = ceil($totalData / $perPage);
        $mahasiswa = $this->model->getAll($search, $prodi, $angk, $page, $perPage);
        
        $listProdi = $this->prodiModel->getAllSimple();
        $listAngkatan = $this->model->getAngkatanList();

        require_once ROOT_PATH.'/views/mahasiswa/index.php';
    }

    public function create(): void {
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/mahasiswa/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=mahasiswa');
        
        $val = new ValidationHelper($_POST);
        $val->required('nim', 'NIM')->numeric('nim', 'NIM')
            ->required('nama', 'Nama Mahasiswa')->maxLength('nama', 100, 'Nama Mahasiswa')
            ->required('prodi_id', 'Program Studi')
            ->required('jenis_kelamin', 'Jenis Kelamin')->inList('jenis_kelamin', ['L', 'P'], 'Jenis Kelamin')
            ->required('angkatan', 'Tahun Angkatan')->year('angkatan', 'Tahun Angkatan');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=mahasiswa&action=create');
        }

        if ($this->model->isNimExist($_POST['nim'])) {
            SessionHelper::setFlash('error', 'NIM sudah terdaftar.');
            redirect('?page=mahasiswa&action=create');
        }
        if ($this->userModel->isUsernameExist($_POST['nim'])) {
            SessionHelper::setFlash('error', 'Username (NIM) sudah digunakan di tabel User.');
            redirect('?page=mahasiswa&action=create');
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $roleMhs = null;
            foreach ($this->userModel->getRoles() as $r) {
                if ($r['nama_role'] === 'mahasiswa') { $roleMhs = $r['id']; break; }
            }
            if (!$roleMhs) throw new Exception("Role mahasiswa tidak ditemukan.");

            $userId = $this->userModel->create([
                'role_id'   => $roleMhs,
                'username'  => $_POST['nim'],
                'password'  => hash('sha256', $_POST['nim']),
                'is_active' => 1
            ]);

            $this->model->create([
                'user_id'       => $userId,
                'prodi_id'      => $_POST['prodi_id'],
                'nim'           => $_POST['nim'],
                'nama'          => $_POST['nama'],
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'angkatan'      => $_POST['angkatan']
            ]);

            $db->commit();
            SessionHelper::setFlash('success', 'Data Mahasiswa berhasil ditambahkan. Password default = NIM.');
            redirect('?page=mahasiswa');
        } catch (Exception $e) {
            $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal menyimpan data: ' . $e->getMessage());
            redirect('?page=mahasiswa&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=mahasiswa');
        $mhs = $this->model->findById($id);
        if (!$mhs) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=mahasiswa');
        }
        $listProdi = $this->prodiModel->getAllSimple();
        require_once ROOT_PATH.'/views/mahasiswa/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=mahasiswa');
        $mhs = $this->model->findById($id);
        if (!$mhs) redirect('?page=mahasiswa');

        $val = new ValidationHelper($_POST);
        $val->required('nim', 'NIM')->numeric('nim', 'NIM')
            ->required('nama', 'Nama Mahasiswa')->maxLength('nama', 100, 'Nama Mahasiswa')
            ->required('prodi_id', 'Program Studi')
            ->required('jenis_kelamin', 'Jenis Kelamin')->inList('jenis_kelamin', ['L', 'P'], 'Jenis Kelamin')
            ->required('angkatan', 'Tahun Angkatan')->year('angkatan', 'Tahun Angkatan');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=mahasiswa&action=edit&id='.$id);
        }

        if ($this->model->isNimExist($_POST['nim'], $id)) {
            SessionHelper::setFlash('error', 'NIM sudah terdaftar.');
            redirect('?page=mahasiswa&action=edit&id='.$id);
        }
        if ($this->userModel->isUsernameExist($_POST['nim'], $mhs['user_id'])) {
            SessionHelper::setFlash('error', 'Username (NIM) sudah digunakan.');
            redirect('?page=mahasiswa&action=edit&id='.$id);
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $this->model->update($id, [
                'prodi_id'      => $_POST['prodi_id'],
                'nim'           => $_POST['nim'],
                'nama'          => $_POST['nama'],
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'angkatan'      => $_POST['angkatan']
            ]);

            $this->userModel->update($mhs['user_id'], [
                'role_id'   => 3,
                'username'  => $_POST['nim'],
                'is_active' => 1
            ]);

            if (!empty($_POST['reset_password'])) {
                $this->userModel->updatePassword($mhs['user_id'], hash('sha256', $_POST['nim']));
            }

            $db->commit();
            SessionHelper::setFlash('success', 'Data Mahasiswa berhasil diperbarui.');
            redirect('?page=mahasiswa');
        } catch (Exception $e) {
            $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=mahasiswa&action=edit&id='.$id);
        }
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=mahasiswa');
        $mhs = $this->model->findById($id);
        if (!$mhs) redirect('?page=mahasiswa');

        if ($this->model->hasRelasi($id)) {
            SessionHelper::setFlash('error', 'Data tidak dapat dihapus karena sudah memiliki relasi (presensi).');
            redirect('?page=mahasiswa');
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            if ($mhs['foto'] && file_exists(UPLOAD_PATH.$mhs['foto'])) {
                unlink(UPLOAD_PATH.$mhs['foto']);
            }
            $this->model->delete($id);
            $this->userModel->delete($mhs['user_id']);

            $db->commit();
            SessionHelper::setFlash('success', 'Data Mahasiswa berhasil dihapus.');
        } catch (Exception $e) {
            $db->rollBack();
            SessionHelper::setFlash('error', 'Gagal menghapus data.');
        }
        redirect('?page=mahasiswa');
    }
}
