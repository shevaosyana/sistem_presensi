<?php
/** Session Helper */
class SessionHelper {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            $secure = defined('SESSION_SECURE') ? SESSION_SECURE : false;
            $samesite = defined('SESSION_SAMESITE') ? SESSION_SAMESITE : 'Lax';
            $rawHost = $_SERVER['HTTP_HOST'] ?? '';
            $domain = explode(':', $rawHost)[0];
            if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
                $domain = '';
            }

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => true,
                'samesite' => $samesite,
            ]);

            ini_set('session.use_strict_mode', '1');
            session_start();
        }
    }
    public static function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }
    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            self::setFlash('error', 'Silakan login terlebih dahulu.');
            redirect('?page=auth&action=login');
        }
    }
    public static function requireRole(string|array $roles): void {
        self::requireLogin();
        $allowed = is_array($roles) ? $roles : [$roles];
        if (!in_array($_SESSION['role'] ?? '', $allowed)) {
            self::setFlash('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('?page=dashboard');
        }
    }
    public static function getUserId(): ?int   { return $_SESSION['user_id']   ?? null; }
    public static function getRole(): string   { return $_SESSION['role']      ?? ''; }
    public static function getNama(): string   { return $_SESSION['nama']      ?? ''; }
    public static function getProfilId(): ?int { return $_SESSION['profil_id'] ?? null; }
    public static function getFoto(): ?string  { return $_SESSION['foto']      ?? null; }
    public static function setUser(array $u, string $role, ?int $profilId=null, ?string $foto=null): void {
        $_SESSION['user_id']   = (int)$u['id'];
        $_SESSION['role']      = $role;
        $_SESSION['nama']      = $u['nama'] ?? $u['username'];
        $_SESSION['username']  = $u['username'];
        $_SESSION['profil_id'] = $profilId;
        $_SESSION['foto']      = $foto;
    }
    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']);
            }
            session_unset();
            session_destroy();
        }
    }
    public static function setFlash(string $type, string $msg): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
    }
    public static function getFlash(): ?array {
        if (!empty($_SESSION['flash'])) {
            $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f;
        }
        return null;
    }
}
