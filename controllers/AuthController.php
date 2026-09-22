<?php
class AuthController {
    private User $userModel;
    public function __construct() { $this->userModel = new User(); }

    public function login(): void {
        if (SessionHelper::isLoggedIn()) redirect('?page=dashboard');
        $flash = SessionHelper::getFlash();
        require_once ROOT_PATH.'/views/auth/login.php';
    }

    public function proses(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('?page=auth&action=login');

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            SessionHelper::setFlash('error', 'Username dan password wajib diisi.');
            redirect('?page=auth&action=login');
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user) {
            SessionHelper::setFlash('error', 'Username tidak ditemukan.');
            redirect('?page=auth&action=login');
        }
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            SessionHelper::setFlash('error', 'Password salah.');
            redirect('?page=auth&action=login');
        }
        if (!$user['is_active']) {
            SessionHelper::setFlash('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
            redirect('?page=auth&action=login');
        }

        session_regenerate_id(true);
        $role = $user['role'];
        $profilId = null;
        $nama = $user['username'];
        $foto = null;

        if ($role === 'mahasiswa') {
            $mhs = (new Mahasiswa())->findByUserId($user['id']);
            if ($mhs) { $profilId = $mhs['id']; $nama = $mhs['nama']; $foto = $mhs['foto']; }
        } elseif ($role === 'dosen') {
            $dsn = (new Dosen())->findByUserId($user['id']);
            if ($dsn) { $profilId = $dsn['id']; $nama = $dsn['nama']; $foto = $dsn['foto']; }
        } else {
            $nama = 'Administrator';
        }

        $user['nama'] = $nama;
        SessionHelper::setUser($user, $role, $profilId, $foto);
        redirect('?page=dashboard');
    }

    public function logout(): void {
        SessionHelper::destroy();
        SessionHelper::start();
        SessionHelper::setFlash('success', 'Anda berhasil logout.');
        redirect('?page=auth&action=login');
    }
}
