<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/database.php';

try {
    $db = Database::getInstance();
    
    // Hash password 'admin123' menggunakan SHA-256
    $password_baru = 'admin123';
    $hash_baru = hash('sha256', $password_baru);
    
    // Cek apakah user admin sudah ada
    $stmt = $db->query("SELECT id FROM users WHERE username = 'admin'");
    $admin = $stmt->fetch();

    if ($admin) {
        // Jika ada, update passwordnya
        $update = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $update->execute([$hash_baru]);
    } else {
        // Jika tidak ada, periksa apakah role admin ada (role_id 1)
        $roleStmt = $db->query("SELECT id FROM roles WHERE nama_role = 'admin'");
        $roleAdmin = $roleStmt->fetch();
        
        if (!$roleAdmin) {
            // Insert role jika belum ada
            $db->exec("INSERT IGNORE INTO roles (id, nama_role) VALUES (1, 'admin'), (2, 'dosen'), (3, 'mahasiswa')");
        }

        // Insert user admin baru
        $insert = $db->prepare("INSERT INTO users (role_id, username, password, is_active) VALUES (1, 'admin', ?, 1)");
        $insert->execute([$hash_baru]);
    }
    
    echo "<div style='font-family:sans-serif; margin: 2rem; padding: 1.5rem; background: #e6ffe6; border: 1px solid #00cc00; border-radius: 8px;'>";
    echo "<h2 style='color: green;'>✅ Akun Admin Berhasil Dibuat / Di-reset!</h2>";
    echo "<p>Password akun <b>admin</b> Anda sekarang sudah diupdate menggunakan algoritma <strong>SHA-256</strong>.</p>";
    echo "<p>Silakan gunakan kredensial berikut untuk login:</p>";
    echo "<ul>";
    echo "<li>Username: <b>admin</b></li>";
    echo "<li>Password: <b>admin123</b></li>";
    echo "</ul>";
    echo "<a href='index.php' style='display:inline-block; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Halaman Login</a>";
    echo "</div>";

} catch (Exception $e) {
    echo "Gagal mereset password: " . $e->getMessage();
}
