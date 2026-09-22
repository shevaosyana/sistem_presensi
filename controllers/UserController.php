<?php
class UserController {
    private User $model;

    public function __construct() {
        // Hanya admin yang bisa mengelola user, KECUALI untuk lihat/edit profil sendiri
        $action = $_GET['action'] ?? 'index';
        if (!in_array($action, ['profil', 'update_profil'])) {
            SessionHelper::requireRole('admin');
        } else {
            SessionHelper::requireLogin();
        }
        $this->model = new User();
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $roleId = $_GET['role'] ?? '';
        $page   = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
        $perPage= ROWS_PER_PAGE;

        $totalData = $this->model->countAll($search, $roleId);
        $totalPages = ceil($totalData / $perPage);
        $users = $this->model->getAll($search, $roleId, $page, $perPage);
        $roles = $this->model->getRoles();
        
        require_once ROOT_PATH.'/views/user/index.php';
    }

    public function create(): void {
        $roles = $this->model->getRoles();
        require_once ROOT_PATH.'/views/user/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=user');
        
        $val = new ValidationHelper($_POST);
        $val->required('username', 'Username')
            ->required('role_id', 'Role')
            ->required('password', 'Password')->minLength('password', 6, 'Password');

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=user&action=create');
        }

        if ($this->model->isUsernameExist($_POST['username'])) {
            SessionHelper::setFlash('error', 'Username sudah digunakan.');
            redirect('?page=user&action=create');
        }

        try {
            // Gunakan SHA-256 sesuai implementasi terbaru
            $hash = hash('sha256', $_POST['password']);
            
            $this->model->create([
                'role_id'   => $_POST['role_id'],
                'username'  => $_POST['username'],
                'password'  => $hash,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ]);
            SessionHelper::setFlash('success', 'User berhasil ditambahkan.');
            redirect('?page=user');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menyimpan data.');
            redirect('?page=user&action=create');
        }
    }

    public function edit(?int $id): void {
        if (!$id) redirect('?page=user');
        $user = $this->model->findById($id);
        if (!$user) {
            SessionHelper::setFlash('error', 'Data tidak ditemukan.');
            redirect('?page=user');
        }
        $roles = $this->model->getRoles();
        require_once ROOT_PATH.'/views/user/edit.php';
    }

    public function update(?int $id): void {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=user');
        $user = $this->model->findById($id);
        if (!$user) redirect('?page=user');

        $val = new ValidationHelper($_POST);
        $val->required('username', 'Username')
            ->required('role_id', 'Role');

        if (!empty($_POST['password'])) {
            $val->minLength('password', 6, 'Password');
        }

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=user&action=edit&id='.$id);
        }

        if ($this->model->isUsernameExist($_POST['username'], $id)) {
            SessionHelper::setFlash('error', 'Username sudah digunakan.');
            redirect('?page=user&action=edit&id='.$id);
        }

        try {
            $this->model->update($id, [
                'role_id'   => $_POST['role_id'],
                'username'  => $_POST['username'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ]);

            if (!empty($_POST['password'])) {
                // Gunakan SHA-256
                $this->model->updatePassword($id, hash('sha256', $_POST['password']));
            }

            SessionHelper::setFlash('success', 'User berhasil diperbarui.');
            redirect('?page=user');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui data.');
            redirect('?page=user&action=edit&id='.$id);
        }
    }

    public function toggle(?int $id): void {
        if (!$id) redirect('?page=user');
        if ($id === SessionHelper::getUserId()) {
            SessionHelper::setFlash('error', 'Anda tidak bisa menonaktifkan akun sendiri yang sedang login.');
            redirect('?page=user');
        }

        try {
            $this->model->toggleStatus($id);
            SessionHelper::setFlash('success', 'Status user berhasil diubah.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal mengubah status.');
        }
        redirect('?page=user');
    }

    public function delete(?int $id): void {
        if (!$id) redirect('?page=user');
        if ($id === SessionHelper::getUserId()) {
            SessionHelper::setFlash('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
            redirect('?page=user');
        }
        
        try {
            $this->model->delete($id);
            SessionHelper::setFlash('success', 'User berhasil dihapus.');
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal menghapus data. Pastikan user tidak memiliki relasi.');
        }
        redirect('?page=user');
    }

    public function profil(): void {
        $userId = SessionHelper::getUserId();
        $user = $this->model->findById($userId);
        require_once ROOT_PATH.'/views/user/profil.php';
    }

    public function updateProfil(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=user&action=profil');
        $userId = SessionHelper::getUserId();
        
        $val = new ValidationHelper($_POST);
        if (!empty($_POST['password_baru'])) {
            $val->required('password_lama', 'Password Lama')
                ->minLength('password_baru', 6, 'Password Baru')
                ->matches('password_baru', 'konfirmasi_password', 'Konfirmasi Password');
        }

        if ($val->fails()) {
            SessionHelper::setFlash('error', $val->firstError());
            redirect('?page=user&action=profil');
        }

        try {
            if (!empty($_POST['password_baru'])) {
                $user = $this->model->findById($userId);
                // Validasi password lama dengan SHA-256
                if (hash('sha256', $_POST['password_lama']) !== $user['password']) {
                    SessionHelper::setFlash('error', 'Password lama tidak sesuai.');
                    redirect('?page=user&action=profil');
                }
                
                // Update ke password baru dengan SHA-256
                $this->model->updatePassword($userId, hash('sha256', $_POST['password_baru']));
                SessionHelper::setFlash('success', 'Password berhasil diperbarui.');
            } else {
                SessionHelper::setFlash('info', 'Tidak ada perubahan profil.');
            }
        } catch (Exception $e) {
            SessionHelper::setFlash('error', 'Gagal memperbarui profil.');
        }
        redirect('?page=user&action=profil');
    }
}
