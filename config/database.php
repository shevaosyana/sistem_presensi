<?php
/**
 * Koneksi Database PDO — Singleton
 */
class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=localhost;dbname=sistem_presensi;charset=utf8mb4';
            try {
                self::$instance = new PDO($dsn, 'root', '', [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die('<div style="font-family:sans-serif;padding:2rem;color:#dc2626;background:#fee2e2;border-radius:8px;margin:2rem">'
                    . '<h2>❌ Koneksi Database Gagal</h2>'
                    . '<p>Pastikan MySQL sudah berjalan dan database <strong>sistem_presensi</strong> sudah dibuat.</p>'
                    . '<small>' . htmlspecialchars($e->getMessage()) . '</small></div>');
            }
        }
        return self::$instance;
    }
    private function __clone() {}
}
